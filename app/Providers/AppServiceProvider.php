<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Laravel\Passport\Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Skip DB-bound view share di CLI supaya `php artisan ...`
        // (route:list, migrate, tinker) tidak crash hanya karena DB belum konek.
        if ($this->app->runningInConsole()) {
            return;
        }

        // Share $kat ke SEMUA view. Header portal (portal/layout/header.blade.php)
        // pakai dropdown kategori:
        //   @foreach($kat as $r) <li>{{ $r->nama_kat }}</li> @endforeach
        // Schema tabel `kategori`: id_kategori, nama_kat.
        //
        // Pakai view composer LAZY supaya:
        //   - query DB hanya jalan kalau benar-benar ada view yang render;
        //   - kalau DB lagi down, halaman yang tidak butuh $kat tetap jalan.
        //
        // Composer '*' dipanggil untuk SETIAP view yang render (layout, header,
        // footer, include). Holder $kat di bawah men-cache hasil query sekali per
        // request supaya `select * from kategori` tidak berulang.
        $kat         = new \stdClass();
        $kat->loaded = false;
        $kat->items  = collect();

        view()->composer('*', function ($view) use ($kat) {
            if (! $kat->loaded) {
                try {
                    $kat->items = \App\Admin\Kategori::orderBy('nama_kat', 'ASC')->get();
                } catch (\Throwable $e) {
                    // DB belum siap / network error - jangan crash halaman.
                    // Dropdown kategori tampil kosong, lebih baik daripada 500.
                    $kat->items = collect();
                }
                $kat->loaded = true;
            }

            $view->with('kat', $kat->items);
        });
    }
}
