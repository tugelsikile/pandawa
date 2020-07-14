<?php

namespace App\Http\Controllers;

use App\Repositories\CabangRepositories;
use App\Repositories\CustomerRepositories;
use App\Repositories\TagihanRepositories;
use Illuminate\Http\Request;
use App\Repositories\UserMenuRepositories;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $userMenuRepositories;
    public $curMenu = 'dashboard';
    public function __construct(
        UserMenuRepositories $userMenuRepositories
    )
    {
        $this->cabangRepository = new CabangRepositories();
        $this->tagihanRepository = new TagihanRepositories();
        $this->customerRepository = new CustomerRepositories();
        $this->userMenuRepositories = $userMenuRepositories;
    }

    public function index()
    {
        $curMenu = $this->curMenu;
        $menus = $this->userMenuRepositories->getMenu(Auth::user()->level);
        $cabangs = auth()->user()->cab_id ? $this->cabangRepository->getAllByID(auth()->user()->cab_id) : $this->cabangRepository->all();
        return view('home',compact('curMenu','menus','cabangs'));
    }
    public function grafikTagihan(Request $request){
        try{
            $data = $this->tagihanRepository->grafikTagihan($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('tagihan.grafik-tagihan',compact('data'));
    }
    public function grafikCustomer(Request $request){
        try{
            $data = $this->customerRepository->grafikCustomer($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('customer.grafik-customer',compact('data'));
    }
}
