#!/usr/bin/env bash
# Jalankan PHP 7.4 (image php:7.4-cli yang sudah ter-pull di Docker lokal)
# dengan project di-mount ke /app di dalam container.
#
# Usage:
#   ./php74.sh --version                                # cek versi PHP
#   ./php74.sh artisan --version                        # cek versi Laravel
#   ./php74.sh artisan migrate:status                   # cek koneksi DB
#   ./php74.sh artisan tinker                           # REPL
#   ./php74.sh artisan key:generate                     # apapun selain serve
#   ./php74.sh start                                    # jalanin dev server di port 8000 (publish ke host)
#   ./php74.sh stop                                     # stop container server yang sedang jalan
#
# Catatan:
# - System PHP (8.4) tetap utuh, tidak diubah.
# - Project folder di-mount sebagai volume, jadi edit file di host langsung
#   kelihatan di container.
# - MySQL di host diakses via 'host.docker.internal' (fitur Docker 20.10+).
#   Kalau mau pakai artisan dari dalam container, tambahkan baris ini di .env:
#       DB_HOST=host.docker.internal
# - Untuk 'start', port 8000 di-publish dari container ke host, jadi bisa
#   diakses di http://localhost:8000.

set -euo pipefail

# Resolve path absolut dari project ini (tempat script ini berada).
PROJECT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$PROJECT_DIR"

# Pilih image: pakai custom kalau ada, fallback ke official.
if docker image inspect smartlibrary-php74 >/dev/null 2>&1; then
    IMAGE="smartlibrary-php74"
else
    IMAGE="php:7.4-cli"
fi

# Container name untuk server (biar bisa di-stop / di-restart).
CONTAINER_NAME="smartlibrary-php74-server"
SERVER_PORT="${SERVER_PORT:-8000}"

case "${1:-}" in
    start)
        # Stop container lama kalau ada.
        docker rm -f "$CONTAINER_NAME" 2>/dev/null || true

        # Mode network:
        #   host   -> container ikut network namespace host. INI DEFAULT karena
        #             MariaDB di host listen hanya di 127.0.0.1 (bind-address),
        #             jadi lewat bridge (gateway 172.17.0.1) pasti "Connection
        #             refused". Dengan network host, DB_HOST=127.0.0.1 di .env
        #             langsung nembak MariaDB host, dan port 8000 nempel ke host.
        #   bridge -> cara lama: publish port. Ini HANYA jalan kalau MariaDB
        #             di-set bind-address=0.0.0.0 / 172.17.0.1 dan .env pakai
        #             DB_HOST=host.docker.internal.
        NETWORK_MODE="${NETWORK_MODE:-host}"

        if [ "$NETWORK_MODE" = "host" ]; then
            docker run -d --rm \
                --name "$CONTAINER_NAME" \
                --network host \
                -v "$PROJECT_DIR:/app" \
                -w /app \
                "$IMAGE" \
                php artisan serve --host=0.0.0.0 --port="$SERVER_PORT"
        else
            docker run -d --rm \
                --name "$CONTAINER_NAME" \
                --add-host=host.docker.internal:host-gateway \
                -v "$PROJECT_DIR:/app" \
                -w /app \
                -p "$SERVER_PORT:8000" \
                "$IMAGE" \
                php artisan serve --host=0.0.0.0 --port=8000
        fi
        echo "✅ Server started (network=$NETWORK_MODE). Akses di http://localhost:$SERVER_PORT"
        echo "   Logs: docker logs -f $CONTAINER_NAME"
        echo "   Stop: ./php74.sh stop"
        ;;

    stop)
        if docker ps -a --format '{{.Names}}' | grep -q "^${CONTAINER_NAME}$"; then
            docker rm -f "$CONTAINER_NAME"
            echo "🛑 Server stopped."
        else
            echo "ℹ️  Server tidak jalan."
        fi
        ;;

    logs)
        docker logs -f "$CONTAINER_NAME"
        ;;

    status)
        if docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_NAME}$"; then
            echo "✅ Server running di http://localhost:$SERVER_PORT"
            docker ps --filter "name=$CONTAINER_NAME" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
        else
            echo "❌ Server tidak jalan. Jalankan: ./php74.sh start"
        fi
        ;;

    *)
        # Mode biasa: jalankan command di dalam container.
        # Deteksi TTY: kalau ada (terminal interaktif) pakai -it, kalau tidak (piped
        # output, cron, CI) pakai -i saja. Tanpa ini, `docker run -it` gagal dengan
        # "the input device is not a TTY" saat output di-redirect.
        DOCKER_TTY_FLAGS="-i"
        if [ -t 1 ]; then
            DOCKER_TTY_FLAGS="-it"
        fi

        # Network: default 'host' supaya container bisa akses MariaDB host
        # (MariaDB bind 127.0.0.1, tidak mendengarkan di bridge). Sama seperti
        # mode 'start'. Set NETWORK_MODE=bridge untuk perilaku lama.
        NETWORK_MODE="${NETWORK_MODE:-host}"

        if [ "$NETWORK_MODE" = "host" ]; then
            docker run --rm $DOCKER_TTY_FLAGS \
                --network host \
                -v "$PROJECT_DIR:/app" \
                -w /app \
                "$IMAGE" \
                php "$@"
        else
            # --add-host=host.docker.internal:host-gateway agar container bisa akses
            # MySQL di host via "host.docker.internal".
            docker run --rm $DOCKER_TTY_FLAGS \
                --add-host=host.docker.internal:host-gateway \
                -v "$PROJECT_DIR:/app" \
                -w /app \
                "$IMAGE" \
                php "$@"
        fi
        ;;
esac
