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

class ProdukController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    protected $cabang;
    protected $produk;
    protected $produkValidation;
    protected $customer;
    public $curMenu = 'admin-produk';

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
    public function create(){

    }
    public function update(){

    }
    public function delete(){

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
}
