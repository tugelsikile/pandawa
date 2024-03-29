<?php

namespace App\Http\Controllers;

require base_path().'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use App\Desa;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{
    CustomerRepositories, UserMenuRepositories, UserPriviledgesRepositories, CabangRepositories, RegionalRepositories
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
        $this->customerRepository = new CustomerRepositories();
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
                $data = $data->first();
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
                $jenis = $this->customerRepository->getAllJenisLayanan();
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('cabang.performa-tagihan',compact('jenis','request','cabangs','curMenu','menus','privs'));
        } else {
            if (!$request->ajax()) abort(403);
            $response = ['data'=>[],'draw'=>$request->draw,'recordsFiltered'=>0,'recordsTotal'=>0,'total_tagihan'=>0];
            try{
                $keyword    = $request->post('search')['value'];
                $orderdir   = $request->post('order')[0]['dir'];
                $cab_id     = $request->post('cab_id');
                $mitra      = $request->mitra;
                $jenis      = $request->jenis;
                $customers  = DB::table('isp_customer')
                    ->join('isp_cabang','isp_customer.cab_id','=','isp_cabang.cab_id','left')
                    ->select(['isp_customer.cust_id','isp_customer.fullname','isp_customer.cab_id','isp_customer.kode'])
                    ->where(['isp_customer.status'=>1,'isp_customer.is_active'=>1])
                    ->where('isp_customer.fullname','like',"%$keyword%")
                    ->orderBy('isp_customer.fullname',$orderdir);
                if (strlen($cab_id)>0) $customers = $customers->where('isp_customer.cab_id','=',$cab_id);
                if (strlen($mitra)>0) $customers = $customers->where('isp_cabang.mitra','=',$mitra);
                if (strlen($jenis)>0) $customers = $customers->where('isp_customer.jenis_layanan','=',$jenis);
                $customers  = $customers->get();
                $customers->map(function ($customer,$index) use ($customers){
                    $tagihan = DB::table('isp_invoice')
                        ->select(['inv_date','price_with_tax'])
                        ->where('cust_id','=',$customer->cust_id)
                        ->where(['is_paid'=>0,'status'=>1])
                        ->where('inv_date','<>',null)
                        ->whereMonth('inv_date','<',date('m'))
                        ->get();
                    $customer->total_tagihan = 0;
                    if ($tagihan->count()>=2){
                        $customer->tagihan = $tagihan;
                        $customer->total_tagihan = $tagihan->sum('price_with_tax');
                        $customer->cabang  = DB::table('isp_cabang')->select('cab_name')->where('cab_id',$customer->cab_id)->get()->first();
                        return $customer;
                    } else {
                        $customers->forget($index);
                    }
                });
                $customers = $customers->values();
                $response['data'] = $customers;
                $response['total_tagihan'] = 'Rp. '.format_rp($customers->sum('total_tagihan'));
                //dd($response);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return $response;
        }
    }
    public function CetakPerformaTagihan(Request $request){
        if ($request->ajax()){ abort(403); } else {
            if ($request->method()!='POST'){ abort(403); } else {
                $total_tagihan = 0;
                try{
                    $cab_id     = $request->post('nama_cabang');
                    $mitra      = $request->mitra;
                    $jenis      = $request->jenis;
                    $customers  = DB::table('isp_customer')
                        ->join('isp_cabang','isp_customer.cab_id','=','isp_cabang.cab_id','left')
                        ->select(['isp_customer.cust_id','isp_customer.fullname','isp_customer.cab_id','isp_customer.kode'])
                        ->where(['isp_customer.status'=>1,'isp_customer.is_active'=>1])
                        ->orderBy('isp_customer.cab_id','asc')->orderBy('isp_customer.fullname','asc');
                    if (strlen($cab_id)>0) $customers = $customers->where('isp_customer.cab_id','=',$cab_id);
                    if (strlen($mitra)>0) $customers = $customers->where('isp_cabang.mitra','=',$mitra);
                    if (strlen($jenis)>0) $customers = $customers->where('isp_customer.jenis_layanan','=',$jenis);
                    $customers  = $customers->get();
                    $customers->map(function ($customer,$index) use ($customers){
                        $tagihan = DB::table('isp_invoice')
                            ->select(['inv_date','price_with_tax'])
                            ->where('cust_id','=',$customer->cust_id)
                            ->where(['is_paid'=>0,'status'=>1])
                            ->where('inv_date','<>',null)
                            ->whereMonth('inv_date','<',date('m'))
                            ->get();
                        $customer->total_tagihan = 0;
                        if ($tagihan->count()>=2){
                            $customer->tagihan = $tagihan;
                            $customer->total_tagihan = $tagihan->sum('price_with_tax');
                            $customer->cabang  = DB::table('isp_cabang')->select('cab_name')->where('cab_id',$customer->cab_id)->get()->first();
                            return $customer;
                        } else {
                            $customers->forget($index);
                        }
                    });
                    $total_tagihan = 'Rp. '.format_rp($customers->values()->sum('total_tagihan'));
                    $customers = $customers->values()->chunk(33);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('cabang.cetak-performa-tagihan',compact('total_tagihan','customers'));
            }
        }
    }
    public function DownloadPerformaTagihan(Request $request){
        if ($request->ajax()){ abort(403); } else {
            if ($request->method()=='POST'){ abort(403); } else {
                try{
                    $cab_id     = $request->id;
                    $mitra      = $request->mitra == 'undefined' ? null : $request->mitra;
                    $jenis      = $request->jenis == 'undefined' ? null : $request->jenis;
                    $customers  = DB::table('isp_customer')
                        ->join('isp_cabang','isp_customer.cab_id','=','isp_cabang.cab_id','left')
                        ->select(['isp_customer.cust_id','isp_customer.fullname','isp_customer.cab_id','isp_customer.kode'])
                        ->where(['isp_customer.status'=>1,'isp_customer.is_active'=>1])
                        ->orderBy('isp_customer.cab_id','asc')->orderBy('isp_customer.fullname','asc');
                    if (strlen($cab_id)>0) $customers = $customers->where('isp_customer.cab_id','=',$cab_id);
                    if (strlen($mitra)>0) $customers = $customers->where('isp_cabang.mitra','=',$mitra);
                    if (strlen($jenis)>0) $customers = $customers->where('isp_customer.jenis_layanan','=',$jenis);
                    $customers  = $customers->get();
                    $customers->map(function ($customer,$index) use ($customers){
                        $jmlTag = DB::table('isp_invoice')
                            ->select(['inv_date','price_with_tax'])
                            ->where('cust_id','=',$customer->cust_id)
                            ->where(['is_paid'=>0,'status'=>1])
                            ->where('inv_date','<>',null)
                            ->whereMonth('inv_date','<',date('m'))
                            ->get();
                        if ($jmlTag->count()>=2){
                            $customer->tagihan = $jmlTag;
                            $customer->cabang  = DB::table('isp_cabang')->select('cab_name')->where('cab_id',$customer->cab_id)->get()->first();
                            return $customer;
                        } else {
                            $customers->forget($index);
                        }
                    });
                    // Mulai PHPSpreadsheet
                    if ($customers->count()==0) {
                        return 'Tidak ada data';
                    } else {
                        $sourceFile     = resource_path('format/performa-tagihan-cabang.xlsx');
                        $fileName       = 'tunggakan-tagihan-cabang';
                        $nama_cabang    = 'Semua Cabang';
                        if (strlen($cab_id)>0) {
                            $nama_cabang    = $this->cabangRepositories->getByID($cab_id)->first()->cab_name;
                            $fileName .= '-'.str_replace(' ','-',$nama_cabang);
                        }

                        $reader         = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet    = $reader->load($sourceFile);
                        $worksheet      = $spreadsheet->setActiveSheetIndex(0);

                        $worksheet->setCellValue('A2',companyInfo()->company_name01)
                            ->setCellValue('C3',': '.$nama_cabang)
                            ->setCellValue('C4',': '.tglIndo(date('Y-m-d')));

                        $row    = 9;
                        foreach ($customers as $keyCustomer => $customer){
                            $worksheet->setCellValue('B'.$row,"'".$customer->kode)
                                ->setCellValue('C'.$row,$customer->fullname)
                                ->setCellValue('D'.$row,!$customer->cabang ? null : $customer->cabang->cab_name);
                            if ($customer->tagihan->count()>0){
                                $column = 5;
                                foreach ($customer->tagihan as $keyTagihan => $tagihan){
                                    $worksheet->setCellValue(toStr($column).$row,bulanIndo(date('m',strtotime($tagihan->inv_date))));
                                    $column++;
                                    $worksheet->setCellValue(toStr($column).$row,(int)$tagihan->price_with_tax);
                                    $column++;
                                }
                            }
                            $row++;
                        }

                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
                        header('Cache-Control: max-age=0');

                        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                        $writer->save('php://output');
                    }
                    //end PHPSpreadsheet
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return false;
            }
        }
    }
    public function getCabang(Request $request){
        try{
            $data   = $this->cabangRepositories->getByID($request->id);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'data loaded',$data);
    }
}
