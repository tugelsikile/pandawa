<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\EmailSetting;
use App\EmailTemplate;
use Illuminate\Http\Request;
use App\Repositories\{
    UserLevelRepositories, UserMenuRepositories, UserPriviledgesRepositories, RegionalRepositories
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Illuminate\Support\Facades\Log;

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
                $logs = Auth::user()->name.' membaca data perusahaan.';
                //Log::channel('customLog')->info($logs,['params'=>sanitize($request)]);
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
    public function dataBank(Request $request){
        if (!$request->ajax()) abort(403);
        return view('setting.data-bank');
    }
    public function dataBankTabel(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $response['data'] = DB::table('isp_bank_account')
                ->where(['status'=>1])
                ->where(function ($q) use ($keyword){
                    $q->where('bank_name','like',"%$keyword%");
                    $q->orWhere('bank_cabang','like',"%$keyword%");
                    $q->orWhere('bank_fullname','like',"%$keyword%");
                    $q->orWhere('bank_rekening','like',"%$keyword%");
                })->orderBy($orderby,$orderdir)->limit($length)->offset($start)->get();
            $response['recordsTotal'] = $response['recordsFiltered'] = $response['data']->count();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' membaca data bank.';
        //Log::channel('customLog')->info($logs,['params'=>sanitize($request)]);
        return $response;
    }
    public function statusBank(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        try{
            $valid = Validator::make($request->all(),[
                'id' => 'required|numeric|exists:isp_bank_account,bank_id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
            DB::table('isp_bank_account')->where('bank_id',$request->id)->update(['status_active'=>$request->data_status]);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' merubah status aktif bank.';
        //Log::channel('customLog')->info($logs,['params'=>sanitize($request)]);
        return format(1000,'Status Account Bank berhasil dirubah');
    }
    public function createBank(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid = Validator::make($request->all(),[
                    'nama_bank' => 'required|string',
                    'cabang_bank' => 'required|string',
                    'nama_pemilik_rekening' => 'required|string',
                    'nomor_rekening' => 'required|string'
                ]);
                if ($valid->fails()){
                    throw new Exception(collect($valid->errors()->all())->join('#'));
                }
                $data = new BankAccount();
                $data->bank_name    = $request->nama_bank;
                $data->bank_cabang  = $request->cabang_bank;
                $data->bank_fullname= $request->nama_pemilik_rekening;
                $data->bank_rekening= $request->nomor_rekening;
                $data->saveOrFail();
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            $logs = Auth::user()->name.' menambahkan data bank.';
            //Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
            return format(1000,'Bank berhasil ditambahkan',$data);
        } else {
            return view('setting.create-bank');
        }
    }
    public function updateBank(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid = Validator::make($request->all(),[
                    'data_bank' => 'required|string|exists:isp_bank_account,bank_id',
                    'nama_bank' => 'required|string',
                    'cabang_bank' => 'required|string',
                    'nama_pemilik_rekening' => 'required|string',
                    'nomor_rekening' => 'required|string'
                ]);
                if ($valid->fails()){
                    throw new Exception(collect($valid->errors()->all())->join('#'));
                }
                $data = BankAccount::where('bank_id',$request->data_bank)->first();
                $data->bank_name    = $request->nama_bank;
                $data->bank_cabang  = $request->cabang_bank;
                $data->bank_fullname= $request->nama_pemilik_rekening;
                $data->bank_rekening= $request->nomor_rekening;
                $data->saveOrFail();
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            $logs = Auth::user()->name.' merubah data bank.';
            //Log::channel('customLog')->notice($logs,['params'=>sanitize($request)]);
            return format(1000,'Data Bank berhasil dirubah',$data);
        } else {
            try{
                $data = BankAccount::where('bank_id',$request->id)->first();
            }catch (Exception $exception){ throw new Exception($exception->getMessage());}
            return view('setting.update-bank',compact('data'));
        }
    }
    public function deleteBank(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        try{
            $valid  = Validator::make($request->all(),[
                'id' => 'required|numeric|exists:isp_bank_account,bank_id'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
            BankAccount::where('bank_id',$request->id)->update(['status'=>0]);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus data bank.';
        //Log::channel('customLog')->warning($logs,['params'=>sanitize($request)]);
        return format(1000,'data bank berhasil dihapus');
    }
    public function templateInvoice(Request $request){
        if (!$request->ajax()) abort(403);
        $data = DB::table('isp_template_id')->get();
        return view('setting.template-invoice',compact('data'));
    }
    public function templateUpdate(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        try{
            $valid = Validator::make($request->all(),[
                'idnya' => 'required|numeric|exists:isp_template_id,idnya',
                'isi_template' => 'required|string|min:4',
                'panjang_nol' => 'required|numeric|min:1'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
            $save = DB::table('isp_template_id')->where('idnya',$request->idnya)->update(['id_string'=>$request->isi_template,'str_pad'=>$request->panjang_nol]);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'Template berhasil dirubah',$save);
    }
    public function TemplateEmail(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid  = Validator::make($request->all(),[
                    'data_template'     => 'required|string|exists:isp_mail_template,tmp_id',
                    'judul_email'       => 'required|string|min:5',
                    'nama_pengirim'     => 'required|string|min:5',
                    'email_pengirim'    => 'required|email',
                    'body_email'        => 'required|string|min:20'
                ]);
                if ($valid->fails()){
                    throw new Exception(collect($valid->errors()->all())->join('#'));
                }
                $save = EmailTemplate::where('tmp_id','=',$request->data_template)->first();
                $save->mail_subject     = $request->judul_email;
                $save->mail_body        = $request->body_email;
                $save->mail_sender      = $request->email_pengirim;
                $save->sender_name      = $request->nama_pengirim;
                dd($save);
                $save->saveOrFail();
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Template email berhasil diupdate',$save);
        } else {
            $data = EmailTemplate::all()->sortBy('tmp_id');
            return view('setting.template-email',compact('data'));
        }
    }
    public function SettingEmail(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid = Validator::make($request->all(),[
                    'data_setting'      => 'required|string|exists:isp_mail_setting,ms_id',
                    'smtp_host_name'    => 'required|string',
                    'smtp_port'         => 'required|numeric|min:1',
                ]);
                if ($valid->fails()){
                    throw new Exception(collect($valid->errors()->all())->join('#'));
                }
                $save = EmailSetting::where('ms_id',$request->data_setting)->update([
                    'mail_host'         => $request->smtp_host_name,
                    'mail_port'         => $request->smtp_port,
                    'mail_user'         => $request->smtp_username,
                    'mail_pass'         => $request->smtp_password
                ]);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return format(1000,'Setting email berhasil disimpan',$save);
        } else {
            try{
                $data = EmailSetting::all()->first();
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('setting.email',compact('data'));
        }
    }
    public function EmailTest(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        try{
            $valid = Validator::make($request->all(),[
                'smtp_host_name'    => 'required|string',
                'smtp_port'         => 'required|numeric|min:1',
                'email_tujuan'      => 'required|email',
                'nama_pengirim'     => 'required|string|min:3',
                'email_pengirim'    => 'required|email',
                'judul_email'       => 'required|string',
                'isi_email'         => 'required|string|min:10'
            ]);
            if ($valid->fails()){
                throw new Exception(collect($valid->errors()->all())->join('#'));
            }
            $mailer = Mail::send('setting.mail-testing',[
                'name' => $request->nama_pengirim,
                'email' => $request->email_pengirim,
                'title' => $request->judul_email,
                'content' => $request->isi_email
            ],function ($message) use ($request){
                $message->to($request->email_tujuan)->subject($request->judul_email);
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$mailer);
    }

}
