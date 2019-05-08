<?php

namespace App\Http\Controllers;

use App\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
class UserLoginController extends Controller
{
    public function login(){
        return view('user/login');
    }
    public function logindo(){
        $name=request()->input('name');
        $pwd=request()->input('pwd');
        $info=UsersModel::where(['name'=>$name])->first();
        if($info){
            if($name==$info->name&&password_verify($pwd,$info->pwd)){
                $token=substr(sha1($info['uid'].time().str::random(10)),5,15);
                $key='uid_token'.$info->uid;
                if(Redis::get($key)){

                }else{
                    Redis::set($key,$token);
                    Redis::expire($key,3600);
                }
                setcookie('token',Redis::get($key),time()+3600,'/','1809a.com',false,true);
                setcookie('uid',$info['uid'],time()+3600,'/','1809a.com',false,true);
            }else{
                echo "登录失败";
            }
        }else{
            echo "信息不正确";
        }
    }
}
