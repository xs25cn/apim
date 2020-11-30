<?php

namespace App\Http\Controllers\Admin;

use http\Env\Response;
use Illuminate\Support\Facades\Redis;

class PcntlController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index()
    {
        $pids = Redis::hgetall(config('common.pid_key'));
        $lists = [];
        if (count($pids)) {
            foreach ($pids as $v) {
                $lists[] = json_decode($v, 'true');
            }
        }
        array_multisort($lists, array_column($lists, 'time'), SORT_ASC, SORT_STRING);
        foreach($lists as $k=>$v){
            $lists[$k]['start_time'] = date('Y-m-d H:i:s',$v['time']);
            $lists[$k]['all_time'] = sec2time(time()-$v['time']);
        }
        if(request()->ajax()){
            return $lists;
        }else{
            return $this->view(compact('lists'));
        }

    }

    public function del()
    {
        $child_pid = request('child_pid');
        if (empty($child_pid)) {
            Redis::del(config('common.pid_key'));
        } else {
            Redis::hdel(config('common.pid_key'), $child_pid);
        }
        if(request()->ajax()){
            return ['code'=>1,'msg'=>'ok'];
        }else{
            return $this->success();
        }

    }


}
