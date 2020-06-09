<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{
    UserLevelRepositories, UserMenuRepositories, UserPriviledgesRepositories
};
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class SettingController extends Controller
{
    protected $userLevelRepository;
    protected $userMenuRepository;
    protected $userPriviledgesRepository;
    public $curMenu = 'setting';

    public function __construct(
        UserLevelRepositories $userLevelRepositories,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories
    )
    {
        $this->userLevelRepository = $userLevelRepositories;
        $this->userMenuRepository = $userMenuRepositories;
        $this->userPriviledgesRepository = $userPriviledgesRepositories;
    }

    public function index(Request $request){
        try{
            $curMenu = $this->curMenu;
            $privs   = $this->userPriviledgesRepository->checkPrivs(Auth::user()->level,$this->curMenu);
            $menus = $this->userMenuRepository->getMenu(Auth::user()->level);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('setting.index',compact('curMenu','privs','menus'));
    }
}
