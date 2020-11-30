<?php
/**
 * 异步任务服务
 * @filename  AsyncService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @time      2018/10/28 下午5:43
 * @version   SVN:$Id:$
 */


namespace App\Services;

class AsyncService extends Service
{
    public $serviceName; //类名
    public $methodName; //方法
    public $params; //参数

    /**
     * 分派 异步任务
     * 使用方法
     * AsyncService::getInstance()->dispatch('high','\App\Library\Common','createOperationLog');
     * AsyncService::getInstance()->dispatch('high','CarImgService','test');
     * AsyncService::getInstance()->dispatch('high','CarImgService','test2',[$arg1,$arg2]);
     * @param $queue_key
     * @param string $service
     * @param $method
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function dispatch($queue_key, $service, $method, $params = [])
    {

        if (empty($service)) {
            throw new \Exception('缺少service');
        }
        if (empty($method)) {
            throw new \Exception('缺少method');
        }

        if (!is_array($params)) {
            throw new \Exception('params必修是数组');
        }

        //传入数据
        $payload = [];
        $payload['service'] = $service;
        $payload['method'] = $method;
        $payload['params'] = $params;


        //队列名
        $queue_arr = config('queue.name');
        if (array_key_exists($queue_key, $queue_arr)) {
            $queue_name = $queue_arr[$queue_key];
        }else{
            $queue_name = 0;//同步
        }


        if ($queue_name) {
            $data = ["tid" => $queue_name, "payload" => json_encode($payload)];
            $this->sendDtq($data);
        } else {
            //同步
            $this->execute($payload);
        }
        return json_encode($payload);
    }


    /**
     * 执行
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function execute(array $data)
    {

        //service
        if (!array_key_exists('service', $data)) {
            throw new \Exception('缺少service');
        }
        //传入的名称是否以\App开头，如果以\App开头直接用 如:\App\Libray\Common
        if (substr($data['service'], 0, 4) == "\App") {
            $this->serviceName = $data['service'];
        } else {
            $this->serviceName = "\\App\\Services\\" . ucfirst($data['service']);
        }


        //方法
        if (!array_key_exists('method', $data)) {
            throw new \Exception('缺少method');
        }
        $this->methodName = $data['method'];

        //参数
        if (array_key_exists('params', $data)) {
            $this->params = $data['params'];
        }else{
            $this->params = [];
        }


        //检查类是否存在
        if (!class_exists($this->serviceName)) {
            throw new \Exception('找不到文件' . $this->serviceName);
        }
        
        $service = new \ReflectionClass($this->serviceName);

        //检查方法是否存在
        $method = $service->getMethod($data['method']);
        if (!$method) {
            throw new \Exception('找不到文件' . $this->serviceName . '中的方法:' . $this->methodName);
        }

        //检查方法需要传递参数个数
        $params = $method->getParameters();
        $i=0;
        if (count($params) > 0) {
            foreach ($params as $param) {
                if (!$param->isOptional()) {
                    $i++;
                }
            }
            if (count($this->params) < $i) {
                throw new \Exception('类:' . $this->serviceName . '中方法:' . $this->methodName . ",必传参数量:" . count($params));
            }
        }else{
            $this->params=[];
        }

        //反射,实例化
        $instance = (new \ReflectionClass($this->serviceName))->newInstanceArgs();

        //执行方法
        return call_user_func([$instance, $this->methodName], ...$this->params);
    }


    /**
     * 发送到dtq异步任务中
     * @param $data
     * $data = ["tid" => $tid, "payload" => json_encode($payload)]
     */
    public function sendDtq($data)
    {
        ksort($data);
        $str = http_build_query($data, '', '&', 2) . config('queue.dtq_key');
        $sign = md5(urldecode($str));
        $data['sign'] = $sign;
        $dtq_url = config('queue.dtq_url');
        my_curl($dtq_url, $data);
    }

}