<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepositories;
use App\Repositories\TagihanRepositories;
use Illuminate\Http\Request;
use Matrix\Exception;

class CustomerDetailController extends Controller
{
    protected $customerRepository;
    protected $tagihanRepository;
    public function __construct(
        TagihanRepositories $tagihanRepositories,
        CustomerRepositories $customerRepositories
    )
    {
        $this->tagihanRepository = $tagihanRepositories;
        $this->customerRepository = $customerRepositories;
    }

    public function pelanggan(Request $request){
        if (!$request->ajax()) redirect(url('admin-customer'));
        try{
            $data   = $this->customerRepository->getByID($request->id);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('customer.details.pelanggan',compact('data'));
    }
    public function perusahaan(Request $request){
        if (!$request->ajax()) redirect(url('admin-customer'));
        try{
            $data   = $this->customerRepository->getByID($request->id);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('customer.details.perusahaan',compact('data'));
    }
    public function infoTagihan(Request $request){
        if (!$request->ajax()) redirect(url('admin-customer'));
        try{
            $data   = $this->customerRepository->getByID($request->id);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('customer.details.info-tagihan',compact('data'));
    }
    public function layanan(Request $request){
        if (!$request->ajax()) redirect(url('admin-customer'));
        try{
            $data   = $this->customerRepository->getByID($request->id);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('customer.details.layanan',compact('data'));
    }
    public function tagihan(Request $request){
        if (!$request->ajax()) redirect(url('admin-customer'));
        try{
            $data       = $this->customerRepository->getByID($request->id);
            $tagihan    = $this->tagihanRepository->tagihanByCustomer(['cust_id'=>$request->id]);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return view('customer.details.tagihan',compact('data','tagihan'));
    }
}
