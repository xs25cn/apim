<?php
/**
 *
 * @filename  SiteService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/3/14 20:11
 * @version   $Id$
 */
namespace App\Services;
use App\Models\Site;
use Illuminate\Support\Facades\Redis;

class SiteService extends Service{
    public function setInfo()
    {
        $info = Site::query()->first();
        Redis::set('apim:system:config', json_encode($info->toArray()));
        return $info;
    }


    //获取站点信息
    public function getInfo()
    {
        $info = Redis::get('apim:system:config');
        if ($info) {
            $info = json_decode($info);
        } else {
            $info = $this->setInfo();
        }
        return $info;
    }

    /**
     * 获取设置的邮箱
     * @param string $key
     * @return array|bool|\Illuminate\Config\Repository|mixed
     */
    public function getSetting($key = '')
    {
        $setting = json_decode($this->getInfo()->setting, true);
        $data = [];
        foreach ($setting as $k => $v) {
            $tmp = explode("\n", $v);
            foreach ($tmp as $kk => $vv) {
                $data[$k][$kk] = trim($vv);
            }
        }
        if ($key && isset($data[$key])) {
            $data = $data[$key];
        }
        return $data;
    }
}