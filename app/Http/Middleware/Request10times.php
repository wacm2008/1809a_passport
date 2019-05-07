<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class Request10times
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
        //print_r($_SERVER);die;
        $key='10times：'.'IP：'.$_SERVER['REMOTE_ADDR']."/".$request->input('token');
        echo $key.'<br>';
        $re=Redis::get($key);
        if($re>10){
            $response=[
                'errorno'=>50001,
                'msg'=>'超过10次限制',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        Redis::incr($key);
        Redis::expire($key,30);
        return $next($request);
    }
}
