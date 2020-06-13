<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    UserMenuRepositories, UserPriviledgesRepositories, UserLevelRepositories
};
use App\Validations\UserLevelValidation;
use Mockery\Exception;

class HakAksesController extends Controller
{
    protected $UserMenuRepository;
    protected $UserPriviledgesRepository;
    protected $UserLevelRepository;
    protected $UserLevelValidation;
    public $curMenu = 'admin-access';

    public function __construct(
        UserLevelValidation $userLevelValidation,
        UserLevelRepositories $userLevelRepositories,
        UserMenuRepositories $UserMenuRepository,
        UserPriviledgesRepositories $UserPriviledgesRepository
    )
    {
        $this->UserMenuRepository = $UserMenuRepository;
        $this->UserPriviledgesRepository = $UserPriviledgesRepository;
        $this->UserLevelRepository = $userLevelRepositories;
        $this->UserLevelValidation = $userLevelValidation;
    }

    public function index(Request $request){
        try{
            $curMenu    = $this->curMenu;
            $privs      = $this->UserPriviledgesRepository->checkPrivs(Auth::user()->level,$this->curMenu);
            $menus      = $this->UserMenuRepository->getMenu(Auth::user()->level);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('hak-akses.index',compact('curMenu','privs','menus'));
    }
    public function table(Request $request){
        $response = ['draw'=>$request->draw,'data'=>[],'recordsFiltered'=>0,'recordsTotal'=>0];
        try{
            $response['data'] = $this->UserLevelRepository->tableUserLevel($request);
            $response['recordsFiltered'] = $response['recordsTotal'] = $response['data']->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function create(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid  = $this->UserLevelValidation->create($request);
                $save   = $this->UserLevelRepository->create($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Hak akses berhasil dibuat',$save);
        } else {
            try{
                $controllers = \App\Controller::all();
                $controllers->map(function ($controller){
                    $controller->functions   = $controller->functionObj;
                    $controller->makeHidden('functionObj');
                    return $controller;
                });
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('hak-akses.create',compact('controllers'));
        }
    }
    public function update(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid  = $this->UserLevelValidation->update($request);
                $save   = $this->UserLevelRepository->update($valid);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Hak akses berhasil dibuat',$save);
        } else {
            try{
                $data       = $this->UserLevelRepository->getBy(['lvl_id'=>$request->id])->first();
                $priviledges    = $this->UserPriviledgesRepository->getAllBy(['lvl_id'=>$request->id]);
                $controllers = \App\Controller::all();
                $controllers->map(function ($controller){
                    $controller->functions   = $controller->functionObj;
                    $controller->makeHidden('functionObj');
                    return $controller;
                });
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('hak-akses.update',compact('controllers','data','priviledges'));
        }
    }
    public function delete(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        try{
            $valid  = $this->UserLevelValidation->delete($request);
            $save   = $this->UserLevelRepository->delete($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Level Pengguna berhasil diupdate',$save);
    }
}
