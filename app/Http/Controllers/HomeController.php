<?php

namespace App\Http\Controllers;

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
        $this->userMenuRepositories = $userMenuRepositories;
        $this->middleware('auth');
    }

    public function index()
    {
        $curMenu = $this->curMenu;
        $menus = $this->userMenuRepositories->getMenu(Auth::user()->level);
        return view('home',compact('curMenu','menus'));
    }
}
