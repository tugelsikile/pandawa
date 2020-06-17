<?php

namespace App\Repositories;

use App\Cabang;
use App\Kas;
use App\Tagihan;
use Illuminate\Http\Request;
use Mockery\Exception;
use App\Repositories\KasRecursiveRepository;

class KasRepository{
    protected $kasRecursiveRepository;
    public function __construct(
        KasRecursiveRepository $kasRecursiveRepository
    )
    {
        $this->kasRecursiveRepository = $kasRecursiveRepository;
    }

    public function saldoAwal(Request $request){
        try{
            $curBulan   = (int)date('m');
            $curTahun   = (int)date('Y');
            $saldo_awal = 0;
            if ((int)$request->bulan <= $curBulan && (int)$request->tahun <= $curTahun){
                $bulan  = $request->bulan - 1;
                $tahun  = $request->tahun;
                if ($bulan <= 0){ $bulan = 12; $tahun = $tahun - 1; }
                $bulan  = str_pad($bulan,2,'0',STR_PAD_LEFT);
                $lastSaldo = Kas::where(['kategori'=>'saldo akhir','bulan'=>$bulan,'tahun'=>$tahun])->get();
                if ($lastSaldo->count()===0){
                    $lastSaldo = 0;
                } else {
                    $lastSaldo = $lastSaldo->first()->ammount;
                }
                $saldoAwalBulan = Kas::where(['kategori'=>'saldo awal','bulan'=>$request->bulan,'tahun'=>$request->tahun])->get();
                if ($saldoAwalBulan->count()===0){
                    $saldoAwalBulan   = new Kas();
                    $saldoAwalBulan->bulan    = $request->bulan;
                    $saldoAwalBulan->tahun    = $request->tahun;
                    $saldoAwalBulan->kategori = 'saldo awal';
                    $saldoAwalBulan->ammount  = $lastSaldo;
                    $saldoAwalBulan->informasi= 'Saldo Awal Bulan ' . bulanIndo($request->bulan) . ' '.$request->tahun;
                    $saldoAwalBulan->priority = 1;
                    $saldoAwalBulan->created_by = 'automated';
                } else {
                    $saldoAwalBulan           = $saldoAwalBulan->first();
                    $saldoAwalBulan->bulan    = $request->bulan;
                    $saldoAwalBulan->tahun    = $request->tahun;
                    $saldoAwalBulan->kategori = 'saldo awal';
                    if ($saldoAwalBulan->locked === 0){
                        $saldoAwalBulan->ammount  = $lastSaldo;
                    }
                    $saldoAwalBulan->informasi= 'Saldo Awal Bulan ' . bulanIndo($request->bulan) . ' '.$request->tahun;
                }
                $saldo_awal = $saldoAwalBulan->ammount;
                $saldoAwalBulan->saveOrFail();

                $saldoAkhirBulan = Kas::where(['kategori'=>'saldo akhir','bulan'=>$request->bulan,'tahun'=>$request->tahun])->get();
                if ($saldoAkhirBulan->count()===0){
                    $saldoAkhirBulan    = new Kas();
                    $saldoAkhirBulan->bulan    = $request->bulan;
                    $saldoAkhirBulan->tahun    = $request->tahun;
                    $saldoAkhirBulan->kategori = 'saldo akhir';
                    $saldoAkhirBulan->ammount  = $lastSaldo;
                    $saldoAkhirBulan->informasi= 'Saldo Akhir Bulan ' . bulanIndo($request->bulan) . ' '.$request->tahun;
                    $saldoAkhirBulan->priority = 1000000000;
                    $saldoAkhirBulan->created_by = 'automated';
                } else {
                    $saldoAkhirBulan           = $saldoAkhirBulan->first();
                    $saldoAkhirBulan->bulan    = $request->bulan;
                    $saldoAkhirBulan->tahun    = $request->tahun;
                    $saldoAkhirBulan->kategori = 'saldo akhir';
                    $saldoAkhirBulan->ammount  = $lastSaldo;
                    $saldoAkhirBulan->informasi= 'Saldo Akhir Bulan ' . bulanIndo($request->bulan) . ' '.$request->tahun;
                }
                $saldoAkhirBulan->saveOrFail();

            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request->merge(['saldo_awal'=>$saldo_awal]);
    }
    private function pendapatanDanPiutangCabang(Request $request){
        try{
            $bulan  = $request->bulan;
            $tahun  = $request->tahun;
            if ((int)$bulan <= (int)date('m') && (int)$tahun <= (int)date('Y')){
                //pendapatan dan piutang cabang
                $cabangs    = Cabang::where(['status'=>1])->orderBy('mitra','asc')->orderBy('cab_name','asc')->get();
                foreach ($cabangs as $keyCabang => $cabang){
                    $lunas = Tagihan::join('isp_customer','isp_invoice.cust_id','isp_customer.cust_id','left')
                        ->where(['isp_invoice.cab_id'=>$cabang->cab_id,'isp_invoice.status'=>1,'isp_invoice.is_paid'=>1,'isp_customer.status'=>1])
                        ->whereNotNull('isp_invoice.cust_id')
                        ->whereNotNull('isp_invoice.cab_id')
                        ->whereMonth('isp_invoice.paid_date','=',$bulan)
                        ->WhereYear('isp_invoice.paid_date','=',$tahun)
                        /*->where(function ($q) use ($bulan,$tahun){
                            $q->WhereMonth('isp_invoice.paid_date','=',$bulan)
                                ->orWhereYear('isp_invoice.paid_date','=',$tahun);

                        })*/
                        ->get()->sum('price_with_tax');
                    if ($lunas>0){
                        $kasLunas = Kas::where(['bulan'=>$bulan,'tahun'=>$tahun,'cab_id'=>$cabang->cab_id,'kategori'=>'pemasukan','tags'=>'tagihan lunas'])->get();
                        if ($kasLunas->count()===0){
                            $kasLunas = new Kas();
                            $kasLunas->bulan    = $request->bulan;
                            $kasLunas->tahun    = $request->tahun;
                            $kasLunas->kategori = 'pemasukan';
                            $kasLunas->priority = 10;
                            $kasLunas->tags     = 'tagihan lunas';
                            $kasLunas->cab_id   = $cabang->cab_id;
                            $kasLunas->created_by = 'automated';
                        } else {
                            $kasLunas = $kasLunas->first();
                        }
                        //hitung sharing profit
                        $persen_share       = $cabang->share_percent;
                        $share_profit       = ( $lunas * $persen_share ) / 100;
                        $kasLunas->ammount  = $share_profit;
                        $info = '';
                        $info .= $cabang->mitra == 1 ? 'Mitra ' : 'Cabang ';
                        $info .= $cabang->cab_name;
                        $info .= ' ('.$cabang->share_percent.'% &times; Rp. '.format_rp($lunas).' = Rp. '.format_rp($share_profit).' )';
                        $kasLunas->informasi= $info;
                        $kasLunas->saveOrFail();
                    }
                    $tunggak = Tagihan::join('isp_customer','isp_invoice.cust_id','isp_customer.cust_id','left')
                        ->where(['isp_invoice.cab_id'=>$cabang->cab_id,'isp_invoice.status'=>1,'isp_invoice.is_paid'=>0,'isp_customer.status'=>1])
                        ->whereNotNull('isp_invoice.cust_id')
                        ->whereNotNull('isp_invoice.cab_id')
                        ->whereMonth('isp_invoice.inv_date',$bulan)
                        ->whereYear('isp_invoice.inv_date',$tahun)
                        ->get()->sum('price_with_tax');
                    if ($tunggak>0){
                        $kasTunggak = Kas::where(['bulan'=>$bulan,'tahun'=>$tahun,'cab_id'=>$cabang->cab_id,'kategori'=>'piutang','tags'=>'tagihan tunggak'])->get();
                        if ($kasTunggak->count()===0){
                            $kasTunggak     = new Kas();
                            $kasTunggak->bulan    = $request->bulan;
                            $kasTunggak->tahun    = $request->tahun;
                            $kasTunggak->kategori = 'piutang';
                            $kasTunggak->ammount  = $tunggak;
                            $info = 'Tunggakan ';
                            $info .= $cabang->mitra == 1 ? 'Mitra ' : 'Cabang ';
                            $info .= $cabang->cab_name;
                            $kasTunggak->informasi= $info;
                            $kasTunggak->priority = 30;
                            $kasTunggak->tags     = 'tagihan tunggak';
                            $kasTunggak->cab_id   = $cabang->cab_id;
                            $kasTunggak->created_by = 'automated';
                        } else {
                            $kasTunggak         = $kasTunggak->first();
                            $kasTunggak->ammount  = $tunggak;
                        }
                        $kasTunggak->saveOrFail();
                    }
                }
                //end pendapatan dan piutang cabang
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return true;
    }
    public function tagihanCabang(Request $request){
        try{
            $this->pendapatanDanPiutangCabang($request);
            $this->kasRecursiveRepository->automate($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function tabelKas(Request $request){
        try{
            $keyword    = $request->search['value'];
            $tahun      = $request->tahun;
            $bulan      = $request->bulan;
            $data       = Kas::where(['tahun'=>$tahun,'bulan'=>$bulan])
                ->where(function ($q) use ($keyword){
                    $q->where('kategori','like',"%$keyword%");
                    $q->orWhere('informasi','like',"%$keyword%");
                })
                ->orderBy('priority','asc')->get();
            $lastSaldo  = 0;
            $exclude    = ['piutang','saldo akhir'];
            $pendapatan = $pengeluaran = $saldo_akhir = 0;
            $piutang = $data->where('kategori','=','piutang')->sum('ammount');
            //dd($piutang);
            if ($data->count()>0){
                foreach ($data as $key => $val){
                    if (!in_array($val->kategori,$exclude)){
                        if ($val->kategori === 'pengeluaran'){
                            $pengeluaran    = $pengeluaran - $val->ammount;
                            $lastSaldo      = $lastSaldo - $val->ammount;
                        } else {
                            if ($val->kategori === 'pemasukan') {
                                $pendapatan = $pendapatan + $val->ammount;
                            }
                            $lastSaldo      = $lastSaldo + $val->ammount;
                        }
                    }
                }
            }
            $saldo_akhir = $lastSaldo;
            //update saldo akhir bulan ini
            $saldoAkhirBulan    = Kas::where(['kategori'=>'saldo akhir','tahun'=>$tahun,'bulan'=>$bulan])->get();
            if ($saldoAkhirBulan->count()>0) {
                $saldoAkhirBulan = $saldoAkhirBulan->first();
                $saldoAkhirBulan->ammount  = $lastSaldo;
                $saldoAkhirBulan->saveOrFail();
            }
            //update saldo awal bulan depan
            $bulan = $bulan + 1;
            if ($bulan > 12){
                $bulan = '01'; $tahun = $tahun + 1;
            }
            $nextMonth = Kas::where(['kategori'=>'saldo awal','tahun'=>$tahun,'bulan'=>$bulan])->get();
            if ($nextMonth->count()>0){
                $nextMonth  = $nextMonth->first();
                $nextMonth->ammount = $lastSaldo;
                $nextMonth->saveOrFail();
            }
            //set ammount akhir bulan untuk return ke datatable
            isset($data[$data->count()-1]) ? $data[$data->count()-1]->ammount = $lastSaldo : null;

            $data = $data->sortBy('kategori')->sortBy('priority');
            $data = $data->values();
            $data = ['data'=>$data,'saldo_awal'=>$request->saldo_awal,'saldo_akhir'=>$saldo_akhir,'pendapatan'=>$pendapatan,'pengeluaran'=>$pengeluaran,'piutang'=>$piutang];
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
    public function getBy($input){
        try{
            $data   = Kas::where($input)->get();
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return $data;
    }
    private function priority($kategori){
        switch ($kategori){
            default :
            case 'pemasukan' : $data = 11; break;
            case 'pengeluaran' : $data = 21; break;
            case 'piutang' : $data = 31; break;
        }
        return $data;
    }
    public function create(Request $request){
        try{
            $metaTgl = explode('-',$request->tanggal_kas);
            if (count($metaTgl)!==3) throw new \Matrix\Exception('Tanggal tidak valid',500);
            $data   = new Kas();
            $data->bulan    = $metaTgl[1];
            $data->tahun    = $metaTgl[0];
            $data->kategori = $request->jenis_kas;
            $data->ammount  = $request->jumlah_kas;
            $data->informasi= $request->uraian_kas;
            $data->created_by = auth()->user();
            $data->priority = $this->priority($request->jenis_kas);
            $data->kas_date = $request->tanggal_kas;
            $data->nomor_bukti = $request->nomor_bukti;
            $data->saveOrFail();
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return $request->merge(['data'=>$data]);
    }
    public function update(Request $request){
        try{
            $metaTgl = explode('-',$request->tanggal_kas);
            if (count($metaTgl)!==3) throw new \Matrix\Exception('Tanggal tidak valid',500);
            $data           = Kas::where(['id'=>$request->data_kas])->get();
            if ($data->count()===0) throw new \Matrix\Exception('Data tidak ditemukan',404);
            $data           = $data->first();
            $data->bulan    = $metaTgl[1];
            $data->tahun    = $metaTgl[0];
            $data->kategori = $request->jenis_kas;
            $data->ammount  = $request->jumlah_kas;
            $data->informasi= $request->uraian_kas;
            $data->created_by = auth()->user();
            $data->priority = $this->priority($request->jenis_kas);
            $data->kas_date = $request->tanggal_kas;
            $data->nomor_bukti = $request->nomor_bukti;
            $data->saveOrFail();
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return $request->merge(['data'=>$data]);
    }
    public function delete(Request $request){
        try{
            $data   = Kas::where(['id'=>$request->id])->get();
            if($data->count()===0) throw new \Matrix\Exception('Data tidak ditemukan',404);
            $data   = $data->first()->delete();
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return $request->merge(['data'=>$data]);
    }
    public function UpdateSaldoAwal(Request $request){
        try{
            $data   = Kas::where(['id'=>$request->data_kas])->get();
            if ($data->count()===0) abort(404);
            $data   = $data->first();
            $data->ammount  = $request->saldo_awal;
            $data->updated_by = auth()->user();
            $data->locked   = isset($request->kunci_saldo) ? 1 : 0;
            $data->saveOrFail();
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return $request->merge(['data'=>$data]);
    }
}