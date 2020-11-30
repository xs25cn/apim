<?php
/**
 * 自定义函数库
 * @filename  helpers.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-8-9 17:08:32
 */


/**
 * Models类 快捷函数
 * @param $classname
 * @param string $path
 * @return mixed
 * @throws Exception
 * @return \Illuminate\Database\Eloquent\Model
 */
function M($classname, $path = 'Models')
{
    return load_class($classname, $path);
}


/**
 * 服务类快捷函数
 * @param $classname
 * @param string $path
 * @return \App\Services\Service;
 */
function S($classname, $path = 'Services')
{
    return load_class($classname . 'Service', $path);
}


/**
 * 加载类,单例模式实例化
 * @param $classname
 * @param $path
 * @return mixed
 * @throws Exception
 */
function load_class($classname, $path)
{
    $classname = ucfirst($classname);
    $class = "\\App\\" . $path . "\\" . $classname;
    if (!class_exists($class)) {
        throw new \Exception('找不到文件' . $class);
    }
    static $classes = [];
    $key = md5($class);
    if (!isset($classes[$key])) {
        //$classes[$key] = (new ReflectionClass($class))->newInstance();
        $classes[$key] = new $class;
    }
    return $classes[$key];
}


/**
 * 数组转树
 * @param $list
 * @param int $root
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @return array
 */
function list_to_tree($list, $root = 0, $pk = 'id', $pid = 'parentid', $child = '_child')
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = 0;
            if (isset($data[$pid])) {
                $parentId = $data[$pid];
            }
            if ((string)$root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

function node_tree($arr, $id = 0, $level = 0)
{
    static $array = array();
    foreach ($arr as $v) {
        if ($v['parentid'] == $id) {
            $v['level'] = $level;
            $array[] = $v;
            node_tree($arr, $v['id'], $level + 1);
        }
    }
    return $array;
}


function arr2str($arr, $str = '')
{

    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                return arr2str($v, $str);
            } else {
                $str .= "<p>{$k}-->{$v}</p>";
            }
        }
    }
    return $str;
}


/**
 * curl请求
 * @param $url
 * @param null $post_fields
 * @param string $headers
 * @param int $read_timeout
 * @param int $connect_timeout
 * @param string $referer
 * @return mixed|string
 */
function my_curl($url, $post_fields = null, $headers = '', $read_timeout = 30, $connect_timeout = 30, $referer = 'apim.xin.com')
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
    if ($referer) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
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
            if ("@file@" != substr($v, 0, 6)) {
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
        //CURL失败
        if (is_array($post_fields)) {
            $post_fields = json_encode($post_fields, 256 | 512);
        }
        $content = 'url:' . $url . ',请求:' . $post_fields . ',响应:' . $reponse;
        Log::error('curl失败:' . $content);
        \App\Services\NoticeService::getInstance()->sendEmail('curl失败', $content);
    }
    curl_close($ch);
    return $reponse;
}


/**
 * 批量curl请求
 * @param array $curl_data
 * @param int $read_timeout
 * @param int $connect_timeout
 * @return array
 */
function my_curl_multi($curl_data, $read_timeout = 30, $connect_timeout = 30)
{
    //加入子curl
    $mh = curl_multi_init();
    $curl_array = array();

    foreach ($curl_data as $k => $info) {
        $curl_array[$k] = curl_init($info['url']);
        curl_setopt($curl_array[$k], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_array[$k], CURLOPT_HEADER, 0);

        if ($read_timeout) {
            curl_setopt($curl_array[$k], CURLOPT_TIMEOUT, $read_timeout);
        }
        if ($connect_timeout) {
            curl_setopt($curl_array[$k], CURLOPT_CONNECTTIMEOUT, $connect_timeout);
        }

        if (!empty($info['headers'])) {
            curl_setopt($curl_array[$k], CURLOPT_HTTPHEADER, $info['headers']);
        }
        //发送整个body
        if (!empty($info['post_fields'])) {
            curl_setopt($curl_array[$k], CURLOPT_POSTFIELDS, $info['post_fields']);
        }

        curl_multi_add_handle($mh, $curl_array[$k]);
    }


    //执行curl
    $running = null;
    do {
        $mrc = curl_multi_exec($mh, $running);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);


    while ($running && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) == -1) {
            usleep(100);
        }
        do {
            $mrc = curl_multi_exec($mh, $running);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }

    //获取执行结果
    $response = [];
    foreach ($curl_array as $key => $val) {
        $response[$key] = curl_multi_getcontent($val);
    }

    //关闭子curl
    foreach ($curl_data as $key => $val) {
        curl_multi_remove_handle($mh, $curl_array[$key]);
    }

    //关闭父curl
    curl_multi_close($mh);

    return $response;
}


/**
 * 发邮件
 * @param string $subject 主题
 * @param string $str 内容
 * @param string $to 收件人 多人以 逗号 分隔
 * @param string $attach 附件 多个以 逗号 分隔
 * @param string $blade 模板
 * @param string $send_name 发件人 1系统,2客服
 */
function send_mail($subject, $str = '', $to = '', $attach = '', $blade = '', $send_name = 1)
{
    if (!$to) {
        $to_arr = explode(',', config('mail.to_email'));
    } else {
        if (is_array($to)) {
            $to_arr = $to;
        } else {
            $to_arr = explode(',', $to);
        }
    }

    if ($attach) {
        if (is_array($attach)) {
            $attach_arr = $attach;
        } else {
            $attach_arr = explode(',', $attach);
        }
    }
    if (!$blade) {
        $blade = 'emails.mail';
    }
    if ($send_name == 2) {
        //使用客服邮件
        config(['mail.from.address' => env('MAIL_USERNAME2')]);
        config(['mail.from.name' => env('MAIL_FROM_NAME2')]);
        config(['mail.username' => env('MAIL_USERNAME2')]);
        config(['mail.password' => env('MAIL_PASSWORD2')]);
    }
    \Mail::send($blade, ['str' => $str], function ($message) use ($subject, $to_arr, $attach_arr) {
        $message->subject($subject);
        foreach ($to_arr as $mail) {
            $message->to($mail);
        }

        if (is_array($attach_arr) && count($attach_arr) > 0) {
            foreach ($attach_arr as $file) {
                $message->attach($file);
            }
        }
    });
}

//获取参数链接
function getParams($arr, $key, $params = '')
{
    if (!$params) {
        $params = request()->all();
    }
    foreach ($arr as $k => $v) {
        if (isset($params[$key])) {
            unset($params[$key]);
        }
        $arr[$k] = ['name' => $v['name'], 'val' => $v['val'], 'url' => http_build_query(array_merge([$key => $v['val']], $params))];
    }
    return $arr;
}


/**
 * 数组排序，按指定的KEY
 * @param array $array 要排序的2维数组
 * @param string $key 要排序的key
 * @param string $orderBy asc从小到大，desc从大到小
 * @return mixed
 */
function sort2array($array, $key, $orderBy = 'asc')
{
    usort($array, function ($a, $b) use ($key, $orderBy) {
        return $orderBy == 'asc' ? strnatcmp($a[$key], $b[$key]) : strnatcmp($b[$key], $a[$key]);
    });
    return $array;
}

/**
 * 保存远程图片
 * @param string $url
 * @param string $filePath
 * @param string $fileName
 * @return string
 */
function save_http_file($url, $filePath, $fileName)
{

    $up_file_path = config('app.file_path') . $filePath;

    if (!file_exists(public_path() . '/' . $up_file_path)) {
        mkdir(public_path() . '/' . $up_file_path, 0755, true);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    file_put_contents(public_path() . '/' . $up_file_path . '/' . $fileName, curl_exec($ch));
    curl_close($ch);
    return $filePath . '/' . $fileName;
}


/**
 * 时间转换
 * @param $time
 * @return bool|string
 */
function sec2time($time)
{
    $str = '';
    if ($time >= 31556926) {
        $str .= floor($time / 31556926) . "年";
        $time = ($time % 31556926);
    }
    if ($time >= 86400) {
        $str .= floor($time / 86400) . "天";
        $time = ($time % 86400);
    }
    if ($time >= 3600) {
        $str .= floor($time / 3600) . "小时";
        $time = ($time % 3600);
    }
    if ($time >= 60) {
        $str .= floor($time / 60) . "分";
        $time = ($time % 60);
    }
    $str .= floor($time) . "秒";
    return $str;

}




