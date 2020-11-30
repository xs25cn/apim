<?php
/**
 *
 * @filename  CrontabService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/3/14 20:07
 * @version   $Id$
 */
namespace App\Services;
use App\Models\Crontab;
use Illuminate\Support\Facades\Redis;

class CrontabService extends  Service{
    //获取crontab
    public function getCrontab()
    {
        $crontab = Redis::get('apim:crontab');
        if(!$crontab){
            $crontab = $this->setCrontab();
        }else{
            $crontab = json_decode($crontab,true);
        }
        return $crontab;
    }

    /**
     * 设置crontab
     */
    public function setCrontab()
    {
        $res = Crontab::onWriteConnection()->select('name', 'code', 'crontab')->where('status', 1)->get()->toArray();
        $crontab = array_column($res, 'crontab', 'code');
        Redis::set('apim:crontab',json_encode($crontab));
        return $crontab;
    }


}