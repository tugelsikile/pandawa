<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepositories;
use Illuminate\Http\Request;
use Mockery\Exception;

class ListController extends Controller
{
    protected $customerRepository;

    public function __construct(
        CustomerRepositories $customerRepositories
    )
    {
        $this->customerRepository = $customerRepositories;
    }

    public function CustomersCabang(Request $request){
        $data = [];
        try{
            $data   = $this->customerRepository->CustomersCabang($request);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
        return format(1000,'OK',$data);
    }
}
