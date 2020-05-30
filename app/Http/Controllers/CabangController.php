<?php

namespace App\Http\Controllers;

use Mockery\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserMenuRepositories;
use App\Repositories\{
    UserPriviledgesRepositories,
    CabangRepositories,
    RegionalRepositories
};

class CabangController extends Controller
{
    protected $menuRepositories;
    protected $cabangRepositories;
    protected $priviledges;
    protected $regional;
    public $curMenu = 'admin-cabang';

    public function __construct(
        UserMenuRepositories $menuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        CabangRepositories $cabangRepositories,
        RegionalRepositories $regionalRepositories
    )
    {
        $this->regional = $regionalRepositories;
        $this->cabangRepositories = $cabangRepositories;
        $this->priviledges = $userPriviledgesRepositories;
        $this->menuRepositories = $menuRepositories;
        $this->middleware('auth');
    }

    public function index(){
        $curMenu = $this->curMenu;
        $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);

        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        return view('cabang.index',compact('curMenu','menus','privs'));
    }
    public function table(Request $request){
        $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $data  = $this->cabangRepositories->table($request);
            $response['data'] = $data;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function create(Request $request){
        if ($request->method() == 'POST'){

        } else {
            $prov = $this->regional->getProv($request);
            return view('cabang.create',compact('prov'));
        }
    }
}
