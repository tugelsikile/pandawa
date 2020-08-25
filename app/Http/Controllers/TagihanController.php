<?php

namespace App\Http\Controllers;

use App\Repositories\{
    CustomerRepositories, CabangRepositories, MailRepository, TagihanRepositories, UserMenuRepositories, UserPriviledgesRepositories
};
use App\Tagihan;
use App\Validations\TagihanValidation;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

use Mpdf;
use PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class TagihanController extends Controller
{
    protected $menuRepositories;
    protected $privileges;
    protected $tagihanValidation;
    protected $tagihanRepositories;
    protected $cabangRepositories;
    protected $customerRepositories;
    public $curMenu = 'admin-tagihan';

    public function __construct(
        CustomerRepositories $customerRepositories,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories,
        CabangRepositories $cabangRepositories,
        TagihanRepositories $tagihanRepositories,
        TagihanValidation $tagihanValidation
    )
    {
        $this->menuRepositories = $userMenuRepositories;
        $this->privileges = $userPriviledgesRepositories;
        $this->cabangRepositories = $cabangRepositories;
        $this->tagihanRepositories = $tagihanRepositories;
        $this->tagihanValidation = $tagihanValidation;
        $this->customerRepositories = $customerRepositories;
    }

    public function index(Request $request){
        try{
            $curMenu = $this->curMenu;
            $privs = $this->privileges->checkPrivs(Auth::user()->level,$this->curMenu);
            $cabangs    = $this->cabangRepositories->all();
            $menus = $this->menuRepositories->getMenu(Auth::user()->level);
            $minTahun = $this->tagihanRepositories->minYear($request);
            $jenis = $this->customerRepositories->getAllJenisLayanan();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('tagihan.index',compact('jenis','cabangs','curMenu','privs','menus','minTahun'));
    }
    public function table(Request $request){
        $response = ['draw'=>$request->draw,'data'=>[],'recordsFiltered'=>0,'recordsTotal'=>0];
        try{
            $response['data'] = $this->tagihanRepositories->table($request);
            $response['recordsFiltered'] = $this->tagihanRepositories->recordsFiltered($request);
            $response['recordsTotal'] = $this->tagihanRepositories->recordsTotal($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function FormGenerate(Request $request){
        if (!$request->ajax()){
            abort(403);
        } else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->generate($request);
                    $data   = $this->customerRepositories->getForGenerate($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'OK',$data);
            } else {
                $cabangs    = $this->cabangRepositories->all();
                return view('tagihan.generate-invoice',compact('cabangs'));
            }
        }
    }
    public function GenInvoiceGetCustomer(Request $request){
        try{
            $valid  = $this->tagihanValidation->GenInvoiceGetCustomer($request);
            $save   = $this->tagihanRepositories->GenInvoiceGetCustomer($valid);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$save);
    }
    public function Cancel(Request $request){
        if (!$request->ajax()){ abort(403);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->Cancel($request);
                    $save   = $this->tagihanRepositories->Cancel($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Tagihan berhasil dibatalkan',$save);
            } else {
                try{
                    $data   = $this->tagihanRepositories->getByID($request);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('tagihan.cancelation',compact('data'));
            }
        }
    }
    public function Approval(Request $request){
        if (!$request->ajax()){ abort(405); } else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->Approval($request);
                    $save   = $this->tagihanRepositories->Approval($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Tagihan berhasil disetujui',$save);
            } else {
                try{
                    $data   = $this->tagihanRepositories->getByID($request);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('tagihan.approval',compact('data'));
            }
        }
    }
    public function create(Request $request){
        if (!$request->ajax()){ abort(405);} else {
            if ($request->method()=='POST'){
                try{
                    $valid  = $this->tagihanValidation->create($request);
                    $save   = $this->tagihanRepositories->create($valid);
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return format(1000,'Tagihan manual berhasil dibuat',$save);
            } else {
                try{
                    $cabangs    = $this->cabangRepositories->all();
                }catch (Exception $exception){
                    throw new Exception($exception->getMessage());
                }
                return view('tagihan.create',compact('cabangs'));
            }
        }
    }
    public function CetakLaporan(Request $request){
        //dd($request->bulan_tagihan);
        $data = [];
        try{
            $data   = $this->tagihanRepositories->CetakLaporan($request);
            $companyInfo = companyInfo();
            $judul_laporan = 'laporan ';
            strlen($request->bulan_tagihan)>0 ? $judul_laporan .= ' bulan '.bulanIndo($request->bulan_tagihan) : false;
            strlen($request->tahun_tagihan)>0 ? $judul_laporan .= ' tahun ' . $request->tahun_tagihan : false;
            strlen($request->nama_cabang)>0 ? $judul_laporan .= '<br>cabang '.$this->cabangRepositories->getByID($request->nama_cabang)->first()->cab_name : false;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('tagihan.cetak-laporan',compact('data','request','judul_laporan','companyInfo'));
    }
    public function CetakRekap(Request $request){
        $data = [];
        try{
            $data   = $this->tagihanRepositories->CetakLaporan($request);
            $companyInfo = companyInfo();
            $judul_laporan = 'laporan ';
            strlen($request->bulan_tagihan)>0 ? $judul_laporan .= ' bulan '.bulanIndo($request->bulan_tagihan) : false;
            strlen($request->tahun_tagihan)>0 ? $judul_laporan .= ' tahun ' . $request->tahun_tagihan : false;
            strlen($request->nama_cabang)>0 ? $judul_laporan .= '<br>cabang '.$this->cabangRepositories->getByID($request->nama_cabang)->first()->cab_name : false;
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('tagihan.cetak-rekap',compact('data','request','judul_laporan','companyInfo'));
    }
    public function CetakInvoice(Request $request){
        if ($request->method()!='GET'){ abort(403); } else {
            $data = [];
            try{
                $companyInfo = companyInfo();
                $data   = $this->tagihanRepositories->getByID($request);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('tagihan.cetak-invoice',compact('data','companyInfo'));
        }
    }
    public function InformasiTagihan(Request $request){
        $params = ['total'=>0,'dibayar'=>0,'tunggak'=>0];
        try{
            $date_range = $request->range;
            $min_date = $max_date = '';
            if (strlen($date_range)>0){
                $range = explode(' - ',$date_range);
                $min_date   = Carbon::createFromFormat('d/m/Y',$range[0])->format('Y-m-d');
                $max_date   = Carbon::createFromFormat('d/m/Y',$range[1])->format('Y-m-d');
            }
            $where = ['isp_customer.status'=>1,'isp_invoice.status'=>1];
            $total  = Tagihan::where($where)
                ->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left')
                ->whereNotNull('isp_invoice.cab_id')
                ->where('isp_invoice.cab_id','<>','')
                ->whereNotNull('isp_invoice.cust_id')
                ->where('isp_invoice.cust_id','<>','');
            $dibayar  = Tagihan::where($where)
                ->where(['isp_invoice.is_paid'=>1])
                ->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left')
                ->whereNotNull('isp_invoice.cab_id')
                ->where('isp_invoice.cab_id','<>','')
                ->whereNotNull('isp_invoice.cust_id')
                ->where('isp_invoice.cust_id','<>','');
            $tunggak = Tagihan::where($where)
                ->where(['isp_invoice.is_paid'=>0])
                ->join('isp_customer','isp_invoice.cust_id','=','isp_customer.cust_id','left')
                ->whereNotNull('isp_invoice.cab_id')
                ->where('isp_invoice.cab_id','<>','')
                ->whereNotNull('isp_invoice.cust_id')
                ->where('isp_invoice.cust_id','<>','');
            //cabang
            if (Auth::user()->cab_id){
                $total = $total->where(['isp_invoice.cab_id'=>Auth::user()->cab_id]);
                $dibayar = $dibayar->where(['isp_invoice.cab_id'=>Auth::user()->cab_id]);
                $tunggak = $tunggak->where(['isp_invoice.cab_id'=>Auth::user()->cab_id]);
            } elseif ($request->cab_id){
                $total = $total->where(['isp_invoice.cab_id'=>$request->cab_id]);
                $dibayar = $dibayar->where(['isp_invoice.cab_id'=>$request->cab_id]);
                $tunggak = $tunggak->where(['isp_invoice.cab_id'=>$request->cab_id]);
            }
            if (strlen($min_date)>0 && strlen($max_date)>0){
                $total  = $total->whereBetween('isp_invoice.paid_date',[$min_date,$max_date]);
                $dibayar = $dibayar->whereBetween('isp_invoice.paid_date',[$min_date,$max_date]);
                $tunggak = $tunggak->whereBetween('isp_invoice.paid_date',[$min_date,$max_date]);
            } else {
                //bulan
                if ($request->bulan){
                    $total = $total->whereMonth('inv_date',$request->bulan);
                    $dibayar = $dibayar->whereMonth('inv_date',$request->bulan);
                    $tunggak = $tunggak->whereMonth('inv_date',$request->bulan);
                }
                //tahun
                if ($request->tahun){
                    $total = $total->whereYear('inv_date',$request->tahun);
                    $dibayar = $dibayar->whereYear('inv_date',$request->tahun);
                    $tunggak = $tunggak->whereYear('inv_date',$request->tahun);
                }
            }
            //npwp
            if (strlen($request->npwp)>0){
                $total = $total->where(['isp_customer.npwp'=>$request->npwp]);
                $dibayar = $dibayar->where(['isp_customer.npwp'=>$request->npwp]);
                $tunggak = $tunggak->where(['isp_customer.npwp'=>$request->npwp]);
            }
            //pelanggan aktif
            if (strlen($request->is_active)>0){
                $total = $total->where('isp_customer.is_active','=',$request->is_active);
                $dibayar = $dibayar->where(['isp_customer.is_active'=>$request->is_active]);
                $tunggak = $tunggak->where(['isp_customer.is_active'=>$request->is_active]);
                if ($request->is_active == 0){
                    $total = $total->whereMonth('isp_customer.nonactive_date',$request->bulan)
                        ->whereYear('isp_customer.nonactive_date',$request->tahun);
                    $dibayar = $dibayar->whereMonth('isp_customer.nonactive_date',$request->bulan)
                        ->whereYear('isp_customer.nonactive_date',$request->tahun);
                    $tunggak = $tunggak->whereMonth('isp_customer.nonactive_date',$request->bulan)
                        ->whereYear('isp_customer.nonactive_date',$request->tahun);
                }
            }
            if (strlen($request->mitra)>0){
                $total = $total->join('isp_cabang','isp_invoice.cab_id','=','isp_cabang.cab_id','left')->where('isp_cabang.mitra','=',$request->mitra);
                $dibayar = $dibayar->join('isp_cabang','isp_invoice.cab_id','=','isp_cabang.cab_id','left')->where('isp_cabang.mitra','=',$request->mitra);
                $tunggak = $tunggak->join('isp_cabang','isp_invoice.cab_id','=','isp_cabang.cab_id','left')->where('isp_cabang.mitra','=',$request->mitra);
            }
            if (strlen($request->jenis)>0){
                $total = $total->where('isp_customer.jenis_layanan','=',$request->jenis);
                $dibayar = $dibayar->where('isp_customer.jenis_layanan','=',$request->jenis);
                $tunggak = $tunggak->where('isp_customer.jenis_layanan','=',$request->jenis);
            }

            $total = $total->sum('price_with_tax');
            $dibayar = $dibayar->sum('price_with_tax');
            $tunggak = $tunggak->sum('price_with_tax');
            $params['total'] = format_rp(round($total));
            $params['dibayar'] = format_rp(round($dibayar));
            $params['tunggak'] = format_rp(round($tunggak));
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$params);
    }
    public function BulkApproval(Request $request){
        try{
            if ($request->method()=='POST'){
                $valid  = $this->tagihanValidation->bulkApprove($request);
                if (is_string($valid)) return format(999,$valid,$request);
                $save   = $this->tagihanRepositories->bulkApprove($valid);
                return format(1000,'Tagihan berhasil diapproval',$save);
            } else {
                $ids = $request->id;
                $ids = explode('-',$ids);
                $data   = $this->tagihanRepositories->getByIDs($ids);
                return view('tagihan.bulk-approval',compact('ids','data'));
            }
        }catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function BulkDisApproval(Request $request){
        try{
            if ($request->method()=='POST'){
                $valid  = $this->tagihanValidation->BulkDisApproval($request);
                if (is_string($valid)) return format(999,$valid,$request);
                $save   = $this->tagihanRepositories->BulkDisApproval($valid);
                return format(1000,'Approval Tagihan berhasil dibatalkan',$save);
            } else {
                $ids = $request->id;
                $ids = explode('-',$ids);
                $data   = $this->tagihanRepositories->getByIDs($ids);
                return view('tagihan.bulk-disapproval',compact('ids','data'));
            }
        }catch (\Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function formSendInvoice(Request $request){
        try{
            if ($request->method()=='POST'){
                $ids = $request->id;
                foreach ($ids as $key => $id){
                    if (strlen($id)>0){
                        $this->sendInvoice(new Request(['id'=>$id]));
                    }
                }
                return format(1000,'Invoice sent');
            } else {
                $ids = $request->id;
                $ids = explode('-',$ids);
                $invoices   = $this->tagihanRepositories->getByIDs($ids);
                return view('tagihan.bulk-send-invoice',compact('invoices'));
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
    public function sendInvoice(Request $request){
        try{
            $companyInfo = companyInfo();
            $data   = $this->tagihanRepositories->getByID(new Request(['id'=>$request->id]));

            //CREATE PDF
            $file_name = $data->pac_id.$data->cab_id.$data->cust_id.$data->inv_id.'.pdf';
            $destination = storage_path() . '/app/public/invoices/' .  $file_name;
            $pdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $html = view('tagihan.invoice-pdf',compact('companyInfo','data'));
            $pdf->SetDisplayMode('fullpage');
            $pdf->WriteHTML($html);
            $pdf->Output($destination,'F');

            //CHECK EMAIL
            $email_tagih    = $data->customer->tagih_email;
            $nama_tagih     = $data->customer->fullname;
            $bulan_tagihan  = Carbon::createFromFormat('Y-m-d',$data->inv_date)->format('F Y');
            $email_flags    = 0;
            if (strlen($email_tagih)==0) $email_flags++;
            if (!filter_var($email_tagih,FILTER_VALIDATE_EMAIL)) $email_flags++;

            if ($email_flags === 0){
                $mail_repository= new MailRepository();
                $mail_config    = $mail_repository->getSetting();
                $mail_template  = $mail_repository->getTemplate(2);

                $mailer = new PHPMailer\PHPMailer();
                $mailer->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mailer->SMTPDebug = SMTP::DEBUG_LOWLEVEL;
                $mailer->isSMTP();
                $mailer->Host       = $mail_config->mail_host;
                $mailer->SMTPAuth   = true;
                $mailer->Username   = $mail_config->mail_user;
                $mailer->Password   = $mail_config->mail_pass;
                $mailer->SMTPSecure = PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mailer->Port       = $mail_config->mail_port;

                $mailer->setFrom($mail_template->mail_sender, $mail_template->sender_name);
                $mailer->addAddress($email_tagih,$nama_tagih);
                $mailer->addReplyTo($mail_template->mail_sender,'Informasi Tagihan Bulan '.$bulan_tagihan);

                $mailer->addAttachment($destination, $bulan_tagihan.'.pdf');

                $mailer->isHTML(true);
                $mailer->Subject    = $mail_template->mail_subject;
                $mailer->Body       = $mail_template->mail_body;
                $mailer->send();
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }
}
