<?php
/**
 * ApiUrl
 * @filename  ApiUrlService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/8 16:18
 */

namespace App\Services;

use App\Jobs\AsyncJob;
use App\Models\AdminUser;
use App\Models\ApiAlert;
use App\Models\ApiDomain;
use App\Models\ApiDomainAdminUser;
use App\Models\ApiResponseTime;
use App\Models\ApiUrl;
use GuzzleHttp\Client;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use IpTools\IpArea;

class ApiUrlService extends Service
{

    /**
     * 保存 域名下的 url
     * @param string $api_domain_id
     * @param string $prefix
     * @param $start_time
     * @param $end_time
     * @return array
     */
    public function syncDomainApiUrl($api_domain_id = '', $prefix = '', $start_time = '', $end_time = '')
    {
        if (!$start_time) {
            $start_time = time() - 60 * 60 * 24;
        }
        if (!$end_time) {
            $end_time = time();
        }
        //通过 url_Id 查出接口相关信息
        $info = ApiDomain::query()->where('id', $api_domain_id)->first();
        if (!$info) {
            return ['code' => 0, 'msg' => '域名不存在'];
        }

        $domain = $info->domain;
        if ($prefix) {
            $prefix = '/' . trim(trim($prefix), '/');
        } else {

            $prefix = '/';
        }

        $t1 = microtime(true);
        $esService = EsService::getInstance();
        $post_data = $esService->getDomainApiUrlJson($domain, $prefix, $start_time, $end_time);
        $es_url = $esService->getEsDomain() . '/' . $esService->getEsIndex($info->es_index) . '/' . $esService->getEsType() . '/_search';
        $res = my_curl($es_url, $post_data, ["content-type: application/json; charset=UTF-8"], 180, 60);
        $results = json_decode($res, true);

        //对返回的信息做判断,是否超时,查取不到信息等
        if (empty($results['aggregations']['res']['buckets'])) {
            return ['code' => '-1', 'msg' => '无数据', 'data' => $results];
        }

        $curl_time = round(microtime(true) - $t1, 3);
        $str = '前缀: ' . $domain . $prefix . ',获取数据时长: ' . $curl_time . ' 秒,命中记录数: ' . $results['hits']['total'];
        NoticeService::getInstance()->sendLog($str);
        $res = $results['aggregations']['res']['buckets'];

        //清洗 URL地址
        $tmp_url = [];
        foreach ($res as $k => $v) {
            //只保留请求量大于1的记录
            if ($v['doc_count'] < 1) {
                continue;
            }
            $key = trim($v['key'], '/');
            //过滤 .*地址
            if (strstr($key, '.')) {
                continue;
            }
            $url = explode('?', $key)[0];

            //过滤%,空,null
            if (strstr($url, '%') || empty($url) || $key == 'null') {
                continue;
            }
            $tmp_url[] = $url;
            unset($url);
        }
        $url_data = array_unique($tmp_url);

        if (count($url_data)) {
            foreach ($url_data as $k => $url) {
                //查询是否已存在
                $url = '/' . trim($url, '/');
                $id = ApiUrl::query()->where([['api_domain_id', '=', $api_domain_id], ['url', '=', $url]])->value('id');
                if ($id) {
                    continue;
                }
                $data = [];
                $data['api_domain_id'] = $api_domain_id;
                $data['url'] = $url;
                $data['title'] = '';
                $data['api_module_id'] = 0;
                $data['code_alert'] = '500,502,504';
                ApiUrl::query()->create($data);
                unset($data);
            }
            //修改最后更新时间
            ApiDomain::query()->where('id', $api_domain_id)->update(['sync_at' => time()]);
        }

    }


    /**
     * 同步接口响应时间数据
     * @param string $api_url_id
     * @param string $api_domain_id
     * @param string $start_time
     * @param string $end_time
     * @throws \Exception
     */
    public function syncApiResponseTime($api_url_id = '', $api_domain_id = '', $start_time = '', $end_time = '')
    {

        //=========== 查出本次要抓的数据 start ===============
        $system_config = SiteService::getInstance()->getSetting();//系统配置

        $time_hist = $system_config['es_request_time_hist'][0];
        $first_start_time = $system_config['es_first_start_time'][0];

        //只取整10分钟
        $tmp_end_time = time() - $system_config['es_end_time'][0];
        if (!$end_time) {
            $end_time = date('Y-m-d H:' . intval(date('i', $tmp_end_time) / 10) . '0:00', $tmp_end_time);
        }

        //查出符合条件的 api_url
        $where = [];
        $where[] = ['t1.status', 1];
        $where[] = ['t2.status', 1];
        if ($api_url_id) {
            $where[] = ['t1.id', $api_url_id];
        }
        if (empty($api_url_id) && !empty($api_domain_id)) {
            $where[] = ['t1.api_domain_id', $api_domain_id];
        }

        $lists = ApiUrl::query()->from('api_url as t1')
            ->select('t1.id as api_url_id', 't1.response_time_alert', 't1.time_alert_type', 't1.time_alert_total', 't1.api_domain_id', 't1.title as url_title', 't1.url', 't2.title as domain_title', 't2.domain')
            ->leftJoin('api_domain as t2', 't1.api_domain_id', '=', 't2.id')
            ->where($where)
            ->orderBy('t1.id', 'desc')
            ->get()
            ->toArray();
        //代码优化
        $api_url_ids = array_column($lists, 'api_url_id');
        $api_url_timestamp = ApiResponseTime::query()
            ->select(\DB::raw('MAX(timestamp) as timestamp,api_url_id'))
            ->whereIn('api_url_id', $api_url_ids)
            ->groupBy('api_url_id')
            ->get()
            ->keyBy('api_url_id')
            ->toArray();

        foreach ($lists as $k => $v) {
            if ($start_time) {
                $lists[$k]['start_time'] = strtotime($start_time);
            } else {
                if (!empty($api_url_timestamp[$v['api_url_id']]['timestamp'])) {
                    $lists[$k]['start_time'] = strtotime($api_url_timestamp[$v['api_url_id']]['timestamp']) + $system_config['es_end_time'][0];
                    $diff_time = time() - $lists[$k]['start_time'];
                    //距离上次采集时间大于7天，将从配置前取前N天进行采集
                    if ($diff_time > 86400 * 7) {
                        $lists[$k]['start_time'] = strtotime(date('Y-m-d 00:00:00', strtotime($first_start_time . " days")));
                    }
                } else {
                    //没有数据时查N天内的,从0点开始
                    $lists[$k]['start_time'] = strtotime(date('Y-m-d 00:00:00', strtotime($first_start_time . " days")));
                }
            }
            //结束时间
            $lists[$k]['end_time'] = strtotime($end_time);
            //时间分组
            $lists[$k]['time_hist'] = $time_hist;
            if ($lists[$k]['end_time'] == $lists[$k]['start_time'] && $lists[$k]['end_time'] < $lists[$k]['start_time']) {
                unset($lists[$k]);
            }
        }

        //=========== 查出本次要抓的数据 end =====================
        NoticeService::getInstance()->sendLog('.............................................................................................................................................................', $system_config);

        $process_total = (int)$system_config['process_max'][0] ?: 1;//进程数
        if (function_exists('pcntl_fork') && php_sapi_name() == 'cli' && count($lists) > 1 && $process_total > 1) {
            //多进程处理
            $this->pcntlWork($lists, $system_config, $process_total);
        } else {
            $this->getApiResponseTime($lists, $system_config);
        }
        //报警
        $queue_name = $system_config['queue_api_alert'][0];
        if ($queue_name) {
            AsyncJob::dispatch('ApiUrlService', 'apiResponseNotice', [$system_config])->onQueue($queue_name);
        } else {
            ApiUrlService::getInstance()->apiResponseNotice($system_config);
        }
    }

    /**
     * 多进程抓取
     * @param $lists
     * @param $system_config
     * @param $process_total
     * @throws \Exception
     */
    public function pcntlWork(array $lists, array $system_config, int $process_total)
    {
        $s_time = time();
        $total = count($lists);//一共要抓取的条数
        $page_total = (int)ceil($total / $process_total);//每个进程抓去数据量(每页总数)
        $api_url_data = array_chunk($lists, $page_total);//生成N份,每份$page_total条,进程数改为N

        $str = '<b>本次需要分析' . $total . ' 个接口。使用' . count($api_url_data) . ' 个子进程，每个进程处理 ' . $page_total . ' 个接口! </b>';
        NoticeService::getInstance()->sendLog($str, $system_config);
        //启动多个子进程
        $master_pid = posix_getpid(); //当前主进程ID
        $childs = [];//子进程
        foreach ($api_url_data as $child => $data) {
            $pid = pcntl_fork();
            if ($pid === -1) {
                exit;
            }
            if ($pid) {
                $childs[] = $pid;
            } else {
                $child_pid = posix_getpid(); //子进程
                $child_process = $child + 1 . ' (' . $child_pid . ')';
                //将pid保存到redis中
                $redis = RedisService::connection();
                $redis->hSet(config('common.pid_key'), $child_pid, json_encode(['time' => time(), 'child_pid' => $child_process, 'pid' => $master_pid]));
                $redis->close();

                //子进程获取数据开始
                $this->getApiResponseTime($data, $system_config, $child_process);

                //子进程获取数据结束
                $str = "------子进程{$child_process} 任务完成,共 " . count($data) . " 个地址,用时 " . (time() - $s_time) . ' 秒 ------';
                NoticeService::getInstance()->sendLog($str, $system_config);
                //redis 中删除
                $redis = RedisService::connection();
                $redis->hDel(config('common.pid_key'), $child_pid);//删除redis
                $redis->close();
                exit();
            }
        }

        //============= 监控子进程 start =============
        while (pcntl_waitpid(0, $status) != -1) {
            $status = pcntl_wexitstatus($status);
        }
        //============= 监控子进程 end ================

        $str = '<b>(*^__^*) 本次抓取全部完成!!用时:' . sec2time((time() - $s_time)) . ' ，共计:' . $total . ' 个任务，子进程:' . count($api_url_data) . ' 个，每个子进程处理任务:' . $page_total . ' 个 !</b>';
        NoticeService::getInstance()->sendLog($str, $system_config);

    }

    /**
     * 抓取数据并保存
     * @param array $lists
     * @param array $system_config
     * @param int $child_process
     * @throws \Exception
     */
    public function getApiResponseTime($lists, $system_config, $child_process = 0)
    {
        $esService = EsService::getInstance();

        foreach ($lists as $api_info) {
            //请求地址
            $es_url = $esService->getEsDomain() . '/' . $esService->getEsIndex($api_info['es_index']) . '/'.$esService->getEsType().'/_search';
            //使用curl获取一次信息
            $post_data = $esService->getApiResponseTimeJson($api_info['domain'], $api_info['url'], $api_info['time_hist'], $api_info['start_time'], $api_info['end_time']);

            $res = my_curl($es_url, $post_data, ["content-type: application/json; charset=UTF-8"], 180, 60);
            //dump($res);exit;
            $results = $esService->formatApiResponseTime($res);

            if (!$results) {
                continue;
            }

            //curl批量获取 不同响应时间的请求数量
            $curls_data = [
                'total_1' => ['request_time_gt' => 0, 'request_time_lt' => 1],//0秒到1秒
                'total_2' => ['request_time_gt' => 1, 'request_time_lt' => 5],//1秒到5秒
                'total_3' => ['request_time_gt' => 5, 'request_time_lt' => 10],
                'total_4' => ['request_time_gt' => 10, 'request_time_lt' => 200],
                'time_alert_total' => ['request_time_gt' => $api_info['response_time_alert'], 'request_time_lt' => 1000],
            ];

            foreach ($curls_data as $k => $v) {
                $curls_data[$k]['url'] = $es_url;
                $curls_data[$k]['post_fields'] = $esService->getApiResponseTimeJson($api_info['domain'], $api_info['url'], $api_info['time_hist'], $api_info['start_time'], $api_info['end_time'], $v['request_time_gt'], $v['request_time_lt']);
                $curls_data[$k]['headers'] = ["content-type: application/json; charset=UTF-8"];
            }
            //批量curl
            $multi_arr = my_curl_multi($curls_data, 180, 60);
            foreach ($multi_arr as $k => $v) {
                $multi_arr[$k] = $esService->formatApiResponseTime($v);
            }

            //将不同响应时间数量组合起来
            foreach ($results as $k => $v) {
                foreach ($curls_data as $kk => $vv) {
                    if (!empty($multi_arr[$kk][$k])) {
                        $results[$k][$kk] = $multi_arr[$kk][$k]['total'];
                    } else {
                        $results[$k][$kk] = 0;
                    }
                }
                //code 值
                $results[$k]['code'] = array_column($v['code'], 'doc_count', 'key');
            }
            //保存数据同步或异步到数据库中
            $queue_name = $system_config['save_api_response_time'][0];
            if ($queue_name) {
                AsyncJob::dispatch('ApiUrlService', 'saveApiResponseTime', [$results, $api_info['api_domain_id'], $api_info['api_url_id'], $system_config])->onQueue($queue_name);
            } else {
                $this->saveApiResponseTime($results, $api_info['api_domain_id'], $api_info['api_url_id'], $system_config);
            }
            $str = "子进程：" . $child_process . " , 抓取接口: " . $api_info['domain'] . $api_info['url'] . " 完成, 共: " . count($results) . " 个结果集......";
            NoticeService::getInstance()->sendLog($str, $system_config);

            //删除无用变量
            unset($results, $multi_arr, $curls_data);

            if (count($lists) > 1) {
                usleep(300000);//抓取完休息0.3秒
            }
        }

    }

    /**
     * 保存响应时长数据
     * @param $results
     * @param $api_domain_id
     * @param $api_url_id
     * @param $system_config
     * @return void
     * @throws \Exception
     */
    public function saveApiResponseTime($results, $api_domain_id, $api_url_id, $system_config)
    {
        //NoticeService::getInstance()->sendLog("数据保存: {$api_domain_id}_{$api_url_id}",$system_config);
        //接口信息
        $api_url_info = ApiUrl::from('api_url as t1')
            ->select('t1.id', 't1.title as url_title', 't1.time_alert_type', 't1.api_domain_id', 't1.time_alert_total', 't1.url', 't2.domain', 't2.title as domain_title', 't1.response_time_alert', 't1.code_alert')
            ->leftJoin('api_domain as t2', 't1.api_domain_id', '=', 't2.id')
            ->where('t1.id', $api_url_id)
            ->first();

        //组装要插入的数据
        $data = [];
        $content = [];
        $alert_code = [];
        foreach ($results as $k => $v) {
            $data[$k]['timestamp'] = $v['timestamp'];
            $data[$k]['total'] = $v['total'];
            $data[$k]['min'] = number_format($v['min'], 3);
            $data[$k]['max'] = number_format($v['max'], 3);
            $data[$k]['avg'] = number_format($v['avg'], 3);
            $data[$k]['total_1'] = $v['total_1'];
            $data[$k]['total_2'] = $v['total_2'];
            $data[$k]['total_3'] = $v['total_3'];
            $data[$k]['total_4'] = $v['total_4'];
            $data[$k]['time_alert_total'] = $v['time_alert_total'];
            $data[$k]['api_domain_id'] = $api_domain_id;
            $data[$k]['api_url_id'] = $api_url_id;
            $data[$k]['created_at'] = time();
            $data[$k]['code_200'] = 0;
            $data[$k]['code_3xx'] = 0;//3xx
            $data[$k]['code_4xx'] = 0;//4xx
            $data[$k]['code_499'] = 0;//499
            $data[$k]['code_500'] = 0;
            $data[$k]['code_502'] = 0;
            $data[$k]['code_504'] = 0;
            $data[$k]['code_5xx'] = 0;
            //code 处理
            foreach ($v['code'] as $kk => $vv) {
                $first = substr($kk, 0, 1);
                if ($first == 2) {
                    if ($kk == 200) {
                        $data[$k]['code_200'] = $vv;
                    }
                } elseif ($first == 3) {
                    $data[$k]['code_3xx'] = $data[$k]['code_3xx'] + $vv;
                } elseif ($first == 4) {
                    if ($kk == 499) {
                        $data[$k]['code_499'] = $vv;//499
                    } else {
                        $data[$k]['code_4xx'] = $data[$k]['code_4xx'] + $vv;
                    }
                } elseif ($first == 5) {
                    if ($kk == 500) {
                        $data[$k]['code_500'] = $vv;
                    } elseif ($kk == 502) {
                        $data[$k]['code_502'] = $vv;
                    } elseif ($kk == 504) {
                        $data[$k]['code_504'] = $vv;
                    } else {
                        $data[$k]['code_5xx'] = $data[$k]['code_5xx'] + $vv;
                    }
                }

                //code值符合条件报警
                if ($api_url_info->code_alert) {
                    $code_alert_arr = explode(',', $api_url_info->code_alert);
                    if (in_array($kk, array_keys(M('ApiUrl')->code_alert_arr)) && in_array($kk, $code_alert_arr)) {
                        $alert_code[] = ['code' => $kk, 'total' => $vv, 'timestamp' => $v['timestamp']];
                    }
                }

            }

            //响应时间过大记录信息
            if ($api_url_info->response_time_alert && $api_url_info->time_alert_total) {
                if ($api_url_info->time_alert_type == 2) {
                    //百分比
                    $time_alert_total = round($v['time_alert_total'] / $v['total'] * 100, 0);
                } else {
                    //数量
                    $time_alert_total = $v['time_alert_total'];
                }
                //对比超预设阀值
                if ($time_alert_total >= $api_url_info->time_alert_total) {
                    $api_alert['api_domain_id'] = $api_domain_id;
                    $api_alert['api_url_id'] = $api_url_id;
                    $api_alert['type'] = 1;
                    $api_alert['timestamp'] = $v['timestamp'];
                    $api_alert['total'] = $v['total'];//总量
                    $api_alert['over_total'] = $v['time_alert_total'];//超阀值数量
                    $api_alert['max'] = $data[$k]['max'];//最大
                    $content[] = $api_alert;
                    unset($api_alert);
                }
            }

        }

        //批量插入数据
        $datas = array_chunk($data, 20);
        foreach ($datas as $val) {
            $res = ApiResponseTime::query()->insert($val);
            //最新同步时间
            if ($res) {
                ApiUrl::where('id', $api_url_id)->update(['sync_at' => time()]);
            }
        }

        //有报警信息，超时
        if (!empty($content)) {
            foreach ($content as $v) {
                ApiAlert::query()->insert($v);
            }
        }

        //状态码异常马上报警
        if (count($alert_code) && !empty($system_config['alert_type'][0])) {
            $this->apiCodeNotice($api_url_info, $alert_code, $system_config);
        }

    }

    /**
     * 报警通知
     * @param string $system_config
     * @return bool
     * @throws \Exception
     */
    public function apiResponseNotice($system_config = '')
    {
        //系统报警设置
        if (!$system_config) {
            $system_config = SiteService::getInstance()->getSetting();
        }
        //报警通知
        if (empty($system_config['alert_type'])) {
            return false;
        }

        $system_alert_type = explode(',', $system_config['alert_type'][0]);

        //先查看是否有需要报警的信息
        if (!M('ApiAlert')->where('status', 1)->count()) {
            return false;
        }

        //要接收报警人管理员与报警id
        $user_lists = M('AdminUser')
            ->select('id as admin_user_id', 'name', 'email', 'realname', 'setting')
            ->where([['status', 1], ['setting', '<>', '']])
            ->get()
            ->toArray();

        if (empty($user_lists)) {
            return false;
        }
        $username = '';
        foreach ($user_lists as $k => $user_info) {
            //查看此人是否需要接收报警
            $user_setting = json_decode($user_info['setting'], true);
            if (empty($user_setting['alert_overtime'])) {
                //不做任务报警
                continue;
            }
            //组装要报警的内容
            $tmp_lists = ApiAlert::query()->select('t1.id', 't1.timestamp', 't1.over_total', 't1.total', 't1.max', 't1.api_domain_id', 't1.api_url_id', 't1.type', 't1.code', 't2.title as api_domain_title', 't2.domain as api_domain', 't3.title as api_url_title', 't3.url as api_url', 't3.response_time_alert', 't3.time_alert_type')
                ->from('api_alert as t1')
                ->leftJoin('api_domain as t2', 't2.id', '=', 't1.api_domain_id')
                ->leftJoin('api_url as t3', 't3.id', '=', 't1.api_url_id')
                ->leftJoin('api_domain_admin_user as t4', 't4.api_domain_id', '=', 't1.api_domain_id')
                ->where([['t1.status', 1], ['t4.admin_user_id', $user_info['admin_user_id']]])
                ->orderBy('t1.timestamp', 'asc')
                ->get()
                ->toArray();
            if (!count($tmp_lists)) {
                continue;
            }
            //组合成多维数组 项目--接口--请求时间--数据
            $api_alert_lists = [];
            foreach ($tmp_lists as $info) {
                $api_alert_lists[$info['api_domain_title'] . '|' . $info['api_domain'] . '|' . $info['api_domain_id']][$info['api_url_title'] . '|' . $info['api_url'] . '|' . $info['api_url_id']][] = $info;
            }
            $user_alert_overtime = explode(',', $user_setting['alert_overtime']);

            $no_alert_time = array_filter(explode(',', $user_setting['no_alert_time']));

            //只有在报警时间段内才报警
            if (empty($no_alert_time) || !in_array(date('H'), $no_alert_time)) {
                //微信报警
                if (in_array('weixin', $system_alert_type) && in_array('weixin', $user_alert_overtime)) {
                    $content = '';
                    $data = [];
                    $data['userName'] = $user_info['name'];
                    $data['msg1'] = "Api性能监控";
                    $data['msg2'] = '接口响应超过阀值';
                    $data['msg3'] = PHP_EOL . " 共" . count($api_alert_lists) . " 个项目报警";
                    foreach ($api_alert_lists as $k1 => $v1) {
                        $k1_info = explode('|', $k1);
                        $content .= "<font color='red'>项目:{$k1_info[0]}({$k1_info[1]})</font><br>";
                        foreach ($v1 as $k2 => $v2) {
                            $k2_info = explode('|', $k2);
                            $content .= "<font color='#d2691e'> 接口:{$k2_info[0]}<br>{$k1_info[1]}{$k2_info[1]}</font><br>";
                            foreach ($v2 as $v3) {
                                $content .= date('H:i', strtotime($v3['timestamp'])) . " 总量:{$v3['total']}，" . round(($v3['over_total'] / $v3['total'] * 100), 0) . "%({$v3['over_total']}次)超{$v3['response_time_alert']}s，最大:{$v3['max']}s<br>";
                            }
                            $content .= "<br>";
                        }
                        $content .= "<br>";
                    }
                    $data['msg4'] = $content;
                    //同步异步
                    $queue_name = $system_config['queue_weixin'][0];
                    if ($queue_name) {
                        AsyncJob::dispatch('NoticeService', 'sendWeiXin', [$data['msg1'], $data['msg2'], $data['msg3'], $data['msg4'], $data['userName']])->onQueue($queue_name);
                    } else {
                        NoticeService::getInstance()->sendWeiXin($data['msg1'], $data['msg2'], $data['msg3'], $data['msg4'], $data['userName']);
                    }
                }

                $username .= $user_info['realname'] . ',';
            }

            //邮件报警
            if (in_array('email', $user_alert_overtime) && in_array('email', $system_alert_type)) {
                $subject = "Api响应时间超过预设阀值";
                $content = "Hi{$user_info['realname']},你好<br><br>";
                $content .= "API性能监控中有" . count(($api_alert_lists)) . "个项目触发了报警阀值，具体数据如下<br>";
                foreach ($api_alert_lists as $k1 => $v1) {
                    $k1_info = explode('|', $k1);
                    $content .= "<h3>{$k1_info[0]}({$k1_info[1]})</h3>";
                    $content .= "<table border='1px' cellspacing='0px'>";
                    foreach ($v1 as $k2 => $v2) {
                        $k2_info = explode('|', $k2);
                        $content .= "<tr bgcolor='#ffffcc'><td colspan='5'> <a href='" . env('APP_URL') . "admin/apiResponseTime/index?api_url_id={$k2_info[2]}'>[{$k2_info[2]}] {$k2_info[0]}( {$k1_info[1]}{$k2_info[1]} )</a> </td></tr>";
                        $content .= "<tr><td>时间</td><td>总量</td><td>超出</td><td>最大响应</td><td>查看</td></tr>";
                        foreach ($v2 as $k3 => $v3) {
                            $content .= "
                                            <tr>
                                                <td> {$v3['timestamp']} </td>
                                                <td> {$v3['total']} </td>
                                                <td> " . round(($v3['over_total'] / $v3['total'] * 100), 0) . "%({$v3['over_total']}次) 超 {$v3['response_time_alert']}s </td>
                                                <td> {$v3['max']}s </td>
                                                <td> <a href='" . env('APP_URL') . "admin/apiResponseTime/info?domain={$k1_info[1]}&url={$k2_info[1]}&start_time=" . $v3['timestamp'] . "'> 本次详情 </a> </td>
                                            </tr>";
                        }
                    }
                    $content .= "</table>";
                }
                $content .= "<br><h3> API性能监控系统 " . env('APP_URL') . "   技术QQ : 5552123 </h3>";
                $queue_name = $system_config['queue_email'][0];

                if ($queue_name) {
                    AsyncJob::dispatch('NoticeService', 'sendEmail', [$subject, $content, $user_info['email']])->onQueue($queue_name);
                } else {
                    NoticeService::getInstance()->sendEmail($subject, $content, $user_info['email']);
                }
            }

            unset($tmp_lists);
        }

        //报警修改状态
        ApiAlert::query()->where('status', 1)->update(['status' => 2]);

        if (!empty($username)) {
            NoticeService::getInstance()->sendLog('发送报警信息给员工： ' . trim($username, ','), $system_config);
        }
    }


    //状态码报警
    public function apiCodeNotice($api_url_info, $alert_code, $system_config)
    {
        NoticeService::getInstance()->sendLog("状态码报警", $system_config);
        //组装要报警信息的内容
        $content = '';
        if (count($alert_code)) {
            $content .= "Hi,你好<br>";
            $content .= "Api性能监控 发现以下接口状态码异常,请及时关注<br><br>";
            $content .= "{$api_url_info['domain']}{$api_url_info['url']}<br>";
        }
        foreach ($alert_code as $k => $v) {
            $content .= " {$v['timestamp']}，状态码：{$v['code']}，共{$v['total']}次<br>";
        }

        $system_alert_type = explode(',', $system_config['alert_type'][0]);
        $admin_user_ids = ApiDomainAdminUser::query()->where('api_domain_id', $api_url_info['api_domain_id'])->pluck('admin_user_id')->toArray();
        $admin_user_arr = AdminUser::query()->select('realname', 'email', 'name', 'setting')->where('status', 1)->whereIn('id', $admin_user_ids)->get()->toArray();
        if (empty($content)) {
            return false;
        }
        foreach ($admin_user_arr as $user_info) {
            $user_setting = json_decode($user_info['setting'], true);
            if (empty($user_setting['alert_error_code'])) {
                //不做任务报警
                continue;
            }
            $user_alert_code = explode(',', $user_setting['alert_error_code']);

            //任务时间都报警
            $no_alert_time = array_filter(explode(',', $user_setting['no_alert_time']));

            //微信只有在报警时间段内才报警
            if (empty($no_alert_time) || !in_array(date('H'), $no_alert_time)) {
                //微信报警
                if (in_array('weixin', $system_alert_type) && in_array('weixin', $user_alert_code)) {
                    NoticeService::getInstance()->sendWeiXin('Api性能监控', '状态码异常报警', "{$api_url_info['domain']}{$api_url_info['url']}", $content, $user_info['name']);
                }
            }

            //邮件报警 只要有设置,任何时间都报警
            if (in_array('email', $system_alert_type) && in_array('email', $user_alert_code)) {
                $email_subject = "状态码异常报警";
                NoticeService::getInstance()->sendEmail($email_subject, $content, $user_info['email']);
            }


        }
    }


    /**
     * 最近请求
     * @param $domain
     * @param $url
     * @param $start_time
     * @param $size
     * @return array
     */
    public function getApiLastResponse($domain, $url, $start_time, $size)
    {
        $elasticConf = config('elastic');
        $esService = EsService::getInstance();
        if (!preg_match('/^[0-9]{10}$/', $start_time)) {
            $start_time = strtotime(request('start_time'));
        }
        $end_time = $start_time + 60 * 10;
        $post_data = $esService->getApiLastResponseJson($domain, $url, $start_time, $end_time, $size);
        $es_index = ApiDomain::query()->where('domain', $domain)->value('es_index');

        $es_url = $esService->getEsDomain() . '/' . $esService->getEsIndex($es_index) . '/' . $esService->getEsType() . '/_search';
        $res = my_curl($es_url, $post_data, ["content-type: application/json; charset=UTF-8"]);
        $res = json_decode($res, true);

        $lists = [];

        if (!empty($res['hits']['hits'])) {

            $ipArea = new IpArea();
            $source = $res['hits']['hits'];
            foreach ($source as $k => $v) {
                $lists[$k] = $v['_source'];
                $lists[$k]['ip_address'] = $ipArea->get($lists[$k]['remote_addr']);
                $request_body = trim($v['_source']['request_body'], '"-"');
                $method = strtoupper($v['_source'][$elasticConf['es_search_filed']['request_method']]);
                $lists[$k]['method'] = $method;
                if ($method == 'GET') {
                    $tmp_arr = explode('?', $v['_source']['request']);
                    $lists[$k]['request'] = $tmp_arr[0]; //访问地址只保留?号左侧
                    if (!empty($tmp_arr[1])) {
                        $request_body = $tmp_arr[1];//？号右侧参数放入body体
                    }
                }

                if ($request_body) {
                    $tmp = explode('&', $request_body);
                    $tmp_arr = [];
                    foreach ($tmp as $vv) {
                        $t = explode('=', $vv);
                        $tmp_arr[$t[0]] = $t[1];
                    }
                    $lists[$k]['request_body'] = $tmp_arr;
                }

                $lists[$k]['timestamp'] = date('Y-m-d H:i:s', strtotime($v['_source']['timestamp']));
            }
        }
        return $lists;
    }

    /**
     * 保存所有域名
     * @param $start_time
     * @param $end_time
     * @return mixed
     */
    public function saveAllDomain($start_time, $end_time, $domain)
    {
        $domains = ApiDomain::pluck('domain')->toArray();//现在有的所有域名
        $res = EsService::getInstance()->getAllDomain(strtotime($start_time), strtotime($end_time), $domain);
        $data = [];
        foreach ($res as $k => $v) {
            if (!in_array($v['key'], $domains)) {
                $data[] = ['title' => $v['key'], 'domain' => $v['key'], 'description' => '', 'created_at' => time()];
            }
        }
        if (count($data)) {
            return ApiDomain::insert($data);
        }
    }


    /**
     * 获取这天的数据
     * @param $day
     * @param int $domain_id
     * @param string $type
     * @return array
     */
    public function getDayTotal($day, $domain_id = 0, $type = 'sum(total)')
    {
        //select DATE_FORMAT(timestamp,'%Y-%m-%d') as times,api_url_id,sum(total) from apim_api_response_time where  api_domain_id=1 and timestamp>'2018-09-12'  group by times,`api_url_id`
        $where = [];
        if ($domain_id) {
            $where[] = ['api_domain_id', $domain_id];
        }
        $where[] = ['timestamp', '>', $day . " 0:00:00"];
        $where[] = ['timestamp', '<=', $day . " 23:59:59"];

        $res = ApiResponseTime::query()->select(DB::raw('DATE_FORMAT(timestamp,"%Y-%m-%d") as times,api_url_id,' . $type . ' as total'))
            ->where($where)
            ->groupBy('times', 'api_url_id')
            ->get('total')
            ->keyBY('api_url_id')
            ->toArray();
        return $res;
    }

}
