<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class ApiController extends Controller
{
    public function userapi(){
        $userInfo=UsersModel::get()->toArray();
        if($userInfo){
            $data=[
                'errorno'=>0,
                'msg'=>'ok',
                'data'=>$userInfo
            ];
        }
        $da=json_encode($data,true);
        var_dump($da);
    }
    public function test(){
        $url="http://1809a.apitest.com/users?uid=1";
        // 创建一个新cURL资源
        $ch = curl_init();

        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // 抓取URL并把它传递给浏览器
        curl_exec($ch);

        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
    }
    //curl post
    public function testA(){
        $url="http://1809a.apitest.com/users/a";
        $data='name=isco&email=123@qq.com';
        // 创建一个新cURL资源
        $ch = curl_init();

        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);//默认发送数据类型 application/x-www-form-urlencoded
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//浏览器不输出
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        // 抓取URL并把它传递给浏览器
        $cu=curl_exec($ch);
        var_dump($cu);die;
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
    }
    public function testB(){
        $url="http://1809a.apitest.com/users/b";
        $data = [
            'name' => 'isco',
            'email'     => '123@qq.com'
        ];
        // 创建一个新cURL资源
        $ch = curl_init();

        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//浏览器不输出
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        // 抓取URL并把它传递给浏览器
        $cu=curl_exec($ch);
        var_dump($cu);die;
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
    }
    public function testC(){
        $url="http://1809a.apitest.com/users/c";
        $data = [
            'name' => 'isco',
            'email'     => '123@qq.com'
        ];
        //$data='name=isco&email=123@qq.com';
        $json=json_encode($data);
        // 创建一个新cURL资源
        $ch = curl_init();

        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//浏览器不输出
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);//发送raw数据
        // 抓取URL并把它传递给浏览器
        $cu=curl_exec($ch);
        var_dump($cu);die;
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
    }
    //中间件限制10次
    public function mid10(){
        echo __METHOD__;
    }
    //注册
    public function register(){
        //密码验证
        $pwd1=request()->input('pwd1');
        $pwd2=request()->input('pwd2');
        if($pwd1!=$pwd2){
            $response=[
                'errorno'=>50001,
                'msg'=>'密码不一致'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $pwd=password_hash($pwd1,PASSWORD_DEFAULT);
        //邮箱验证
        $email=request()->input('email');
        $e=UsersModel::where(['email'=>$email])->first();
        if($e){
            $response=[
                'errorno'=>50002,
                'msg'=>'邮箱存在'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        $data=[
            'name'=>request()->input('name'),
            'email'=>$email,
            'age'=>request()->input('age'),
            'pwd'=>$pwd,
            'add_time'=>time()
        ];
        $add=UsersModel::insert($data);
        if($add){
            $response=[
                'errorno'=>0,
                'msg'=>'注册成功'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }
    //登录
    public function login(){
        $email=request()->input('email');
        $pwd=request()->input('pwd');
        $e=UsersModel::where(['email'=>$email])->first();
        if($e){
            if(password_verify($pwd,$e->pwd)){
                $token=$this->getLoginToken($e->uid);
                $token_key='token_uid'.$e->uid;
                Redis::set($token_key,$token);
                Redis::expire($token_key,604800);
                $response=[
                    'errorno'=>0,
                    'msg'=>'登录成功',
                    'token'=>$token
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }else{
                $response=[
                    'errorno'=>"50003",
                    'msg'=>'密码不正确'
                ];
                die(json_encode($response,JSON_UNESCAPED_UNICODE));
            }
        }else{
            $response=[
                'errorno'=>"50004",
                'msg'=>'账号或密码不正确'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
    }
    //登录生成token
    public function getLoginToken($uid){
        return substr(sha1($uid.time().str::random(10)),5,15);
    }
    //个人中心
    public function myuser(){
        echo 123;
    }
}
