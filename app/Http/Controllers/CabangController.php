<?php

namespace App\Http\Controllers;

use App\Desa;
use Mockery\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    UserMenuRepositories,
    UserPriviledgesRepositories,
    CabangRepositories,
    RegionalRepositories
};
use App\Validations\CabangValidation;

class CabangController extends Controller
{
    protected $menuRepositories;
    protected $cabangRepositories;
    protected $cabangValidation;
    protected $priviledges;
    protected $regional;
    public $curMenu = 'admin-cabang';

    public function __construct(
        UserMenuRepositories $menuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        CabangRepositories $cabangRepositories,
        CabangValidation $cabangValidation,
        RegionalRepositories $regionalRepositories
    )
    {
        $this->regional = $regionalRepositories;
        $this->cabangRepositories = $cabangRepositories;
        $this->cabangValidation = $cabangValidation;
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
            $response['recordsFiltered'] = $response['recordsTotal'] = $this->cabangRepositories->numRows($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function create(Request $request){
        if ($request->method() == 'POST'){
            try{
                $valid  = $this->cabangValidation->create($request);
                $create = $this->cabangRepositories->create($valid);
            }catch (\Exception $exception){
                throw new \Exception($exception->getMessage());
            }
            return format(1000,'OK',$create);
        } else {
            $prov = $this->regional->getProv($request);
            return view('cabang.create',compact('prov'));
        }
    }
    public function update(Request $request){
        if ($request->method() == 'POST'){
            try{
                $valid  = $this->cabangValidation->update($request);
                $save   = $this->cabangRepositories->update($valid);
            }catch (\Exception $exception){
                throw new \Exception($exception->getMessage());
            }
            return format(1000,'Cabang berhasil diupdate',$save);
        } else {
            $prov = $this->regional->getProv($request);
            try{
                $id = $request->get('id');
                $data = $this->cabangRepositories->getByID($id);
                if (!$data){
                    return format(500,'data not found');
                }
                if (strlen($data->village_id)==0){
                    $village    = new Desa();
                    $village->id = null;
                } else {
                    $village    = $this->regional->getDesaByID($data->village_id);
                }
                $district   = $this->regional->getKecByID($data->district_id);
                $regency    = $this->regional->getKabByID($district->regency_id);
            }catch (\Exception $exception){
                throw new \Exception($exception->getMessage());
            }
            return view('cabang.update',compact('prov','data','village','district','regency'));
        }
    }
    public function delete(Request $request){
        if ($request->method() == 'POST'){
            try{
                $valid  = $this->cabangValidation->delete($request);
                $save   = $this->cabangRepositories->delete($valid);
            }catch (\Exception $exception){
                throw new \Exception($exception->getMessage());
            }
            return format(1000,'Cabang berhasil dihapus',$save);
        }
    }
}
