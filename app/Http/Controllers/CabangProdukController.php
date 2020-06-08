<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    UserMenuRepositories,
    UserPriviledgesRepositories,
    CabangRepositories,
    ProdukRepositories,
    CustomerRepositories
};
use App\Validations\ProdukValidations;

use Exception;

class CabangProdukController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    protected $cabang;
    protected $produk;
    protected $produkValidation;
    protected $customer;
    public $curMenu = 'cabang-produk';

    public function __construct(
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        CabangRepositories $cabangRepositories,
        ProdukRepositories $produkRepositories,
        ProdukValidations $produkValidations,
        CustomerRepositories $customerRepositories
    )
    {
        $this->priviledges = $userPriviledgesRepositories;
        $this->menuRepositories = $userMenuRepositories;
        $this->cabang = $cabangRepositories;
        $this->produk = $produkRepositories;
        $this->produkValidation = $produkValidations;
        $this->customer = $customerRepositories;
    }
    public function index(){
        $curMenu = $this->curMenu;
        $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);

        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        $cabangs = $this->cabang->all();
        return view('produk.index',compact('curMenu','menus','privs','cabangs'));
    }
    public function table(Request $request){
        $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $data  = $this->produk->table($request);
            $response['data'] = $data;
            $response['recordsFiltered'] = $this->produk->numRows($request);
            $response['recordsTotal'] = $this->produk->numRowsAll($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function kodeProduk(Request $request){
        try{
            $data = $this->produk->KodeProduk($request);
            $data = date('Ymd').str_pad($request->cab_id,4,'0',STR_PAD_LEFT).str_pad($data,4,'0',STR_PAD_LEFT);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$data);
    }
    public function create(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid  = $this->produkValidation->create($request);
                $save   = $this->produk->create($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Produk berhasil dibuat',$save);
        } else {
            $cabangs = $this->cabang->all();
            return view('produk.create',compact('cabangs'));
        }
    }
    public function update(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid = $this->produkValidation->update($request);
                $save = $this->produk->update($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Produk berhasil diupdate',$save);
        } else {
            $data = $this->produk->getByID($request->id);
            $cabangs = $this->cabang->all();
            return view('produk.update',compact('cabangs','data'));
        }
    }
    public function delete(Request $request){
        try{
            $valid = $this->produkValidation->delete($request);
            $delete = $this->produk->delete($valid);
            $setNullCustomer = $this->customer->deletePackageID($delete);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Produk berhasil dihapus',$delete);
    }
    public function bulkDelete(Request $request){
        try{
            $valid  = $this->produkValidation->bulkDelete($request);
            $update = $this->produk->bulkDelete($valid);
            $updateCustomer = $this->customer->deletePackage($update);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Produk berhasil dihapus',$updateCustomer);
    }
    public function getCabangProduk(Request $request){
        try{
            $data = $this->produk->getCabangProduk($request);
        }catch (\Mockery\Exception $exception){
            throw new \Mockery\Exception($exception->getMessage());
        }
        return format(1000,'OK',$data);
    }
}
