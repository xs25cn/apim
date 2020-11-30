<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApiModule;
use App\Services\ApiDomainService;
use Illuminate\Http\Request;

class ApiModuleController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new ApiModule();

    }

    //列表
    public function index()
    {
        $where = [];
        if (request('start_time') && request('end_time')) {
            $where['and'][] = ['created_at', '>=', strtotime(request('start_time'))];
            $where['and'][] = ['created_at', '<=', strtotime(request('end_time'))];
        }
        //是否有项目权限
        if (request('api_domain_id')) {
            if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
                return $this->error('无此项目权限');
            }
            $where['and'][] = ['api_domain_id', request('api_domain_id')];
        } else {
            if (count($this->user_domain) > 0) {
                $where['in'][] = ['api_domain_id', array_column($this->user_domain, 'id')];
            }else{
                $where['in'][] = ['api_domain_id', [0]];
            }
        }

        $lists = $this->M->where(function ($q) use ($where) {
            if ($where['and']) {
                $q->where($where['and']);
            }
            if (isset($where['in'])) {
                foreach ($where['in'] as $k => $v) {
                    $q->whereIn($v[0], $v[1]);
                }
            }
        })->orderBy('id', 'desc')->paginate(20);


        $lists->load('btApiDomain','btAdminUser');


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
    private function storage()
    {
        $this->validate(request(), $this->M->rules, $this->M->messages);
        $params = request($this->M->fillable); //可以添加或修改的参数
        $params['admin_id'] = $this->login_user->id;
        if (request('id')) {
            $rs = $this->M->where('id', request('id'))->update($params);
        } else {
            $rs = $this->M->create($params);
        }
        return $rs;
    }

    //删除
    public function  del()
    {
        $id = request('id');
        $this->M->where('id', $id)->delete();
        return $this->success();
    }


    //获取系统域名对应的model
    public function publicSystemApiModel()
    {
        $where = [];
        if (request('api_domain_id')) {
            $where[] = ['api_domain_id', request('api_domain_id')];
        }
        $data = ['code' => 1, 'msg' => 'ok'];
        $data['data'] = M('ApiModule')->select('title', 'id')->where($where)->get()->toArray();


        return $data;
    }



}
