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

/*Route::get('/', function () {
    return view('welcome');
});
Route::resource('test','TestController');*/

//Route::get('/admin', 'Admin\IndexController@index');

if (php_sapi_name()!='cli') {
    $method = strtolower($_SERVER['REQUEST_METHOD']); //方法
    $act = explode('?', $_SERVER['REQUEST_URI'])[0]; //请求

    if ($act != '/') {
        $path = explode('/', trim($act, '/'));
    } else {
        $path[0] = 'Admin';
    }


    if (!isset($path[1])) {
        $path[1] = 'Index';
    }
    if (!isset($path[2])) {
        if($path[0]=='Admin' && $path[1]=='Index'){
            $path[2] = 'index';
        }else{
            $path[2] = 'index';
        }


    }

    $path[0] = ucfirst($path[0]);
    $path[1] = ucfirst($path[1]);

    Route::$method($act, $path[0] . '\\' . $path[1] . 'Controller@' . $path[2]);
}


