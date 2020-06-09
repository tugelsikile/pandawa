<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cetak-loading',function (){
    return 'Loading ...';
});

Route::group(['prefix'=>'tagihan'],function (){
    Route::post('/cetak-laporan','TagihanController@CetakLaporan');
    Route::post('/cetak-rekap','TagihanController@CetakRekap');
    Route::get('/cetak-invoice','TagihanController@CetakInvoice');
});

Route::group(['prefix'=>'lists'],function (){
    Route::post('/customers','ListController@CustomersCabang');
    Route::post('/cabang','ListController@cabang');
    Route::post('/members','ListController@members');
    Route::post('/produk-cabang','ProdukController@getCabangProduk');
});

Route::group(['prefix'=>'regional'],function (){

});