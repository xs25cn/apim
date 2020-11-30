<?php
/**
 *
 * @filename  SystemLog.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/1/23 10:12
 * @version   $Id$
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redis;

class SystemLogController extends Controller
{
    public function index()
    {
        if (request('iframe')) {
            header('X-Accel-Buffering: no'); // 关键是加了这一行。
            #设置执行时间不限时
            set_time_limit(0);
            ini_set('default_socket_timeout', -1);
            #清除并关闭缓冲，输出到浏览器之前使用这个函数。
            ob_end_clean();
            #控制隐式缓冲泻出，默认off，打开时，对每个 print/echo 或者输出命令的结果都发送到浏览器。
            ob_implicit_flush(1);
            echo '<script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>';
            echo '<div class="scorpall" style="margin-bottom:60px;color: #fff;background-color: #000000;font-size: 14px"></div>';
            $redis = new \Predis\Client(config('database.redis.default') + array('read_write_timeout' => 0));
            $pubsub = $redis->pubSubLoop();
            $pubsub->subscribe('apim:send_log');
            foreach ($pubsub as $message) {
                if ($message->channel == 'apim:send_log' && $message->kind == 'message') {
                    if ($message->payload == 'stop') {
                        $this->pushDivContent('.scorpall', 'Aborting pubsub loop...');
                        $pubsub->unsubscribe();
                    } else {
                        $this->pushDivContent('.scorpall', $message->payload);
                    }
                }
            }
        } elseif (request('get_log')) {
            return ['code' => 1, 'msg' => Redis::RPOP("apim:send_log")];
        } else {
            return $this->view();
        }
    }


    private function pushDivContent($div, $message)
    {
        $message = str_repeat(" ", 1024) . $message . '<br />';
        echo '<script>
		var h = $(".scorpall")[0].offsetHeight;
		$("body").scrollTop(h); 
		$("' . $div . '").append("' . $message . '")
	    </script>';
        ob_flush();
        flush();
    }

}