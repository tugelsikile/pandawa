<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{
    CabangRepositories, RadiusServerRepository, UserMenuRepositories, UserPriviledgesRepositories
};
use Exception;

class RadiusServerController extends Controller
{
    public $curMenu = 'radius-server';

    public function __construct()
    {
        $this->RadiusServerRepository   = new RadiusServerRepository();
        $this->priviledges              = new UserPriviledgesRepositories();
        $this->menuRepositories         = new UserMenuRepositories();
        $this->cabangRepositories       = new CabangRepositories();
    }
    public function index(Request $request){
        try{
            $users      = $this->RadiusServerRepository->getUsers($request);
            $curMenu    = $this->curMenu;
            $privs      = $this->priviledges->checkPrivs(auth()->user()->level,$this->curMenu);
            $menus      = $this->menuRepositories->getMenu(auth()->user()->level);
            $cabangs    = $this->cabangRepositories->all();
            return view('radius-server.index',compact('cabangs','users','curMenu','privs','menus'));
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }

}
