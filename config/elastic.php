<?php
/**
 *  索引配置
 * @filename  elastic.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/26 17:26
 */
return [
    //'host' => 'http://127.0.0.1:9200',
    'es_host' => 'http://ali-f-bpc-elasticsearch07.bj:9200',
    //索引文件
    'es_index' => [
        1 => 'act-access-nginx-log*',
        2 => 'go_access*',
        3 => 'java_access*',
    ],
    //文档
    'es_type' => '_doc',
    //es中的字段映射
    'es_search_filed'=>[
        'domain'=>'domain_name',//域名
        'request'=>'request', //请求地址
        'status'=>'status',//状态
        'timestamp'=>'@timestamp',//时间
        'request_time'=>'request_time',//响应时长
        'request_method'=>'request_action',//请求方法
    ],

    //时间分组,现在使用10分钟
    'request_time_hist' => [
        '10m' => '10分钟',
        '1h' => '1小时',
    ],
    //第一次采集
    'first_start_time' => [
        '-1' => '1天前',
        '-2' => '2天前',
        '-3' => '3天前',
        '-4' => '4天前',
        '-5' => '5天前',
        '-6' => '6天前',
        '-7' => '7天前',
        //'-10' => '10天前',
        //'-20' => '20天前',
    ],

    //服务器有延迟,所以这里需要设置下,一般10分钟最好
    'end_time' => [
        '60' => '1分钟前',
        '600' => '10分钟前',
        '3600' => '1小时前',
    ],
    //进程
    'process_max' => [
        1 => '1个',
        2 => '2个',
        5 => '5个',
        8 => '8个',
        10 => '10个',
        15 => '15个',
        20 => '20个',
        30 => '30个',
        50 => '50个',
    ],

];