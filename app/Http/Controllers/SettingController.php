<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{
    UserLevelRepositories, UserMenuRepositories, UserPriviledgesRepositories, RegionalRepositories
};
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class SettingController extends Controller
{
    protected $regionalRepository;
    protected $userLevelRepository;
    protected $userMenuRepository;
    protected $userPriviledgesRepository;
    public $curMenu = 'setting';

    public function __construct(
        RegionalRepositories $regionalRepositories,
        UserLevelRepositories $userLevelRepositories,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories
    )
    {
        $this->regionalRepository = $regionalRepositories;
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
    public function dataPerusahaan(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){

            } else {
                try{
                    $provinces = $this->regionalRepository->getProv($request);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('setting.data-perusahaan',compact('provinces'));
            }
        }
    }
}
