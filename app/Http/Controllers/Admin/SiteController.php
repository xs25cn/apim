<?php

namespace App\Http\Controllers\Admin;

use App\Models\Site;
use App\Services\SiteService;

class SiteController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new Site();
    }

    //列表
    public function index()
    {
        $where = [];
        $lists = $this->M->where($where)->orderBy('id', 'desc')->paginate(20);
        return $this->view(compact('lists'));

    }

    //详情
    public function info()
    {
        $info = $this->M->find(1);
        $info->setting = json_decode($info->setting, true);
        return $this->view(compact('info'));
    }

    //添加
    protected function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功');
        } else {
            return $this->error();
        }
    }

    //修改
    protected function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功');
        } else {
            return $this->error();
        }
    }

    //存储
    public function storage()
    {
        $info = request('info');

        foreach($info['setting'] as $k=>$arr){
            if(is_array($arr)){
                $info['setting'][$k]= implode(',',$arr);
            }
        }
        $info['setting'] = json_encode($info['setting'], 64 | 256);
        $id = request('id');
        //修改
        if ($id) {
            $rs = $this->M->where('id', $id)->update($info);
        } else {
            //添加
            $rs = $this->M->create($info);
        }
        SiteService::getInstance()->setInfo();
        return $rs;
    }


}
