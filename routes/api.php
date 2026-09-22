<?php

use Illuminate\Http\Request;


Route::post('login', 'API\UserController@login');
Route::get('sekolah', 'API\UserController@sekolah');

Route::get('public/all-ebook','API\EbookController@showAll');
Route::get('user-detail/{user}','API\UserController@detail');

// ebook
Route::post('cari-ebook','API\EbookController@cariEbook');
Route::get('detail-ebook/{id}','API\EbookController@detail');

Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function()
{
	// member
	Route::get('all-user','API\UserController@showAll');
	Route::put('update/{user}', 'API\UserController@update');
	Route::delete('user/{user}','API\UserController@destroy');
	Route::get('user-profile/{id}','API\UserController@showProfile');

	// kategori ebook
	Route::get('all-kategori-ebook','API\KategoriEbookController@showAll');
	Route::post('store-kategori-ebook/{admin}','API\KategoriEbookController@store');
	Route::put('update-kategori-ebook/{kategori}','API\KategoriEbookController@update');
	Route::delete('kategori-ebook/{kategori}','API\KategoriEbookController@destroy');

	// mitra
	Route::get('all-mitra','API\MitraController@showAll');
	Route::post('store-mitra/{admin}','API\MitraController@store');
	Route::put('update-mitra/{mitra}','API\MitraController@update');
	Route::delete('mitra/{mitra}','API\MitraController@destroy');

	// ebook
	Route::get('all-ebook-by-kategori/{kategori}','API\EbookController@showAllByKategori');
	Route::get('all-ebook','API\EbookController@showAll');
	Route::post('store-ebook/{admin}','API\EbookController@store');
	Route::put('update-ebook/{ebook}','API\EbookController@update');
	Route::delete('ebook/{ebook}','API\EbookController@destroy');
	

});
