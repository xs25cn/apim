<?php

namespace App\Http\Controllers\Admin;

/**
 * API响应时间
 * @filename   ApiResponseTimeController.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/26 16:48
 */

use App\Models\ApiResponseTime;
use App\Models\ApiUrl;
use App\Services\ApiDomainService;
use App\Services\ApiUrlService;
use App\Services\EsService;
use Illuminate\Http\Request;
use IpTools\IpArea;

class ApiResponseTimeController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new ApiResponseTime();

    }

    //列表
    public function index()
    {

        if (request('reportrange')) {
            $arr = explode(' - ', request('reportrange'));
            $start_time = $arr[0] . " 0:00:00";
            $end_time = $arr[1] . " 23:59:59";

        } else {
            $start_time = date('Y-m-d H:i:s', time() - 60 * 60 * 8);
            $end_time = date('Y-m-d H:i:s', time());
        }

        $where = [];
        $where[] = ['timestamp', '>=', $start_time];
        $where[] = ['timestamp', '<=', $end_time];

        //平均时间长区间
        if (request('avg_min') && request('avg_max')) {
            $where[] = ['avg', '>=', request('avg_min')];
            $where[] = ['avg', '<=', request('avg_max')];
        }

        //API详情
        if (request('api_url_id')) {
            $where[] = ['api_url_id', '=', request('api_url_id')];
            //查出接口对应的名称与域名
            $api_info = ApiUrl::query()->where('id', request('api_url_id'))->first();
            //是否有项目权限
            if (!in_array($api_info->api_domain_id, array_column($this->user_domain, 'id'))) {
                return $this->error('无此项目权限');
            }
        } else {
            return $this->error('非法请求');
        }


        //小时统计 select DATE_FORMAT(timestamp,'%m-%d %H') as time,AVG(avg) from lv_api_response_time group by time;
        //统计方式:分,时,日,月

        //默认第1种,后面改为后台配置
        $view_type = request('view_type');
        if (in_array($view_type, [2, 3, 4])) {
            //时，日，月
            switch ($view_type) {
                //小时
                case 2:
                    $format = '%m-%d %H:00';
                    break;
                //天
                case 3:
                    $format = '%Y-%m-%d';
                    break;
                //月
                case 4:
                    $format = '%Y.%c';
                    break;
                default:
                    $format='%m-%d %H:00';
            }
            $fields = "DATE_FORMAT(timestamp,'$format') as times,sum(avg*total)/sum(total) avg,min(min) min,Max(max) max,
        sum(total) total,sum(total_1) total_1,sum(total_2) total_2,sum(total_3) total_3,sum(total_4) total_4,sum(time_alert_total) time_alert_total,
        sum(code_200) code_200,sum(code_3xx) code_3xx,sum(code_4xx) code_4xx,sum(code_499) code_499,sum(code_500) code_500,sum(code_502) code_502,sum(code_504) code_504,sum(code_5xx) code_5xx";

        } else {
            //分钟
            $format = '%m-%d %H:%i';
            $fields = "DATE_FORMAT(timestamp,'$format') as times,AVG(avg) avg,min(min) min,Max(max) max,
        sum(total) total,sum(total_1) total_1,sum(total_2) total_2,sum(total_3) total_3,sum(total_4) total_4,sum(time_alert_total) time_alert_total,
        sum(code_200) code_200,sum(code_3xx) code_3xx,sum(code_4xx) code_4xx,sum(code_499) code_499,sum(code_500) code_500,sum(code_502) code_502,sum(code_504) code_504,sum(code_5xx) code_5xx";
        }
        $lists = $this->M->select(\DB::raw($fields))
            ->where($where)
            ->groupBy('times')
            ->orderBy('times', 'asc')
            ->get();
        foreach ($lists as $k => $v) {
            $lists[$k]->avg = round($v->avg, 3);
            $lists[$k]->max = round($v->max, 3);
        }

        //此接口详情
        return $this->view(compact('lists', 'api_info', 'start_time', 'end_time'));

    }

    //数据详情（实时）
    public function info()
    {
        $domain = request('domain');
        $url = request('url');
        if (!request('start_time')) {
            $start_time = date('Y-m-d H:i:s',time()-60*10);
        }else{
            $start_time = request('start_time');
        }
        $size = request('size') ?: 20;
        $lists = ApiUrlService::getInstance()->getApiLastResponse($domain, $url, $start_time, $size);

        return $this->view(compact('lists', 'start_time'));
    }


}
