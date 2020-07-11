<?php

namespace App\Http\Controllers;

use App\Provinces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    ProdukRepositories, UserMenuRepositories, UserPriviledgesRepositories, RegionalRepositories, CustomerRepositories, CabangRepositories
};
use App\Validations\CustomerValidations;
use Mockery\Exception;

class CustomerController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    protected $regional;
    protected $customerValidation;
    protected $customer;
    protected $cabang;
    protected $produk;
    public $curMenu = 'admin-customer';

    public function __construct(
        ProdukRepositories $produkRepositories,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        RegionalRepositories $regionalRepositories,
        CustomerRepositories $customerRepositories,
        CustomerValidations $customerValidations,
        CabangRepositories $cabangRepositories
    )
    {
        $this->produk = $produkRepositories;
        $this->menuRepositories = $userMenuRepositories;
        $this->priviledges = $userPriviledgesRepositories;
        $this->regional = $regionalRepositories;
        $this->customer = $customerRepositories;
        $this->customerValidation = $customerValidations;
        $this->cabang = $cabangRepositories;
    }
    public function index(){
        $curMenu = $this->curMenu;
        $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);
        $jenisLayanan = $this->customer->getAllJenisLayanan();
        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        if (strlen(Auth::user()->cab_id)>0){
            $cabangs = $this->cabang->getByID(Auth::user()->cab_id);
        } else {
            $cabangs = $this->cabang->all();
        }
        return view('customer.index',compact('jenisLayanan','curMenu','menus','privs','cabangs'));
    }
    public function table(Request $request){
        $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $response['data'] = $this->customer->table($request);
            $response['recordsFiltered'] = $this->customer->recordsFiltered($request);
            $response['recordsTotal'] = $this->customer->recordsTotal($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function create(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid  = $this->customerValidation->create($request);
                $save   = $this->customer->create($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Pelanggan berhasil ditambahkan',$save);
        } else {
            $cabangs = $this->cabang->all();
            $provs = Provinces::all();
            $jenis = $this->customer->getAllJenisLayanan();
            return view('customer.create',compact('jenis','provs','cabangs'));
        }
    }
    public function update(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid  = $this->customerValidation->update($request);
                $save   = $this->customer->update($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Pelanggan berhasil diupdate',$save);
        } else {
            $data   = $this->customer->getByID($request->id);
            $newReq = new Request();
            $newReq->setMethod('POST');
            $newReq->cab_id = $data->cab_id;
            $products = $this->produk->getCabangProduk($newReq);
            $cabangs = $this->cabang->all();
            $provs = Provinces::all();
            $jenis = $this->customer->getAllJenisLayanan();
            return view('customer.update',compact('jenis','data','provs','cabangs','products'));
        }
    }
    public function delete(Request $request){
        try{
            $valid  = $this->customerValidation->delete($request);
            $delete = $this->customer->delete($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Customer berhasil dihapus',$delete);
    }
    public function bulkDelete(Request $request){
        try{
            $valid  = $this->customerValidation->bulkDelete($request);
            $delete = $this->customer->bulkDelete($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Beberapa customer berhasil dihapus',$delete);
    }
    public function setStatus(Request $request){
        try{
            $valid = $this->customerValidation->setStatus($request);
            $set   = $this->customer->setStatus($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Status Customer berhasil diupdate',$set);
    }
    public function detail(Request $request){
        try{
            $curMenu = $this->curMenu;
            $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);
            $menus = $this->menuRepositories->getMenu(Auth::user()->level);
            $data   = $this->customer->getByID($request->id);
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return view('customer.detail',compact('data','curMenu','privs','menus'));
    }
    public function jenisLayanan(Request $request){
        if ($request->method()=='POST'){
            $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
            try{
                $response['data'] = $this->customer->getAllJenisLayanan();
                $response['recordsFiltered'] = count($response['data']);
                $response['recordsTotal'] = count($response['data']);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return $response;
        } else {
            $curMenu = $this->curMenu;
            $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);
            $menus = $this->menuRepositories->getMenu(Auth::user()->level);
            return view('customer.jenis-layanan.index',compact('curMenu','menus','privs'));
        }
    }
    public function createJenisLayanan(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid  = $this->customerValidation->createJenisLayanan($request);
                $save   = $this->customer->createJenisLayanan($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Jenis Layanan berhasil dibuat',$save);
        } else {
            return view('customer.jenis-layanan.create');
        }
    }
    public function updateJenisLayanan(Request $request){
        if ($request->method()=='POST'){
            try{
                $valid  = $this->customerValidation->updateJenisLayanan($request);
                $save = $this->customer->updateJenisLayanan($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Jenis layanan berhasil diupdate',$save);
        } else {
            try{
                $data = $this->customer->getJenisLayanan(['id'=>$request->id]);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('customer.jenis-layanan.update',compact('data'));
        }
    }
    public function deleteJenisLayanan(Request $request){
        try{
            $valid  = $this->customerValidation->deleteJenisLayanan($request);
            $save   = $this->customer->deleteJenisLayanan($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Jenis layanan berhasil dihapus',$save);
    }
}
