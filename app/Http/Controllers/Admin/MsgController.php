<?php
/**
 * 提示消息
 * @filename  MsgController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-7-31 17:43:47
 * @version   SVN:$Id:$
 */
namespace App\Http\Controllers\Admin;

class MsgController
{
    public function index()
    {

        //验证参数
        if (!empty(session('msg'))) {
            $data = [
                'msg' => session('msg'),
                'url' => session('url'),
                'wait' => session('wait') ?: 1,
                'code' => session('code') ?: 0,
                'data' => session('data')
            ];
        } else {
            $data = [
                'msg' => '前往首页中...',
                'url' => '/',
                'wait' => 1,
                'code' => 0,
                'data' => '',
            ];
        }
        return view('admin.jump', ['data' => $data]);
    }
}