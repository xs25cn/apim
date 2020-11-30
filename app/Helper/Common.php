<?php

namespace App\Library;

/**
 * 公用方法类
 */

use App\Services\RabbitmqService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use PHPMailer\PHPMailer\PHPMailer;

class Common
{

    /**
     * curl请求
     * @author DuZhenxun <5552123@qq.com>
     * @param $url
     * @param null $post_fields
     * @param string $headers
     * @param int $read_timeout
     * @param int $connect_timeout
     * @return mixed|string
     */
    public static function myCurl($url, $post_fields = null, $headers = '', $read_timeout = 30, $connect_timeout = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($read_timeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $read_timeout);
        }
        if ($connect_timeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
        }

        //https
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        //post请求
        if (is_array($post_fields) && 0 < count($post_fields)) {
            $post_string = "";
            $post_multipart = false;
            foreach ($post_fields as $k => $v) {
                if ("@" != substr($v, 0, 1)) {
                    //判断是不是文件上传
                    $post_string .= "$k=" . urlencode($v) . "&";
                } else {
                    //文件上传用multipart/form-data，否则用www-form-urlencoded
                    $post_multipart = true;
                    if (class_exists('\CURLFile')) {
                        $post_fields[$k] = new \CURLFile(substr($v, 1));
                    }
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($post_multipart) {
                if (class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    if (defined('CURLOPT_SAFE_UPLOAD')) {
                        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($post_string, 0, -1));
            }
        } else {
            //发送整个body
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        $reponse = curl_exec($ch);
        //错误信息
        if (curl_errno($ch)) {
            $reponse = curl_error($ch);
        }
        curl_close($ch);
        return $reponse;
    }


    /**
     * 微信通知
     * @author DuZhenxun <5552123@qq.com>
     * @param string $msg1 异常现像
     * @param string $msg2 异常影响
     * @param string $msg3 详细信息
     * @param string $msg4 备注信息
     * @param string $to 接收信息账号(先关注微信公众账号)
     *
     * @return bool
     */
    public static function sendWeiXin($msg1 = '', $msg2 = '', $msg3 = '', $msg4 = '', $to = '')
    {
        if (!$to) {
            $to = config('common.alert_weixin');
        }
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

    /**
     * 发到webScoket
     * @author DuZhenxun <5552123@qq.com>
     * @param $data
     * @return mixed|string
     */
    public static function sendWebScoket($data)
    {
        $data = date('Y-m-d H:i:s') . ' ' . $data;
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
        return self::myCurl($url, $fields, '', 1, 1);
    }


    /**
     * 格式化时间戳
     * @param $time
     * @return string
     */
    public static function formatDate($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v;
            }
        }
        return '';
    }


    /**
     * 多维组处理
     * SuperCollection->hasOne() 后多维数组处理
     * @param $data
     * @return mixed
     */
    public static function arrayFilter($data)
    {
        //重新组装成二维数组
        foreach ($data as $k => &$v) {
            foreach ($v as $kk => $vv) {
                if (is_array($vv)) {
                    if (count($vv) > 1) {
                        array_shift($vv);
                    }
                    $v += $vv;
                    unset($v[$kk]);
                }
            }
        }
        unset($v);
        return $data;
    }

    /**
     * sql条件
     * @param $condition
     * $condition['and']
     * $condition['in']
     * $condition['or']
     * @return \Closure
     */
    public static function sqlWhere($condition)
    {
        /**
         * @param \Illuminate\Database\Query\Builder $q
         */
        return function ($q) use ($condition) {
            if (!empty($condition['and'])) {
                $q->where($condition['and']);
            }
            if (!empty($condition['in'])) {
                foreach ($condition['in'] as $k => $v) {
                    $q->whereIn($v[0], $v[1]);
                }
            }
            if (!empty($condition['or'])) {
                foreach ($condition['or'] as $k => $v) {
                    $q->where(
                        function (Builder $q2) use ($k, $v) {
                            $q2->where($v['where'][0], $v['where'][1], $v['where'][2])
                                ->orWhere($v['orWhere'][0], $v['orWhere'][1], $v['orWhere'][2]);
                        }
                    );
                }
            }
        };
    }


}
