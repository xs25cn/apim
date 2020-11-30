<?php
/**
 * 通知
 * @filename  NoticeService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/13 18:51
 */

namespace App\Services;

use App\Library\Common;
use Illuminate\Support\Facades\Redis;

class  NoticeService extends Service
{
    /**
     * 发邮件
     * @param $subject
     * @param string $contents
     * @param string $to
     * @param string $ext
     * @return bool
     */
    public function sendEmail($subject, $contents = '', $to = '', $ext = 'html')
    {
        if (!$to) {
            $to = config('mail.to_email');
        }
        $data = [
            'to' => $to,
            'subject' => $subject,
            'body' => stripslashes($contents),
            'sysName' => '',
            'msgSource' => 'apim',
            'ext' => $ext,
        ];
        //这里要发邮件......
        return send_mail($data['subject'], $contents, $to);
    }


    /**
     * 微信通知
     * @param string $msg1
     * @param string $msg2
     * @param string $msg3
     * @param string $msg4
     * @param string $to
     * @return bool
     */
    public function sendWeiXin($msg1 = '', $msg2 = '', $msg3 = '', $msg4 = '', $to = '')
    {
        $data = [
            'to' => $to,
            'sysName' => $msg1,
            'subject' => $msg2,
            'body' => $msg3,
            'ext' => $msg4,
            'msgSource' => 'apim'
        ];

        return RabbitmqService::getInstance()->notice($data, 'send_wechat');
    }

    //发送日志
    public function sendLog($data, $system_config = '')
    {
        try {
            if (!$system_config) {
                $system_config = SiteService::getInstance()->getSetting();
            }
            if (empty($system_config['send_log'])) {
                return false;
            }
            $send_log = explode(',', $system_config['send_log'][0]);

            if (in_array('web_scoket', $send_log)) {
                $this->_sendLogWebScoket($data);
            }
            if (in_array('redis_log', $send_log)) {
                $this->_sendLogRedis($data);
            }
        } catch (\Exception $exception) {
            $this->sendEmail('发送日志异常', $exception->getMessage());
        }

    }

    /**
     * 发日志到webScoket
     * @param $data
     * @return mixed|string
     */
    private function _sendLogWebScoket($data)
    {
        $data = date('Y-m-d H:i:s') . ' ' . $data;
        //return my_curl("http://39.106.231.36:10181/api/sendMsg",["content"=>$data,"room"=>"1"], '', 2, 2);
        /*
                $config = config('common.web_socket');
                $url = $config['notify_url'];
                $app_id = $config['app_id'];
                $app_secret = $config['app_secret'];
                $room_id = $config['room_id'];
                $time = time();

                $sign = md5($app_id . $room_id . $data . $app_secret . $time);//bc0d6fff2deae5399693380a0d92c590
                $fields = [
                    'appid' => $app_id,
                    'room_id' => $room_id,
                    'data' => $data,
                    'sign' => $sign,
                    'time' => $time
                ];
                $res = my_curl($url, $fields, '', 1, 1);*/
    }

    private function _sendLogRedis($data)
    {
        $data = date('Y-m-d H:i:s') . '  ' . $data;
        $key = "apim:send_log";
        if (Redis::LLEN($key) < 1000) {
            Redis::LPUSH($key, $data);
        }
        return true;
    }


}