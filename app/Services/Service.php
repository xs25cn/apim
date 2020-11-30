<?php
/**
 * Service
 * @filename  Service.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/5 09:50
 */

namespace App\Services;


class Service
{
    protected static $instance=[];

    /**
     * 服务对象实例（单例模式）
     * @return static
     */
    public static function getInstance()
    {

        if(!isset(self::$instance[static::class]))
        {
            static::$instance[static::class] =new static();
        }
        return static::$instance[static::class];
    }


}
