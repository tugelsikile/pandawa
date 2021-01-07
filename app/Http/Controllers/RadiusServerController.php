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
            $curMenu    = $this->curMenu;
            $privs      = $this->priviledges->checkPrivs(auth()->user()->level,$this->curMenu);
            $menus      = $this->menuRepositories->getMenu(auth()->user()->level);
            $cabangs    = $this->cabangRepositories->all();
            return view('radius-server.index',compact('cabangs','curMenu','privs','menus'));
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function table(Request $request){
        try{
            $data   = $this->RadiusServerRepository->getUsersTable($request);
            if (count($data->data)===0){
                return ['data'=>[],'recordsFiltered'=>0,'recordsTotal'=>0];
            }
            return [
                'data' => $data->data,
                'draw' => $request->draw,
                'recordsFiltered' => $data->recordsFiltered,
                'recordsTotal' => $data->recordsTotal
            ];
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function createUser(Request $request){
        try{
            if ($request->method()=='POST'){
                $save = $this->RadiusServerRepository->createUser($request);
                return format($save->code,$save->msg,$save->params);
            } else {
                $cabangs        = $this->cabangRepositories->all();
                $user_levels    = $this->RadiusServerRepository->getAllUserLevel();
                return view('radius-server.create',compact('cabangs','user_levels'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function updateUser(Request $request){
        try{
            if ($request->method()=='POST'){
                $save = $this->RadiusServerRepository->updateUser($request);
                return format($save->code,$save->msg,$save->params);
            } else {
                $data = collect($this->RadiusServerRepository->getUserByID($request));
                if ($data->count()===0){
                    die('Tidak ada data');
                } else {
                    $data = $data->first();
                    $cabangs    = $this->cabangRepositories->all();
                    $user_levels    = $this->RadiusServerRepository->getAllUserLevel();
                    return view('radius-server.update',compact('cabangs','user_levels','data'));
                }
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function deleteUser(Request $request){
        try{
            $save = $this->RadiusServerRepository->deleteUser($request);
            return format($save->code,$save->msg,$save->params);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }

}
