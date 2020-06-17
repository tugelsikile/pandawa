<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;
use App\Repositories\{
    KasRecursiveRepository, TagihanRepositories, UserMenuRepositories, UserPriviledgesRepositories, KasRepository
};
use App\Validations\KasValidation;

class KasController extends Controller
{
    protected $userMenuRepository;
    protected $userPrivilegeRepository;
    protected $kasRepository;
    protected $kasValidation;
    protected $kasRecursiveRepository;
    protected $tagihanRepository;
    public $curMenu = 'admin-kas';

    public function __construct(
        KasValidation $kasValidation,
        TagihanRepositories $tagihanRepositories,
        KasRepository $kasRepository,
        KasRecursiveRepository $kasRecursiveRepository,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories
    )
    {
        $this->kasValidation = $kasValidation;
        $this->tagihanRepository = $tagihanRepositories;
        $this->userMenuRepository = $userMenuRepositories;
        $this->userPrivilegeRepository = $userPriviledgesRepositories;
        $this->kasRepository = $kasRepository;
        $this->kasRecursiveRepository = $kasRecursiveRepository;
    }

    public function index(Request $request){
        try{
            $curMenu = $this->curMenu;
            $privs  = $this->userPrivilegeRepository->checkPrivs(auth()->user()->level,$this->curMenu);
            $menus  = $this->userMenuRepository->getMenu(auth()->user()->level);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('kas.index',compact('curMenu','privs','menus'));
    }
    public function table(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()!='POST') abort(403);
        $response = [ 'saldo_awal' => 0, 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $checkSaldoAwal = $this->kasRepository->saldoAwal($request);
            $checkTagihan   = $this->kasRepository->tagihanCabang($checkSaldoAwal);
            $data           = $this->kasRepository->tabelKas($checkTagihan);
            $response['data'] = $data['data'];
            $response['saldo_awal'] = format_rp($data['saldo_awal']);
            $response['saldo_akhir'] = format_rp($data['saldo_akhir']);
            $response['pendapatan'] = format_rp($data['pendapatan']);
            $response['pengeluaran'] = format_rp($data['pengeluaran']);
            $response['piutang'] = format_rp($data['piutang']);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
    public function RecursiveOutput(Request $request){
        if ($request->method()=='POST'){
            if (!$request->ajax()) abort(403);
            $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
            try{
                $response['data'] = $this->kasRecursiveRepository->table($request);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return $response;
        } else {
            try{
                $curMenu = $this->curMenu;
                $privs  = $this->userPrivilegeRepository->checkPrivs(auth()->user()->level,$this->curMenu);
                $menus  = $this->userMenuRepository->getMenu(auth()->user()->level);
            }catch (Exception $exception){
                throw new Exception($exception->getMessage());
            }
            return view('kas.recursive-index',compact('curMenu','privs','menus'));
        }
    }
    public function RecursiveOutputCreate(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid = $this->kasValidation->createRecursive($request);
                $save   = $this->kasRecursiveRepository->createRecursive($valid);
            }catch (Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return format(1000,'Pengeluaran rutin berhasil ditambahkan',$save);
        } else {
            return view('kas.create-recursive');
        }
    }
    public function RecursiveOutputUpdate(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid = $this->kasValidation->updateRecursive($request);
                $save   = $this->kasRecursiveRepository->updateRecursive($valid);
            }catch (Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return format(1000,'Pengeluaran rutin berhasil ditambahkan',$save);
        } else {
            try{
                $data   = $this->kasRecursiveRepository->getBy(['id'=>$request->id]);
                if ($data->count()===0) die('Data tidak ditemukan');
                $data   = $data->first();
            }catch (\Matrix\Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return view('kas.update-recursive',compact('data'));
        }
    }
    public function create(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid  = $this->kasValidation->create($request);
                $save   = $this->kasRepository->create($valid);
            }catch (\Matrix\Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return format(1000,'Kas '.$request->jenis_kas.' dibuat',$save);
        } else {
            return view('kas.create');
        }
    }
    public function update(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid  = $this->kasValidation->update($request);
                $save   = $this->kasRepository->update($valid);
            }catch (\Matrix\Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return format(1000,'Data Kas '.$request->jenis_kas.' diupdate',$save);
        } else {
            try{
                $data = $this->kasRepository->getBy(['id'=>$request->id]);
                if ($data->count()===0) abort(404);
                $data = $data->first();
            }catch (\Matrix\Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return view('kas.update',compact('data'));
        }
    }
    public function delete(Request $request){
        if (!$request->ajax() || $request->method()!='POST') abort(403);
        try{
            $valid  = $this->kasValidation->delete($request);
            $save   = $this->kasRepository->delete($valid);
        }catch (\Matrix\Exception $exception){
            throw new \Matrix\Exception($exception->getMessage());
        }
        return format(1000,'Data Kas berhasil dihapus',$save);
    }
    public function UpdateSaldoAwal(Request $request){
        if (!$request->ajax()) abort(403);
        if ($request->method()=='POST'){
            try{
                $valid  = $this->kasValidation->UpdateSaldoAwal($request);
                $save   = $this->kasRepository->UpdateSaldoAwal($valid);
            }catch (\Matrix\Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return format(1000,'Saldo awal berhasil diupdate',$save);
        } else {
            $data = [];
            try{
                $data   = $this->kasRepository->getBy(['id'=>$request->id]);
                if ($data->count()===0) abort(404);
                $data   = $data->first();
            }catch (\Matrix\Exception $exception){
                throw new \Matrix\Exception($exception->getMessage());
            }
            return view('kas.update-saldo-awal',compact('data'));
        }
    }
}
