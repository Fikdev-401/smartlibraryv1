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
// header('location: https://smartelibrary.elayanan.info/');

Route::get('/', 'portalCont@index')->name('dashboard');
Route::get('/signup', 'registerCont@index')->name('register');
Route::post('/signup', 'registerCont@post')->name('register.post');
Route::get('/reset', 'registerCont@reset')->name('reset');
Route::post('/reset', 'registerCont@resetPassword')->name('resetPassword');

Route::get('/pdf/user','portalCont@pdfUser')->name('pdf.user');
Route::get('/pdf/daftar-buku','portalCont@pdfBuku')->name('pdf.buku');

//Route::get('/refreshcaptcha', 'registerCont@refreshCaptcha');
//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/signin', 'signinCont@show')->name('signin');
Route::post('/signin', 'signinCont@post')->name('signin.post');
Route::get('/signout', 'signinCont@logout')->name('signout');

Route::get('/blog/{id}', 'Admin\BlogCont@index')->name('blog');

Route::group(['middleware'=>['auth:user','ceklevel:SUPERUSER,ADMIN,OPERATOR']], function(){
	Route::get('/dev', 'Admin\devCont@home')->name('dev.home');
	Route::get('/adm', 'Admin\devCont@home')->name('adm.home');
	Route::get('/op', 'opCont@home')->name('op.home');

	Route::get('/gen/kategori', 'Admin\KategoriCont@index')->name('gen.kategori');
	Route::get('/gen/kategori/create', 'Admin\KategoriCont@create')->name('gen.kategori.insert');
	Route::post('/gen/kategori/post', 'Admin\KategoriCont@store')->name('gen.kategori.post');
	Route::get('/gen/kategori/edit/{id}', 'Admin\KategoriCont@edit')->name('gen.kategori.edit');
	Route::post('/gen/kategori/update', 'Admin\KategoriCont@update')->name('gen.kategori.update');
	Route::get('/gen/kategori/delete/{id}', 'Admin\KategoriCont@destroy')->name('gen.kategori.delete');

	Route::get('/gen/ebook', 'Admin\EbookCont@index')->name('gen.ebook');
	Route::get('/gen/ebook/create', 'Admin\EbookCont@create')->name('gen.ebook.insert');
	Route::post('/gen/ebook/post', 'Admin\EbookCont@store')->name('gen.ebook.post');
	Route::get('/gen/ebook/edit/{id}', 'Admin\EbookCont@edit')->name('gen.ebook.edit');
	Route::post('/gen/ebook/update', 'Admin\EbookCont@update')->name('gen.ebook.update');
	Route::get('/gen/ebook/delete/{id}', 'Admin\EbookCont@destroy')->name('gen.ebook.delete');

	Route::get('/header/image', 'Admin\ImageHeaderCont@index')->name('portal.header.image');
	Route::post('/header/image/store', 'Admin\ImageHeaderCont@post')->name('portal.header.image.post');

	Route::get('/tentang/aplikasi', 'Admin\TentangAplikasiCont@index')->name('portal.tentang.aplikasi');
	Route::post('/tentang/aplikasi/store', 'Admin\TentangAplikasiCont@post')->name('portal.tentang.aplikasi.post');

	Route::get('/mitra', 'Admin\MitraCont@index')->name('portal.mitra');
	Route::get('/mitra/create', 'Admin\MitraCont@create')->name('portal.mitra.insert');
	Route::post('/mitra/store', 'Admin\MitraCont@store')->name('portal.mitra.post');
	Route::get('/mitra/edit/{id}', 'Admin\MitraCont@edit')->name('portal.mitra.edit');
	Route::post('/mitra/update', 'Admin\MitraCont@update')->name('portal.mitra.update');
	Route::get('/mitra/delete/{id}', 'Admin\MitraCont@destroy')->name('portal.mitra.delete');

	Route::post('/kontak/kami/store', 'Admin\KontakKamiCont@store')->name('portal.kontak.kami');
	Route::get('/pesan/masuk', 'Admin\KontakKamiCont@show')->name('pesan.masuk');
	Route::get('/pesan/masuk/delete/{id}', 'Admin\KontakKamiCont@destroy')->name('pesan.masuk.delete');

	Route::get('/gen/pengguna', 'Admin\devCont@showuser')->name('gen.pengguna');
	Route::get('/gen/pengguna/insert', 'Admin\devCont@newuser')->name('gen.pengguna.insert');
	Route::post('/gen/pengguna/post', 'Admin\devCont@postuser')->name('gen.pengguna.post');
	Route::get('/gen/pengguna/edit/{id}', 'Admin\devCont@edituser')->name('gen.pengguna.edit');
	Route::post('/gen/pengguna/update', 'Admin\devCont@updateuser')->name('gen.pengguna.update');
	Route::get('/gen/pengguna/delete/{id}', 'Admin\devCont@deluser')->name('gen.pengguna.delete');
	
	Route::get('/gen/user/upload', 'Admin\UserUploadController@index')->name('gen.user.upload');
	Route::get('/gen/user/upload/details', 'Admin\UserUploadController@details')->name('gen.user.upload.details');
	Route::get('/gen/user/upload/details/ajax', 'Admin\UserUploadController@ajax')->name('user.details.upload');
	
	Route::get('/gen/sekolah', 'Admin\SekolahController@index')->name('sekolah.index');
	Route::get('/gen/sekolah/create', 'Admin\SekolahController@create')->name('sekolah.create');
	Route::post('/gen/sekolah/create', 'Admin\SekolahController@store')->name('sekolah.post');
	Route::get('/gen/sekolah/edit', 'Admin\SekolahController@edit')->name('sekolah.edit');
	Route::post('/gen/sekolah/edit', 'Admin\SekolahController@update')->name('sekolah.update');
	Route::get('/gen/sekolah/delete/{id}', 'Admin\SekolahController@delete')->name('sekolah.delete');
	
	Route::get('/member/sekolah', 'Admin\devCont@membersekolah')->name('member.sekolah');
	Route::get('/member/non-aktif', 'Admin\devCont@membernonaktif')->name('member.nonaktif');
	Route::get('/member/aktivasi', 'Admin\devCont@memberaktivasi')->name('member.aktivasi');
	
	Route::get('/sekolah/delete/{id}','Admin\SekolahController@deleteSekolah');
	
});
