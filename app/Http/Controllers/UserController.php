<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{
    CabangRepositories, UserLevelRepositories, UserMenuRepositories, UserPriviledgesRepositories, UserRepositories
};
use App\Validations\UserValidations;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class UserController extends Controller
{
    public $curMenu = 'admin-account';
    protected $menuRepositories;
    protected $priviledges;
    protected $userRepositories;
    protected $userValidations;
    protected $cabangRepositories;
    protected $userLevelRepositories;

    public function __construct(
        UserValidations $userValidations,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        UserRepositories $userRepositories,
        CabangRepositories $cabangRepositories,
        UserLevelRepositories $userLevelRepositories
    )
    {
        $this->userLevelRepositories = $userLevelRepositories;
        $this->userRepositories = $userRepositories;
        $this->userValidations = $userValidations;
        $this->menuRepositories = $userMenuRepositories;
        $this->priviledges = $userPriviledgesRepositories;
        $this->cabangRepositories = $cabangRepositories;
    }
    public function index(Request $request){
        try{
            $curMenu = $this->curMenu;
            $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);
            $menus = $this->menuRepositories->getMenu(Auth::user()->level);
            $cabangs = $this->cabangRepositories->all();
            $levels = $this->userLevelRepositories->getAll();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('users.index',compact('curMenu','privs','menus','cabangs','levels'));
    }
    public function table(Request $request){
        $response = ['data'=>[],'draw'=>$request->draw,'recordsFiltered'=>0,'recordsTotal'=>0];
        try{
            $response['data'] = $this->userRepositories->table($request);
            $response['recordsFiltered'] = $this->userRepositories->recordsFiltered($request);
            $response['recordsTotal'] = $this->userRepositories->recordsTotal($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function create(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->userValidations->create($request);
                    $save   = $this->userRepositories->create($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Pengguna berhasil dibuat',$save);
            } else {
                try{
                    $cabangs = $this->cabangRepositories->all();
                    $levels = $this->userLevelRepositories->getAll();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('users.create',compact('cabangs','levels'));
            }
        }
    }
    public function update(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){
                try{
                    $valid = $this->userValidations->update($request);
                    $save   = $this->userRepositories->update($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Pengguna berhasil diupdate',$save);
            } else {
                try{
                    $cabangs = $this->cabangRepositories->all();
                    $levels = $this->userLevelRepositories->getAll();
                    $data =$this->userRepositories->getByID($request->id);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('users.update',compact('cabangs','levels','data'));
            }
        }
    }
    public function delete(Request $request){
        try{
            $valid = $this->userValidations->delete($request);
            $save = $this->userRepositories->delete($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Pengguna berhasil dihapus',$save);
    }
}
