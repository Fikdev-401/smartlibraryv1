<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'portal\PortalController@index');

/*
|--------------------------------------------------------------------------
|	Admin Route
|--------------------------------------------------------------------------
|
|	Dashboard Route
*/

Route::get('/cpanel','admin\HomeController@index');
/* |------------IDENTITAS PERPUSTAKAAN ---------------*/
Route::get('/cpanel/identitas','admin\IdentitasController@index');
Route::post('/cpanel/identitas/post','admin\IdentitasController@store');

/* |------------BANNER ---------------*/
Route::get('/cpanel/banner','admin\BannerController@index');
Route::get('/cpanel/banner/create','admin\BannerController@create');
Route::post('/cpanel/banner/post','admin\BannerController@store');

/* |------------JENIS BUKU ---------------*/
Route::get('/cpanel/jenis-buku','admin\JenisBukuController@index');
Route::get('/cpanel/jenis-buku/create','admin\JenisBukuController@create');
Route::post('/cpanel/jenis-buku/post','admin\JenisBukuController@store');
/* |------------KATEGORI BUKU ---------------*/
Route::get('/cpanel/kategori-buku','admin\KategoriBukuController@index');
Route::get('/cpanel/kategori-buku/create','admin\KategoriBukuController@create');
Route::post('/cpanel/kategori-buku/post','admin\KategoriBukuController@store');
/* |------------RAK BUKU ---------------*/
Route::get('/cpanel/rak','admin\RakController@index');
Route::get('/cpanel/rak/create','admin\RakController@create');
Route::post('/cpanel/rak/post','admin\RakController@store');
/* |------------BUKU ---------------*/
Route::get('/cpanel/buku','admin\BukuController@index');
Route::get('/cpanel/buku/create','admin\BukuController@create');
Route::post('/cpanel/buku/post','admin\BukuController@store');
Route::get('/cpanel/lap-buku','admin\BukuController@printbuku');
/* |------------LOKASI BUKU ---------------*/
Route::get('/cpanel/lokasi-buku','admin\RakBukuController@index');
Route::get('/cpanel/lokasi-buku/create','admin\RakBukuController@create');
Route::post('/cpanel/lokasi-buku/post','admin\RakBukuController@store');
Route::get('/cpanel/lap-rak-buku','admin\RakBukuController@laprak');
/* |------------EBUKU ---------------*/
Route::get('/cpanel/ebook','admin\EBookController@index');
Route::get('/cpanel/ebook/create','admin\EBookController@create');
Route::post('/cpanel/ebook/post','admin\EBookController@store');
Route::get('/cpanel/lap-ebook','admin\EBookController@lapebook');
/* |------------ANGGOTA---------------*/
Route::get('/cpanel/anggota','admin\AnggotaController@index');
Route::get('/cpanel/anggota/create','admin\AnggotaController@create');
Route::post('/cpanel/anggota/post','admin\AnggotaController@store');
Route::get('/cpanel/anggota/print-kartu/{id}','admin\AnggotaController@cetak_kartu');
Route::get('/cpanel/lap-anggota','admin\AnggotaController@lapanggota');
/* |------------BUKU HILANG---------------*/
Route::get('/cpanel/buku-hilang','admin\BukuHilangController@index');
Route::get('/cpanel/buku-hilang/create','admin\BukuHilangController@create');
Route::post('/cpanel/buku-hilang/post','admin\BukuHilangController@store');
Route::get('/cpanel/lap-buku-hilang','admin\BukuHilangController@lapbukuhilang');
Route::get('/cpanel/lap-buku-hilang/{id1}/{id2}','admin\BukuHilangController@lapbukuhilangbulan');
Route::get('/cpanel/lap-buku-hilang/{id1}','admin\BukuHilangController@lapbukuhilangtahun');
/* |------------PEMINJAMAN---------------*/
Route::get('/cpanel/peminjaman','admin\PeminjamanController@index');
Route::get('/cpanel/peminjaman/create','admin\PeminjamanController@create');
Route::get('/cpanel/lap-peminjaman','admin\PeminjamanController@lappeminjaman');
Route::get('/cpanel/lap-peminjaman-bulan/{id1}/{id2}','admin\PeminjamanController@lappeminjamanbulan');
Route::get('/cpanel/lap-peminjaman-tahun/{id1}','admin\PeminjamanController@lappeminjamantahun');
Route::get('/cpanel/peminjaman-tempo','admin\PeminjamanController@peminjamantempo');
Route::get('/cpanel/peminjaman-tempo/{id1}/{id2}','admin\PeminjamanController@lappeminjamantempo');
Route::post('/cpanel/peminjaman/post','admin\PeminjamanController@store');
/* |------------PENGEMBALIAN---------------*/
Route::get('/cpanel/pengembalian','admin\PengembalianController@index');
Route::get('/cpanel/pengembalian/create','admin\PengembalianController@create');
Route::get('/cpanel/getpeminjaman/{id}','admin\PengembalianController@getPeminjaman');
Route::get('/cpanel/getdurasipeminjaman/{idanggota}/{idbuku}','admin\PengembalianController@getDurasiPeminjaman');
Route::post('/cpanel/pengembalian/post','admin\PengembalianController@store');
Route::get('/cpanel/lap-pengembalian','admin\PengembalianController@lapPengembalian');
Route::get('/cpanel/lap-pengembalian-bulan/{id1}/{id2}','admin\PengembalianController@lappengembalianbulan');
Route::get('/cpanel/lap-pengembalian-tahun/{id1}','admin\PengembalianController@lappengembaliantahun');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*============PORTAL ROUTE=============*/

