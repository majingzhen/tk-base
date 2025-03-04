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

    // 上传文件
    Route::group('upload', function () {
        Route::post('/', 'upload/upload');
    });

    // 用户相关
    Route::group('user', function () {
        Route::get('/', 'user/index');
        Route::get('page', 'user/page');
        Route::post('save', 'user/save');
        Route::post('delete', 'user/delete');
        Route::get('roles', 'user/roles');
        Route::post('unlock', 'user/unlock');
    });

    // 角色相关
    Route::group('role', function () {
        Route::get('/', 'role/index');
        Route::get('list', 'role/list');
        Route::get('page', 'role/page');
        Route::post('save', 'role/save');
        Route::post('delete', 'role/delete');
    });

    // 菜单相关
    Route::group('menu', function () {
        Route::get('/', 'menu/index');
        Route::get('list', 'menu/list');
        Route::post('save', 'menu/save');
        Route::post('delete', 'menu/delete');
    });
    // 配置管理
    Route::group('config', function () {
        Route::get('/', 'config/index');
        Route::get('page', 'config/page');
        Route::post('save', 'config/save');
        Route::post('delete', 'config/delete');
        Route::get('getConfigByTab', 'config/getConfigByTab');
    });
})->middleware(\app\admin\middleware\CheckLogin::class);


