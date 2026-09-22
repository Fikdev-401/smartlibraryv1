<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel `videos` menyimpan referensi video YouTube (bukan file video).
     * Operator menempelkan URL YouTube, sistem mengekstrak video_id 11-char,
     * dan video ditampilkan via iframe YouTube embed di UI member/portal.
     *
     * Karena video YouTube diakses via iframe (client-side, butuh internet),
     * backend tidak perlu fetch/konten video — cukup simpan URL + ID.
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->string('id_video', 36)->primary();
            $table->string('judul', 255);
            $table->string('kategori_video', 50);
            $table->text('youtube_url');
            $table->string('youtube_id', 20);
            $table->text('deskripsi')->nullable();
            $table->string('id_user', 36)->nullable();
            $table->string('id_sekolah', 36)->nullable();
            $table->enum('aktif', ['Y', 'N'])->default('Y');
            $table->timestamps();

            $table->index('kategori_video', 'idx_videos_kategori');
            $table->index('aktif', 'idx_videos_aktif');
            $table->index('id_sekolah', 'idx_videos_sekolah');
            $table->index('created_at', 'idx_videos_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
