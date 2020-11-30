<?php
/**
 *
 * @filename  RedisService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/1/17 13:54
 * @version   $Id$
 */

namespace App\Services;


class RedisService
{
    public static function connection(){
        $config = config('database.redis');
        $redis = new \Redis();
        if (!$redis->connect($config['default']['host'], $config['default']['port'])) {
            throw new \Exception('redis 连接有问题');
        }
        // $redis = new \Predis\Client(['scheme'=>'tcp','host'=>$config['default']['host'],'port'=>$config['default']['port']]);
        return $redis;
    }
}