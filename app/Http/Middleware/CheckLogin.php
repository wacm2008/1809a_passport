<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class CheckLogin
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
        $token=$request->input('token');
        $uid=$request->input('uid');
        if(empty($token)||empty($uid)){
            $response=[
                'errorno'=>40001,
                'msg'=>'参数不全',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $key='token_uid'.$request->input('uid');
        $local_token=Redis::get($key);
        if($token){
            if($token==$local_token){
                echo 'ok'.'<br>';
            }else{
                $response=[
                    'errorno'=>40004,
                    'msg'=>'token无效',
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            $response=[
                'errorno'=>40005,
                'msg'=>'请先登录',
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}
