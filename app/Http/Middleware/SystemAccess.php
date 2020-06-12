<?php

namespace App\Http\Middleware;

use App\UserPriviledges;
use Closure;

class SystemAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()){
            abort(403);
        } else {
            $currentRoute = str_replace('-','_',$request->path());
            $currentRoute = explode('/',$currentRoute);
            $currentRoute = $currentRoute[0];
            if ($currentRoute != 'home'){
                $lvl_id = auth()->user()->level;
                $privileges = UserPriviledges::where('lvl_id','=',$lvl_id)
                    ->join('isp_controllers','isp_user_priviledges.ctrl_id','=','isp_controllers.ctrl_id','left')
                    ->where('isp_controllers.ctrl_url','=',$currentRoute)->get();
                if(!$privileges->count()){
                    abort(403);
                } else {
                    if ($privileges->first()->R_opt === 0) abort(403);
                }
            }
        }
        return $next($request);
    }
}
