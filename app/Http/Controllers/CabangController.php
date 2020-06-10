<?php

namespace App\Http\Controllers;

use App\Desa;
use Illuminate\Support\Facades\DB;
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
    public function PerformaTagihan(Request $request){
        if($request->method()!='POST'){
            try{
                $curMenu = $this->curMenu;
                $privs   = $this->priviledges->checkPrivs(Auth::user()->level,$this->curMenu);

                $menus = $this->menuRepositories->getMenu(Auth::user()->level);
                if (Auth::user()->cab_id){
                    $cabangs = $this->cabangRepositories->getByID(Auth::user()->cab_id);
                } else {
                    $cabangs = $this->cabangRepositories->all();
                }
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('cabang.performa-tagihan',compact('cabangs','curMenu','menus','privs'));
        } else {
            if (!$request->ajax()) abort(403);
            $response = ['data'=>[],'draw'=>$request->draw,'recordsFiltered'=>0,'recordsTotal'=>0];
            try{
                $invoices = DB::table('isp_invoice')
                    ->select(['is_paid','inv_id','inv_number','inv_date','cust_id','cab_id'])
                    ->where('is_paid','<',1)
                    ->where('status','>',0);
                if (strlen($request->cab_id)>0) $invoices = $invoices->where('cab_id','=',$request->cab_id);
                $invoices = $invoices->get();
                $data = [];
                if ($invoices->count()>0){
                    $n = 0;
                    $cari = [];
                    foreach ($invoices as $invoice){
                        if ($invoice->is_paid < 1){
                            if (!isset($cari[$invoice->cust_id])) {
                                $cari[$invoice->cust_id] = 0;
                            } else {
                                $cari[$invoice->cust_id]++;
                            }
                            if ($cari[$invoice->cust_id]>1){
                                $data[$n] = $invoice;
                                $n++;
                            }
                        }
                    }
                }
                $data = collect($data);
                $data->map(function ($data){
                    $data->customer = DB::table('isp_customer')->select(['fullname','cust_id','kode'])->where('cust_id','=',$data->cust_id)->get()->first();
                    $data->cabang   = $this->cabangRepositories->getByID($data->cab_id)->cab_name;
                    return $data;
                });
                $response['data'] = $data;
                $response['recordsFiltered'] = $data->count();
                $response['recordsTotal'] = $data->count();
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return $response;
        }
    }
}
