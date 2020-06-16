<?php

namespace App\Repositories;

use App\Cabang;
use App\Kas;
use App\Tagihan;
use Illuminate\Http\Request;
use Mockery\Exception;

class KasRepository{
    public function saldoAwal(Request $request){
        try{
            $curBulan   = (int)date('m');
            $curTahun   = (int)date('Y');
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
                    $saldoAwalBulan->created_by = auth()->user();
                } else {
                    $saldoAwalBulan           = $saldoAwalBulan->first();
                    $saldoAwalBulan->bulan    = $request->bulan;
                    $saldoAwalBulan->tahun    = $request->tahun;
                    $saldoAwalBulan->kategori = 'saldo awal';
                    $saldoAwalBulan->ammount  = $lastSaldo;
                    $saldoAwalBulan->informasi= 'Saldo Awal Bulan ' . bulanIndo($request->bulan) . ' '.$request->tahun;
                    $saldoAwalBulan->updated_by = auth()->user();
                }
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
                    $saldoAkhirBulan->created_by = auth()->user();
                } else {
                    $saldoAkhirBulan           = $saldoAkhirBulan->first();
                    $saldoAkhirBulan->bulan    = $request->bulan;
                    $saldoAkhirBulan->tahun    = $request->tahun;
                    $saldoAkhirBulan->kategori = 'saldo akhir';
                    $saldoAkhirBulan->ammount  = $lastSaldo;
                    $saldoAkhirBulan->informasi= 'Saldo Akhir Bulan ' . bulanIndo($request->bulan) . ' '.$request->tahun;
                    $saldoAkhirBulan->updated_by = auth()->user();
                }
                $saldoAkhirBulan->saveOrFail();

            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $request;
    }
    public function tagihanCabang(Request $request){
        try{
            $bulan  = $request->bulan;
            $tahun  = $request->tahun;
            if ((int)$bulan <= (int)date('m') && (int)$tahun <= (int)date('Y')){
                $cabangs    = Cabang::where(['status'=>1])->orderBy('mitra','asc')->orderBy('cab_name','asc')->get();
                foreach ($cabangs as $keyCabang => $cabang){
                    $lunas = Tagihan::select(['isp_invoice.price_with_tax'])
                        ->join('isp_customer','isp_invoice.cust_id','isp_customer.cust_id','left')
                        ->where(['isp_invoice.cab_id'=>$cabang->cab_id,'isp_invoice.status'=>1,'isp_invoice.is_paid'=>1,'isp_customer.status'=>1])
                        ->whereMonth('inv_date',$bulan)
                        ->whereYear('inv_date',$tahun)
                        ->get()->sum('price_with_tax');
                    $kasLunas = Kas::where(['bulan'=>$bulan,'tahun'=>$tahun,'cab_id'=>$cabang->cab_id,'kategori'=>'pemasukan','tags'=>'tagihan lunas'])->get();
                    if ($kasLunas->count()===0){
                        $kasLunas = new Kas();
                        $kasLunas->bulan    = $request->bulan;
                        $kasLunas->tahun    = $request->tahun;
                        $kasLunas->kategori = 'pemasukan';
                        $kasLunas->priority = 10;
                        $kasLunas->tags     = 'tagihan lunas';
                        $kasLunas->cab_id   = $cabang->cab_id;
                        $kasLunas->created_by = auth()->user();
                    } else {
                        $kasLunas = $kasLunas->first();
                        $kasLunas->updated_by = auth()->user();
                    }
                    //hitung sharing profit
                    $persen_share       = $cabang->share_percent;
                    $share_profit       = ( $lunas * $persen_share ) / 100;
                    $kasLunas->ammount  = $share_profit;
                    $info = 'Sharing ';
                    $info .= $cabang->mitra == 1 ? 'Mitra ' : 'Cabang ';
                    $info .= $cabang->cab_name;
                    $info .= ' ('.$cabang->share_percent.'% &times; Rp. '.format_rp($lunas).' = Rp. '.format_rp($share_profit).' )';
                    $kasLunas->informasi= $info;
                    $kasLunas->saveOrFail();

                    $tunggak = Tagihan::select(['isp_invoice.price_with_tax'])
                        ->join('isp_customer','isp_invoice.cust_id','isp_customer.cust_id','left')
                        ->where(['isp_invoice.cab_id'=>$cabang->cab_id,'isp_invoice.status'=>1,'isp_invoice.is_paid'=>0,'isp_customer.status'=>1])
                        ->whereMonth('inv_date',$bulan)
                        ->whereYear('inv_date',$tahun)
                        ->get()->sum('price_with_tax');
                    $kasTunggak = Kas::where(['bulan'=>$bulan,'tahun'=>$tahun,'cab_id'=>$cabang->cab_id,'kategori'=>'piutang','tags'=>'tagihan tunggak'])->get();
                    if ($kasTunggak->count()===0){
                        $kasTunggak     = new Kas();
                        $kasTunggak->bulan    = $request->bulan;
                        $kasTunggak->tahun    = $request->tahun;
                        $kasTunggak->kategori = 'piutang';
                        $kasTunggak->ammount  = $tunggak;
                        $info = 'Tunggakan Tagihan ';
                        $info .= $cabang->mitra == 1 ? 'Mitra ' : 'Cabang ';
                        $info .= $cabang->cab_name;
                        $kasTunggak->informasi= $info;
                        $kasTunggak->priority = 11;
                        $kasTunggak->tags     = 'tagihan tunggak';
                        $kasTunggak->cab_id   = $cabang->cab_id;
                        $kasTunggak->created_by = auth()->user();
                    } else {
                        $kasTunggak         = $kasTunggak->first();
                        $kasTunggak->ammount  = $tunggak;
                        $kasTunggak->updated_by = auth()->user();
                    }
                    $kasTunggak->saveOrFail();
                }
            }
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
            if ($data->count()>0){
                foreach ($data as $key => $val){
                    if (!in_array($val->kategori,$exclude)){
                        $lastSaldo      = $lastSaldo + $val->ammount;
                    } elseif ($val->kategori === 'pengeluaran'){
                        $lastSaldo      = $lastSaldo - $val->ammount;
                    }
                }
            }
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

        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data;
    }
}