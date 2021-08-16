<?php

namespace App\Http\Controllers\Admin;

/**
 * api 接口
 * @filename   ApiUrlController.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 16:48
 */
use App\Jobs\AsyncJob;
use App\Models\ApiDomain;
use App\Models\ApiResponseTime;
use App\Models\ApiUrl;
use App\Services\ApiUrlService;
use App\Services\SiteService;

class ApiUrlController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new ApiUrl();
    }

    //列表
    public function index()
    {
        $where = [];
        if (request('start_time') && request('end_time')) {
            $where['and'][] = ['created_at', '>=', strtotime(request('start_time'))];
            $where['and'][] = ['created_at', '<=', strtotime(request('end_time'))];
        }

        if (request('url')) {
            $where['and'][] = ['url','like', '%'.request('url').'%'];
        }
        if (request('api_module_id')) {
            $where['and'][] = ['api_module_id', request('api_module_id')];

        }

        //是否有项目权限
        if (request('api_domain_id')) {
            if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
                return $this->error('无此项目权限');
            }
            $where['and'][] = ['api_domain_id', request('api_domain_id')];

        } else {
            if (is_array($this->user_domain) && count($this->user_domain) > 0) {
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
        })->orderBy('updated_at', 'desc')->paginate(20);

        $lists->load('btApiDomain','btAdminUser');
        //今日访问量统计
        $today_total = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d'),request('api_domain_id'));
        $today_avg = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d'),request('api_domain_id'),'sum(avg*total)/sum(total)');
        $today_code = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d'),request('api_domain_id'),'sum(code_500+code_504+code_502)');
        $today_5s_up = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d'),request('api_domain_id'),'sum(total_3+total_4)');
        //昨日访问量
        $yesterday_total = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d',strtotime("-1 day")),request('api_domain_id'));
        $yesterday_avg = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d',strtotime("-1 day")),request('api_domain_id'),'SUM(AVG*total)/SUM(total)');
        $yesterday_code = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d',strtotime("-1 day")),request('api_domain_id'),'sum(code_500+code_504+code_502)');
        $yesterday_5s_up = ApiUrlService::getInstance()->getDayTotal(date('Y-m-d',strtotime("-1 day")),request('api_domain_id'),'sum(total_3+total_4)');

        foreach ($lists as $k=>$v){
            $lists[$k]->today_total = $today_total[$v->id]['total'];
            $lists[$k]->today_avg = round($today_avg[$v->id]['total'],3);
            $lists[$k]->today_code = $today_code[$v->id]['total'];
            $lists[$k]->today_5s_up = $today_5s_up[$v->id]['total'];

            $lists[$k]->yesterday_total = $yesterday_total[$v->id]['total'];
            $lists[$k]->yesterday_avg = round($yesterday_avg[$v->id]['total'],3);
            $lists[$k]->yesterday_code = $yesterday_code[$v->id]['total'];
            $lists[$k]->yesterday_5s_up = $yesterday_5s_up[$v->id]['total'];
        }

        $api_domain_info = ApiDomain::query()->where('id',request('api_domain_id'))->first();
        return $this->view(compact('lists','api_domain_info'));

    }

    //详情
    public function info()
    {
        //是否有项目权限
        if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
            return $this->error('无此项目权限');
        }
        $info = $this->M->find(request('id'));
        return $this->view(compact('info'));
    }

    //批量获取api地址
    public function bachApiUrl()
    {
        //是否有项目权限
        if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
            return $this->error('无此项目权限');
        }
        if(request('prefix') && request('api_domain_id')){
            $api_domain_id = request('api_domain_id');
            $prefix = request('prefix');
            $start_time = strtotime(request('start_time'));
            $end_time = strtotime(request('end_time'));
            if (!$start_time) {
                $start_time = time() - 86400;
            }
            if(!$end_time){
                $end_time = $start_time + 86400;
            }
            if($end_time-$start_time>86400*5){
                return $this->error('时间范围不能大于5天');
            }
            if($end_time-$start_time<60){
                return $this->error('时间范围不能少于1分钟');
            }

            $queueName = SiteService::getInstance()->getSetting('get_api_url')[0];
            if($queueName){
                //异步
                AsyncJob::dispatch('ApiUrlService','syncDomainApiUrl',[$api_domain_id,$prefix,$start_time,$end_time])->onQueue($queueName);
            }else{
                //同步,时间较长...
                ApiUrlService::getInstance()->syncDomainApiUrl($api_domain_id,$prefix,$start_time,$end_time);
            }
            return $this->success('操作成功,数据抓取中...');
        }else{
            return $this->view();
        }

    }


    //添加
    public function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功');
        } else {
            return $this->error();
        }
    }

    //修改
    public function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功');
        } else {
            return $this->error();
        }
    }

    //删除
    public function  del()
    {
        //是否有项目权限
        if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
            return $this->error('无此项目权限');
        }

        $id = request('id');
        $this->M->where('id', $id)->delete();
        ApiResponseTime::query()->where('api_url_id', $id)->delete();
        return $this->success();
    }

    //批量删除
    public function batchDel(){
        //是否有项目权限
        if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
            return ["code"=>-1,"msg"=>"无此项目权限"];
        }
        $ids = request('ids');
        $id_arr = explode(",",$ids);

        $this->M->whereIn('id', $id_arr)->delete();
        ApiResponseTime::query()->whereIn('api_url_id', $id_arr)->delete();
        return ['code'=>1,'msg'=>'ok'];
    }

    //存储
    public function storage()
    {

        $params = request(M('ApiUrl')->fillable); //可以添加或修改的参数
        $params['url'] = '/'.trim(trim($params['url']), '/');
        $params['admin_id'] = $this->login_user->id;

        if(is_array($params['code_alert']) && count($params['code_alert'])>0){
            $params['code_alert'] = implode(',',$params['code_alert']);
        }else{
            $params['code_alert']='';
        }
        if(empty($params['api_module_id'])){
            $params['api_module_id'] = 0;
        }
        if(empty($params['title'])){
            $start = strripos($params['url'],'/');
            $params['title'] = substr($params['url'],$start+1);
        }

        if (request('id')) {
            $rs = $this->M->where('id', request('id'))->update($params);
        } else {
            $rs = $this->M->create($params);
        }
        return $rs;
    }

    //异步请求响应数据
    public function asyncResponseTime(){
        $id = request('id');
        if(!request('api_domain_id')){
            return $this->error('缺少域名 id');
        }

        //是否有项目权限
        if(!in_array(request('api_domain_id'),array_column($this->user_domain,'id'))){
            return $this->error('无此项目权限');
        }

        $queueName =SiteService::getInstance()->getSetting('get_api_response_time')[0];
        if($queueName){
            //异步
            AsyncJob::dispatch('ApiUrlService','syncApiResponseTime',[$id,request('api_domain_id')])->onQueue($queueName);
        }else{
            //同步,时间较长...
            ApiUrlService::getInstance()->syncApiResponseTime($id,request('api_domain_id'));
        }

        return $this->success('操作成功,正在同步中...');
    }


}
