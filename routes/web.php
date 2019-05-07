<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/api/user','ApiController@userapi');
//curl get post
Route::get('/api/test','ApiController@test');
Route::get('/api/test/a','ApiController@testA');
Route::get('/api/test/b','ApiController@testB');
Route::get('/api/test/c','ApiController@testC');
//中间件限制10次
Route::get('/api/mid','ApiController@mid10')->middleware('request10times');
//注册
Route::post('/api/reg','ApiController@register');
//登录
Route::post('/api/login','ApiController@login');
//个人中心
Route::get('/api/myuser','ApiController@myuser')->middleware(['checklogin','request10times']);
//资源控制器
Route::resource('/goods',GoodsController::class);

//用户登录
Route::get('/login','UserLoginController@login');
Route::post('/logindo','UserLoginController@logindo');