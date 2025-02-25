<?php


// 登录相关路由
use think\facade\Route;

Route::group('', function () {
    Route::get('login', 'Login/index');
    Route::post('login/check', 'Login/check');
    Route::get('login/captcha', 'accountLogin/captcha');
    Route::get('logout', 'accountLogin/logout');

    // 后台首页相关路由
    Route::get("/", 'index/index');
    Route::get('index', 'index/welcome');
    Route::get('welcome', 'index/welcome');


    // 系统相关路由
    Route::get('getLoginUser', 'system/getLoginUser');
    Route::get('getMenu', 'system/getMenu');

    // 用户相关
    Route::group('user', function () {
        Route::get('/', 'user/index');
        Route::get('list', 'user/list');
        Route::post('save', 'user/save');
    });

    // 上传文件
    Route::group('upload', function () {
        Route::post('/', 'upload/upload');
    });
})->middleware(\app\admin\middleware\CheckLogin::class);


