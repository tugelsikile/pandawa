<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=>'regional'],function (){
    Route::get('/get-kab','RegionalController@kabupaten');
    Route::get('/get-kec','RegionalController@kecamatan');
    Route::get('/get-desa','RegionalController@desa');
});
Route::post('preview-id','TemplateController@PreviewID');

Auth::routes();
Route::group(['middleware'=>'auth'],function (){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix'=>'lists'],function (){
        Route::post('/cabang','ListController@cabang');
        Route::post('/members','ListController@members');
    });

    Route::group(['prefix'=>'admin-cabang'],function (){
        Route::get('/','CabangController@index');
        Route::post('/table','CabangController@table');

        Route::get('/create','CabangController@create');
        Route::post('/create','CabangController@create');
        Route::get('/update','CabangController@update');
        Route::post('/update','CabangController@update');
        Route::post('/delete','CabangController@delete');
    });

    Route::group(['prefix'=>'admin-produk'],function (){
        Route::get('/','ProdukController@index');
        Route::post('/table','ProdukController@table');

        Route::get('/create','ProdukController@create');
        Route::post('/create','ProdukController@create');
        Route::get('/update','ProdukController@update');
        Route::post('/update','ProdukController@update');
        Route::post('/delete','ProdukController@delete');
        Route::post('/bulk-delete','ProdukController@bulkDelete');
    });
});
