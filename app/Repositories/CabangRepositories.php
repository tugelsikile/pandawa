<?php

namespace App\Repositories;

use App\{
    Cabang, Customer, Desa, Invoice, Kabupaten, Kecamatan, Provinces
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Illuminate\Support\Facades\Log;

class CabangRepositories{
    public function all(){
        try{
            if (strlen(Auth::user()->cab_id)>0){
                $data = Cabang::where(['status'=>1,'cab_id'=>Auth::user()->cab_id])->get();
            } else {
                $data = Cabang::where(['status'=>1])->get();
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function table(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $start      = $request->post('start');
            $length     = $request->post('length');
            $orderby    = $request->post('order')[0]['column'];
            $orderby    = $request->post('columns')[$orderby]['data'];
            $orderdir   = $request->post('order')[0]['dir'];
            $data = Cabang::where('status','=',1)
                ->where('cab_name','like',"%$keyword%")
                ->orderBy($orderby,$orderdir)
                ->limit($length)
                ->offset($start)
                ->get();
            if (!is_null($data)){
                $data->map(function ($data){
                    $cur_date = date('Y-m-').'01';
                    $data->customer = Customer::where(['status'=>1,'cab_id'=>$data->cab_id])->get();
                    $data->invoice  = format_rp(Invoice::where(['status'=>1,'cab_id'=>$data->cab_id,'inv_date'=>$cur_date])->sum('price_with_tax'));
                    return $data;
                });
            }
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' membaca data cabang. parameter : '.json_encode($request->all());
        Log::channel('customLog')->info($logs);
        return $data;
    }
    public function numRows(Request $request){
        try{
            $keyword    = $request->post('search')['value'];
            $data = Cabang::where('status','=',1)
                ->where('cab_name','like',"%$keyword%")
                ->get();
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $data->count();
    }
    public function create(Request $request){
        $data = new Cabang();
        try{
            $data->cab_name         = $request->cab_name;
            $data->id_template      = $request->template;
            $data->id_template_pad  = $request->pad;
            $data->address01        = ucwords(strtolower(Desa::where('id',$request->village_id)->get()->first()->name));
            $data->address02        = $request->alamat;
            $data->district_id      = $request->district_id;
            $data->village_id       = $request->village_id;
            $data->phone            = $request->telp;
            $data->email            = $request->email;
            $data->owner            = $request->nama_pemilik;
            $data->owner_phone      = $request->telp_pemilik;
            $data->postal           = $request->kode_pos;
            $data->mitra            = $request->mitra;
            $data->share_percent    = $request->share_percent;
            $data->save();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menambahkan data cabang '.$data->cab_name.'. parameter : '.json_encode($request->all());
        Log::channel('customLog')->notice($logs);
        return $data;
    }
    public function update(Request $request){
        try{
            $data   = Cabang::where('cab_id',$request->cab_id)->get()->first();
            $data->cab_name         = $request->cab_name;
            $data->id_template      = $request->template;
            $data->id_template_pad  = $request->pad;
            $data->address01        = ucwords(strtolower(Desa::where('id',$request->village_id)->get()->first()->name));
            $data->address02        = $request->alamat;
            $data->district_id      = $request->district_id;
            $data->village_id       = $request->village_id;
            $data->phone            = $request->telp;
            $data->email            = $request->email;
            $data->owner            = $request->nama_pemilik;
            $data->owner_phone      = $request->telp_pemilik;
            $data->postal           = $request->kode_pos;
            $data->mitra            = $request->mitra;
            $data->share_percent    = $request->share_percent;
            $data->save();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' merubah data cabang '.$data->cab_name.'. parameter : '.json_encode($request->all());
        Log::channel('customLog')->notice($logs);
        return $data;
    }
    public function delete(Request $request){
        try{
            $data   = Cabang::where('cab_id',$request->id)->get()->first();
            $data->status = 0;
            $data->save();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        $logs = Auth::user()->name.' menghapus data cabang '.$data->cab_name.'. parameter : '.json_encode($request->all());
        Log::channel('customLog')->warning($logs);
        return $data;
    }
    public function getByID($id){
        try{
            $data   = Cabang::find($id);
            $data->districts = Kecamatan::where('id',$data->district_id)->get()->first();
            $data->regencies = Kabupaten::where('id',$data->districts->regency_id)->get()->first();
            $data->provinces = Provinces::where('id',$data->regencies->province_id)->get()->first();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
    public function getAllByID($id){
        try{
            $data   = Cabang::where('cab_id','=',$id)->get();
            $data->map(function ($data){
                $data->districts = Kecamatan::where('id',$data->district_id)->get()->first();
                $data->regencies = Kabupaten::where('id',$data->districts->regency_id)->get()->first();
                $data->provinces = Provinces::where('id',$data->regencies->province_id)->get()->first();
            });
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        return $data;
    }
}