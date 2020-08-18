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
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::group(['prefix'=>'regional'],function (){
    Route::get('/get-kab','RegionalController@kabupaten');
    Route::get('/get-kec','RegionalController@kecamatan');
    Route::get('/get-desa','RegionalController@desa');
});
Route::post('preview-id','TemplateController@PreviewID');
Route::post('preview-id-pelanggan','TemplateController@PreviewCustomerID');
Route::post('preview-harga','TemplateController@PreviewHarga');

Auth::routes();

Route::group(['middleware'=>'auth','prefix'=>'user'],function (){
    Route::get('/profile','UserController@profile')->name('user.profile');
    Route::post('/profile','UserController@profile')->name('user.profile');
});

Route::group(['middleware'=>['auth','systemAccess']],function (){
    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix'=>'lists'],function (){
        Route::post('/cabang','ListController@ListCabang');
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

        Route::get('/performa-tagihan','CabangController@PerformaTagihan');
        Route::post('/performa-tagihan','CabangController@PerformaTagihan');
        Route::post('/cetak-performa-tagihan','CabangController@CetakPerformaTagihan');
        Route::get('/download-performa-tagihan','CabangController@DownloadPerformaTagihan');
    });

    Route::group(['prefix'=>'cabang-produk'],function (){
        Route::get('/','CabangProdukController@index');
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

        Route::get('/detail','CustomerController@detail')->name('admin-customer.detail');

        Route::group(['prefix'=>'details'],function (){
            Route::get('/pelanggan','CustomerDetailController@pelanggan')->name('admin-customer.details.pelanggan');
            Route::get('/perusahaan','CustomerDetailController@perusahaan')->name('admin-customer.details.perusahaan');
            Route::get('/info-tagihan','CustomerDetailController@infoTagihan')->name('admin-customer.details.info-tagihan');
            Route::get('/layanan','CustomerDetailController@layanan')->name('admin-customer.details.layanan');
            Route::get('/tagihan','CustomerDetailController@tagihan')->name('admin-customer.details.tagihan');
        });

        Route::group(['prefix'=>'jenis-layanan'],function (){
            Route::get('/','CustomerController@jenisLayanan');
            Route::post('/table','CustomerController@jenisLayanan');
            Route::get('/create','CustomerController@createJenisLayanan');
            Route::post('/create','CustomerController@createJenisLayanan');
            Route::get('/update','CustomerController@updateJenisLayanan');
            Route::post('/update','CustomerController@updateJenisLayanan');
            Route::post('/delete','CustomerController@deleteJenisLayanan');
        });
    });

    Route::group(['prefix'=>'cabang-customer'],function (){
        Route::get('/','CabangCustomerController@index');
    });

    Route::group(['prefix'=>'admin-tagihan'],function (){
        Route::post('/informasi','TagihanController@InformasiTagihan');

        Route::get('/','TagihanController@index');
        Route::post('/table','TagihanController@table');

        Route::get('/generate-invoice','TagihanController@FormGenerate');
        Route::post('/generate-invoice','TagihanController@FormGenerate');
        Route::post('/generate-invoice-next-step','TagihanController@GenInvoiceGetCustomer');

        Route::get('/approve-tagihan','TagihanController@Approval');
        Route::post('/approve-tagihan','TagihanController@Approval');
        Route::get('/cancel-tagihan','TagihanController@Cancel');
        Route::post('/cancel-tagihan','TagihanController@Cancel');

        Route::get('/create','TagihanController@create');
        Route::post('/create','TagihanController@create');

        Route::post('/delete','TagihanController@delete');
        Route::post('/bulk-delete','TagihanController@bulkDelete');

        Route::get('/bulk-approve','TagihanController@BulkApproval');
        Route::get('/bulk-disapprove','TagihanController@BulkDisApproval');
        Route::post('/bulk-approve','TagihanController@BulkApproval');
        Route::post('/bulk-disapprove','TagihanController@BulkDisApproval');
    });
    Route::group(['prefix'=>'cabang-tagihan'],function (){
        Route::get('/','CabangTagihanController@index');
    });

    Route::group(['prefix'=>'admin-account'],function (){
        Route::get('/','UserController@index');
        Route::post('/table','UserController@table');

        Route::get('/create','UserController@create');
        Route::post('/create','UserController@create');

        Route::get('/update','UserController@update');
        Route::post('/update','UserController@update');

        Route::post('/delete','UserController@delete');
    });

    Route::group(['prefix'=>'cabang-account'],function (){
        Route::get('/','CabangUserController@index');
    });

    Route::group(['prefix'=>'barang'],function (){
        Route::get('/','BarangController@index');
        Route::post('/table','BarangController@table');

        Route::get('/create','BarangController@create');
        Route::post('/create','BarangController@create');

        Route::get('/update','BarangController@update');
        Route::post('/update','BarangController@update');

        Route::post('/delete','BarangController@delete');
    });

    Route::group(['prefix'=>'setting'],function (){
        Route::get('/','SettingController@index');
        Route::get('/data-perusahaan','SettingController@dataPerusahaan');
        Route::post('/data-perusahaan','SettingController@dataPerusahaan');

        Route::get('/data-bank','SettingController@dataBank');
        Route::post('/data-bank-tabel','SettingController@dataBankTabel');
        Route::post('/set-status-bank','SettingController@statusBank');
        Route::get('/create-bank','SettingController@createBank');
        Route::post('/create-bank','SettingController@createBank');
        Route::get('/update-bank','SettingController@updateBank');
        Route::post('/update-bank','SettingController@updateBank');
        Route::post('/delete-bank','SettingController@deleteBank');

        Route::get('/template-invoice','SettingController@templateInvoice');
        Route::post('/template-update','SettingController@templateUpdate');

        Route::get('/template-email','SettingController@TemplateEmail');
        Route::post('/template-email','SettingController@TemplateEmail');

        Route::get('/email','SettingController@SettingEmail');
        Route::post('/email','SettingController@SettingEmail');
        Route::post('/email-test','SettingController@EmailTest');

        Route::get('/application-logs','\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('auth');
    });

    Route::group(['prefix'=>'admin-access'],function (){
        Route::get('/','HakAksesController@index');
        Route::post('/table','HakAksesController@table');

        Route::get('/create','HakAksesController@create');
        Route::post('/create','HakAksesController@create');

        Route::get('/update','HakAksesController@update');
        Route::post('/update','HakAksesController@update');

        Route::post('/delete','HakAksesController@delete');
        Route::post('/delete-halaman','HakAksesController@deletePage');

        Route::get('/halaman-dan-fungsi','HakAksesController@Pages');
        Route::post('/halaman-dan-fungsi','HakAksesController@Pages');
    });

    Route::group(['prefix'=>'admin-kas'],function (){
        Route::get('/','KasController@index')->name('admin-kas');
        Route::post('/table','KasController@table')->name('admin-kas.table');
        Route::get('/create','KasController@create')->name('admin-kas.create');
        Route::post('/create','KasController@create')->name('admin-kas.create');
        Route::get('/update','KasController@update')->name('admin-kas.update');
        Route::post('/update','KasController@update')->name('admin-kas.update');
        Route::post('/delete','KasController@delete')->name('admin-kas.delete');
        Route::get('/update-saldo-awal','KasController@UpdateSaldoAwal')->name('admin-kas.update-saldo-awal');
        Route::post('/update-saldo-awal','KasController@UpdateSaldoAwal')->name('admin-kas.update-saldo-awal');

        Route::get('/pengeluaran-rutin','KasController@RecursiveOutput')->name('admin-kas.pengeluaran-rutin');
        Route::post('/pengeluaran-rutin','KasController@RecursiveOutput')->name('admin-kas.pengeluaran-rutin');

        Route::get('/create-pengeluaran-rutin','KasController@RecursiveOutputCreate')->name('admin-kas.create-pengeluaran-rutin');
        Route::post('/create-pengeluaran-rutin','KasController@RecursiveOutputCreate')->name('admin-kas.create-pengeluaran-rutin');

        Route::get('/update-pengeluaran-rutin','KasController@RecursiveOutputUpdate')->name('admin-kas.update-pengeluaran-rutin');
        Route::post('/update-pengeluaran-rutin','KasController@RecursiveOutputUpdate')->name('admin-kas.update-pengeluaran-rutin');
    });
});
