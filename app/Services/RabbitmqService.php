<?php
/**
 *
 * @filename  RabbitmqService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/11/8 18:39
 */
namespace App\Services;
class RabbitmqService extends Service{

    /**
     * 消息通知
     * @param $data
     * @param string $messageType 类型[send_mail(邮件),send_wechat(微信)]
     * @return bool
     */
    public  function notice($data,$messageType='send_mail'){
        try{
            $rabbit_conf = config('xin.rabbit_conf');

            $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($rabbit_conf['host'], $rabbit_conf['port'],$rabbit_conf['user'],$rabbit_conf['password']);

            if(!$connection->isConnected()){
                throw new \Exception($connection);
            }
            $channel = $connection->channel();
            $channel->queue_declare($rabbit_conf['queue'], false, true, false, false);

            $message_pro = [
                'exchange' => $rabbit_conf['exchange'],
                'routeKey' => $rabbit_conf['routing_key'],
                'messageType' => $messageType,
                'queueName' => $rabbit_conf['queue'],
                'messageNo' => uniqid(),
                'messageBody' => json_encode($data,64|256)
            ];

            $message = json_encode($message_pro, 64|256);

            $msg = new \PhpAmqpLib\Message\AMQPMessage($message);

            $channel->basic_publish($msg, $rabbit_conf['exchange'], $rabbit_conf['routing_key']);
            $channel->close();
            $connection->close();
        }catch (\Exception $exception){
            \Log::info('rabbitmq操作失败');
        }
        return true;

    }
}