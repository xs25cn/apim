<?php

/**
 * 工作记录
 * @filename  WorkInfoController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-06-25 19:43:47
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;


use App\Models\WorkInfo;

class WorkInfoController extends Controller
{

    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new WorkInfo();
    }

    //列表
    public function index()
    {
        $where = [];
        $where[] = ['admin_id', $this->login_user->id];
        $where[] = ['is_delete', 0];
        if (request('start_time') && request('end_time')) {
            $where[] = ['created_at', '>=', strtotime(request('start_time'))];
            $where[] = ['created_at', '<=', strtotime(request('end_time'))];
        }
        if (request('admin_id')) {
            $where[] = ['admin_id', request('admin_id')];
        }
        $lists = $this->M->where($where)->orderBy('id', 'desc')->paginate(20);

        return $this->view(compact('lists'));

    }

    //详情
    public function info()
    {
        $info = $this->M->find(request('id'));
        return $this->view(compact('info'));
    }
    //添加
    protected function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //修改
    protected function edit()
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
        $info = request('info');
        $info['admin_id'] = $this->login_user->id;
        $info['reminder_at'] = strtotime($info['reminder_at']);
        $id = request('id');
        //修改
        if ($id) {
            $info['is_reminder'] = 1; //再次提醒
            $rs = $this->M->where('id', $id)->update($info);
        } else {
            //添加
            $rs = $this->M->create($info);
        }
        return $rs;
    }


}
