<?php

/**
 * 定时任务
 * @filename  CrontabController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-06-25 17:43:47
 */

namespace App\Http\Controllers\Admin;


use App\Models\Crontab;
use App\Services\CrontabService;

class CrontabController extends Controller
{

    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new Crontab();
    }

    //列表
    public function index()
    {
        $where = [];
        if (request('status')) {
            $where[] = ['status', request('status')];
        }
        $lists = $this->M->where($where)->orderBy('id', 'desc')->paginate(20);
        $lists->load('btAdminUser');
        return $this->view(compact('lists'));

    }

    //详情
    public function info()
    {
        $info = $this->M->find(request('id'));
        return $this->view(compact('info'));

    }

    //添加
    public function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //修改
    public function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //存储
    public function storage()
    {
        $this->validate(request(), $this->M->rules, $this->M->messages);
        $params = request($this->M->fillable); //可以添加或修改的参数
        if (request('id')) {
            $rs = $this->M->where('id', request('id'))->update($params);
        } else {
            $params['admin_id'] = $this->login_user->id;
            $rs = $this->M->create($params);
        }
        CrontabService::getInstance()->setCrontab();
        return $rs;
    }

    // 状态禁
    public function status()
    {
        $info = $this->M->find(request('id'));
        if (!$info) {
            return $this->error('找不到这条信息');
        }
        $info->status = $info->status == 1 ? 2 : 1;
        $info->save();
        CrontabService::getInstance()->setCrontab();
        return $this->success();
    }

    //删除
    public function del()
    {
        $this->M->where('id', request('id'))->delete();
        CrontabService::getInstance()->setCrontab();
        return $this->success();
    }

    public function statement(){
        if(request('str')){
            $res = CrontabService::getInstance()->statement(request('str'));
            if($res['code']==1){
                return $this->success();
            }else{
                return $this->error($res['msg']);
            }
        }else{
            return $this->view();
        }
    }

}
