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
        Route::get('/delete','CabangController@delete');
    });
});
