<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    UserMenuRepositories,
    UserPriviledgesRepositories,
    CabangRepositories
};

class ProdukController extends Controller
{
    protected $menuRepositories;
    protected $priviledges;
    protected $cabang;
    public $curMenu = 'admin-produk';

    public function __construct(
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        CabangRepositories $cabangRepositories
    )
    {
        $this->priviledges = $userPriviledgesRepositories;
        $this->menuRepositories = $userMenuRepositories;
        $this->cabang = $cabangRepositories;
    }
    public function index(){
        $curMenu = $this->curMenu;
        $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);

        $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        $cabangs = $this->cabang->all();
        return view('produk.index',compact('curMenu','menus','privs','cabangs'));
    }
    public function table(){

    }
    public function create(){

    }
    public function update(){

    }
    public function delete(){

    }
}
