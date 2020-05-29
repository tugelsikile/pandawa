<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserMenuRepositories;
use App\Repositories\UserPriviledgesRepositories;

class CabangController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    public $curMenu = 'admin-cabang';
    public function __construct(
        UserMenuRepositories $menuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories
    )
    {
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
        $data = [
            'draw' => $request->post('draw'),
            'data' => [],
            'recordsFiltered' => 0,
            'recordsTotal' => 0
        ];
        return $data;
    }
}
