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
        // Share $kat ke SEMUA view agar header portal yang pakai dropdown
        // kategori ebook tidak error "Undefined variable $kat" di halaman
        // baru (mis. VideoCont::memberIndex) yang tidak load kategori manual.
        // Header di portal/layout/header.blade.php expects loop:
        //   @foreach($kat as $r)
        //     <li><a href="...">{{$r->nama_kat}}</a></li>
        //   @endforeach
        // Schema: tabel `kategori` punya kolom id_kategori + nama_kat.
        view()->share('kat', \App\Admin\Kategori::orderBy('nama_kat','ASC')->get());
    }
}
