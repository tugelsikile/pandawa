<?php

namespace App\Http\Controllers;

use App\Repositories\{
    UserMenuRepositories, UserPriviledgesRepositories, BarangRepositories, CabangRepositories
};
use App\Validations\BarangValidation;
use Illuminate\Http\Request;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    protected $cabangRepository;
    protected $barangRepository;
    protected $barangValidation;
    protected $menuRepositories;
    protected $priviledges;
    protected $regional;
    public $curMenu = 'barang';

    public function __construct(
        CabangRepositories $cabangRepositories,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        BarangRepositories $barangRepositories,
        BarangValidation $barangValidation
    )
    {
        $this->cabangRepository = $cabangRepositories;
        $this->priviledges = $userPriviledgesRepositories;
        $this->menuRepositories = $userMenuRepositories;
        $this->barangRepository = $barangRepositories;
        $this->barangValidation = $barangValidation;
    }
    public function index(Request $request){
        try{
            $curMenu = $this->curMenu;
            $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);
            $menus = $this->menuRepositories->getMenu(Auth::user()->level);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('barang.index',compact('curMenu','privs','menus'));
    }
    public function table(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()!='POST'){ abort(403);} else {
                $response = ['data'=>[],'draw'=>$request->draw,'recordsFiltered'=>0,'recordsTotal'=>0];
                try{
                    $response['data'] = $this->barangRepository->table($request);
                    $response['recordsFiltered'] = $this->barangRepository->recordsFiltered($request);
                    $response['recordsTotal'] = $this->barangRepository->recordsTotal($request);
                }catch (Exception $exception){
                    $response['data'] = [];
                }
                return $response;
            }
        }
    }
    public function create(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->barangValidation->create($request);
                    $save   = $this->barangRepository->create($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Barang berhasil ditambahkan',$save);
            } else {
                try{
                    $cabangs = $this->cabangRepository->all();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('barang.create',compact('cabangs'));
            }
        }
    }
    public function update(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->barangValidation->update($request);
                    $save   = $this->barangRepository->update($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Barang berhasil dirubah',$save);
            } else {
                try{
                    $data = $this->barangRepository->getByID($request->id);
                    $cabangs = $this->cabangRepository->all();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('barang.update',compact('cabangs','data'));
            }
        }
    }
    public function delete(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()!='POST'){ abort(403); } else {
                try{
                    $valid  = $this->barangValidation->delete($request);
                    $save   = $this->barangRepository->delete($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Barang berhasil dihapus',$save);
            }
        }
    }
}
