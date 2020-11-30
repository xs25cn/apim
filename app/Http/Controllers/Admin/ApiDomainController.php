<?php

namespace App\Http\Controllers\Admin;

use App\Models\ApiDomain;
use App\Models\ApiDomainAdminUser;
use App\Models\ApiResponseTime;
use App\Models\ApiUrl;
use App\Services\ApiUrlService;
use App\Jobs\AsyncJob;
use Illuminate\Support\Facades\Validator;

class ApiDomainController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new ApiDomain();

    }

    //列表
    public function index()
    {
        $where = [];

        if (request('start_time') && request('end_time')) {
            $where['and'][] = ['created_at', '>=', strtotime(request('start_time'))];
            $where['and'][] = ['created_at', '<=', strtotime(request('end_time'))];
        }
        if(request('domain')){
            $where['and'][]=['domain','like','%'.request('domain').'%'];
        }
        if(request('title')){
            $where['and'][]=['title','like','%'.request('title').'%'];
        }
        if (request('es_index')) {
            $where['and'][] = ['es_index', request('es_index')];
        }
        if (request('env_type')) {
            $where['and'][] = ['env_type', request('env_type')];
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
        //检测是否已存在该域名
        $info = $this->M->where('domain',trim(request('domain')))->first();
        if($info){
            return $this->error(request('domain').' 域名已存在！请找相关开发组分配！','',10);
        }
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
            if($rs){
                //将此项目分配给自己
                ApiDomainAdminUser::create(['admin_user_id'=>$this->login_user->id,'api_domain_id'=>$rs->id]);
            }
        }
        return $rs;
    }

    //状态禁用
    public function status()
    {
        $info = $this->M->find(request('id'));
        if(!in_array(request('id'),array_column($this->user_domain,'id'))){
            return $this->error('此项目无权限');
        }
        if (!$info) {
            return $this->error('找不到这条信息');
        }
        $info->status = $info->status == 1 ? 2 : 1;
        $info->save();
        return $this->success();
    }

    public function del(){
        $this->M->where('id', request('id'))->delete();
        return $this->success();
    }

    /**
     * 参数校验
     * @author DuZhenxun <5552123@qq.com>
     * @param $params
     * @param $key
     * @throws UnValidDataException
     */
    private function _paramsValidator($params, $key)
    {
        $roule = [];//校验规则

        $roule['transferPrice'] = [
            ['taskid' => 'required', 'masterid' => 'required', 'bank_prop_type' => 'required',  'toStatus' => function ($attr, $value, $fail) {
                if ($value != 16) {
                    $fail(sprintf("%s 必须是16", $attr));
                }
            }]
        ];

        if (!empty($roule[$key])) {
            $validator = Validator::make($params, ...$roule[$key]);
            if ($validator->fails()) {
                $message = $validator->messages()->first();
                throw new UnValidDataException($message);
            }
        }
    }

}
