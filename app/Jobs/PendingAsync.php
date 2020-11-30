<?php
/**
 * 重新封装 队列保存格式
 * @filename  PendingAsync.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/1/17 14:45
 * @version   $Id$
 */

namespace App\Jobs;

use App\Services\RedisService;
use Illuminate\Support\Facades\Redis;

class PendingAsync
{
    public $serviceName, $methodName, $params;

    public function __construct($serviceName, $methodName, $params)
    {
        $this->serviceName = $serviceName;
        $this->methodName = $methodName;
        $this->params = $params;
    }

    public function onQueue($queueName)
    {
        /* $command = [
             'serviceName' => $this->serviceName,
             'methodName' => $this->methodName,
             'params' => $this->params,
             'job' => '',
             'connection' => '',
             'queue' => $queueName,
             'chainConnection' => '',
             'chainQueue' => '',
             'delay' => '',
             'chained' => [],
         ];*/
        $command = new AsyncJob($this->serviceName, $this->methodName, $this->params);
        $command->queue = $queueName;


        $data = [];
        $data['displayName'] = "App\Jobs\AsyncJob";
        $data['job'] = "Illuminate\Queue\CallQueuedHandler@call";
        $data['maxTries'] = null;
        $data['timeout'] = null;
        $data['timeoutAt'] = null;
        $data['data'] = [
            'commandName' => "App\Jobs\AsyncJob",
            'command' => serialize($command)
        ];
        $data['id'] = $queueName . '_' . $this->serviceName . '::' . $this->methodName . '_' . uniqid();
        $data['attempts'] = 0;
        $redis = RedisService::connection();
        $redis->lPush('queues:' . $queueName, json_encode($data));
        $redis->close();
    }


}
