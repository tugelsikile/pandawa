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
Route::post('preview-id-pelanggan','TemplateController@PreviewCustomerID');
Route::post('preview-harga','TemplateController@PreviewHarga');

Auth::routes();
Route::group(['middleware'=>'auth'],function (){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix'=>'lists'],function (){
        Route::post('/cabang','ListController@cabang');
        Route::post('/members','ListController@members');
        Route::post('/produk-cabang','ProdukController@getCabangProduk');
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

        Route::post('/kode-produk','ProdukController@kodeProduk');
        Route::get('/create','ProdukController@create');
        Route::post('/create','ProdukController@create');
        Route::get('/update','ProdukController@update');
        Route::post('/update','ProdukController@update');
        Route::post('/delete','ProdukController@delete');
        Route::post('/bulk-delete','ProdukController@bulkDelete');
    });

    Route::group(['prefix'=>'admin-customer'],function (){
        Route::get('/','CustomerController@index');
        Route::post('/table','CustomerController@table');

        Route::post('/customer-id','CustomerController@customerID');
        Route::get('/create','CustomerController@create');
        Route::post('/create','CustomerController@create');

        Route::get('/update','CustomerController@update');
        Route::post('/update','CustomerController@update');

        Route::post('/delete','CustomerController@delete');
        Route::post('/bulk-delete','CustomerController@bulkDelete');

        Route::post('/set-status','CustomerController@setStatus');
    });

    Route::group(['prefix'=>'admin-tagihan'],function (){
        Route::get('/','TagihanController@index');
        Route::post('/table','TagihanController@table');

        Route::get('/generate-invoice','TagihanController@FormGenerate');
        Route::post('/generate-invoice','TagihanController@FormGenerate');

        Route::get('/create','TagihanController@create');
        Route::post('/create','TagihanController@create');

        Route::get('/update','TagihanController@update');
        Route::post('/update','TagihanController@update');

        Route::post('/delete','TagihanController@delete');
        Route::post('/bulk-delete','TagihanController@bulkDelete');
    });
});
