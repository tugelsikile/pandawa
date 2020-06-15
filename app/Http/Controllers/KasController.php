<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\Exception;
use App\Repositories\{
    KasRecursiveRepository, TagihanRepositories, UserMenuRepositories, UserPriviledgesRepositories, KasRepository
};

class KasController extends Controller
{
    protected $userMenuRepository;
    protected $userPrivilegeRepository;
    protected $kasRepository;
    protected $kasRecursiveRepository;
    protected $tagihanRepository;
    public $curMenu = 'admin-kas';

    public function __construct(
        TagihanRepositories $tagihanRepositories,
        KasRepository $kasRepository,
        KasRecursiveRepository $kasRecursiveRepository,
        UserMenuRepositories $userMenuRepositories,
        UserPriviledgesRepositories $userPriviledgesRepositories
    )
    {
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
        $response = [ 'draw' => $request->post('draw'), 'data' => [], 'recordsFiltered' => 0, 'recordsTotal' => 0 ];
        try{
            $checkSaldoAwal = $this->kasRepository->saldoAwal($request);
            $checkTagihan   = $this->kasRepository->tagihanCabang($checkSaldoAwal);
            $response['data'] = $this->kasRepository->tabelKas($checkTagihan);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return $response;
    }
}
