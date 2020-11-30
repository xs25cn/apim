<?php

namespace App\Jobs;

/**
 * 异步任务
 * 添加 \App\Jobs\AsyncJob::dispatch('MailService','sendMail',['duzhenxun',28])->onQueue('apim:high');
 * 取出 php artisan queue:listen  --queue=apim:high --tries=1   --timeout=60
 * 取出 php artisan queue:listen  --queue=apim:high,apim:middle,apim:low --tries=1  --memory=1204 --timeout=600
 * @filename   AsyncJob.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 16:48
 */

use App\Services\MailService;
use App\Services\NoticeService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AsyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $serviceName; //类名
    public $methodName; //方法
    public $params; //参数


    /**
     * AsyncJob constructor.
     * @param $service_name
     * @param $method_name
     * @param array $params
     */
    public function __construct($service_name, $method_name, $params = [])
    {

        //传入的名称是否以\App开头，如果以\App开头直接用 如:\App\Libray\Common
        if (substr($service_name, 0, 4) == "\App") {
            $this->serviceName = $service_name;
        } else {
            $this->serviceName = "\\App\\Services\\" . ucfirst($service_name);
        }

        $this->methodName = $method_name;
        $this->params = $params;
    }


    public function handle()
    {

        //检查类是否存在
        if (!class_exists($this->serviceName)) {
            throw new \Exception('找不到文件' . $this->serviceName);
        }

        $service = new \ReflectionClass($this->serviceName);

        //检查方法是否存在
        $method = $service->getMethod($this->methodName);
        if (!$method) {
            throw new \Exception('找不到文件' . $this->serviceName . '中的方法:' . $this->methodName);
        }

        //检查方法需要传递参数个数
        $params = $method->getParameters();
        $i = 0;
        if (count($params) > 0) {
            foreach ($params as $param) {
                if (!$param->isOptional()) {
                    $i++;
                }
            }
            if (count($this->params) < $i) {
                throw new \Exception('类:' . $this->serviceName . '中方法:' . $this->methodName . ",必传参数量:" . count($params));
            }
        } else {
            $this->params = [];
        }

        //反射,实例化
        $instance = (new \ReflectionClass($this->serviceName))->newInstanceArgs();
        //执行方法
        return call_user_func([$instance, $this->methodName], ...$this->params);
    }


    public function failed()
    {
        //$content = $this->serviceName . "<br>" . $this->methodName . "<br>" . json_encode($this->params);
       // NoticeService::getInstance()->sendEmail('job error', $content);
    }

    //覆盖原系统写队列
    public static function dispatch()
    {
       return new PendingAsync(...func_get_args());
    }


}
