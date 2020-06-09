<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{
    UserLevelRepositories, UserMenuRepositories, UserPriviledgesRepositories, RegionalRepositories
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
                try{
                    $valid = Validator::make($request->all(),[
                        'nama_perusahaan' => 'required|string|min:5',
                        'alamat_perusahaan' => 'required|string|min:5',
                        'nama_desa' => 'required|numeric|exists:isp_region_villages,id',
                        'nama_kecamatan' => 'required|numeric|exists:isp_region_districts,id',
                        'nama_kabupaten' => 'required|numeric|exists:isp_region_regencies,id',
                        'nama_provinsi' => 'required|numeric|exists:isp_region_provinces,id',
                        'kode_pos' => 'required|numeric',
                        'alamat_email' => 'required|email',
                        'nomor_telepon' => 'required|string|min:10',
                    ]);
                    if ($valid->fails()){
                        throw new Exception(collect($valid->errors()->all())->join('#'));
                    }
                    $data   = DB::table('isp_site')->update([
                        'company_name01' => $request->nama_perusahaan,
                        'address_01' => $request->alamat_perusahaan,
                        'district_id' => $request->nama_kecamatan,
                        'email' => $request->alamat_email,
                        'phone' => $request->nomor_telepon,
                        'postal_code' => $request->kode_pos,
                        'village_id' => $request->nama_desa,
                        'regency_id' => $request->nama_kabupaten,
                        'province_id' => $request->nama_provinsi
                    ]);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Data berhasil dirubah',$data);
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
