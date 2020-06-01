<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    UserMenuRepositories,
    UserPriviledgesRepositories,
    RegionalRepositories,
    CustomerRepositories,
    CabangRepositories
};
use Exception;

class CustomerController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    protected $regional;
    protected $customer;
    protected $cabang;
    public $curMenu = 'admin-customer';

    public function __construct(
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        RegionalRepositories $regionalRepositories,
        CustomerRepositories $customerRepositories,
        CabangRepositories $cabangRepositories
    )
    {
        $this->menuRepositories = $userMenuRepositories;
        $this->priviledges = $userPriviledgesRepositories;
        $this->regional = $regionalRepositories;
        $this->customer = $customerRepositories;
        $this->cabang = $cabangRepositories;
    }
    public function index(){
        $curMenu = $this->curMenu;
        $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);

        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        $cabangs = $this->cabang->all();
        return view('customer.index',compact('curMenu','menus','privs','cabangs'));
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

    }
    public function update(Request $request){

    }
    public function delete(Request $request){

    }
    public function bulkDelete(Request $request){

    }
}
