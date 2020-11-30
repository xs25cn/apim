## 小手API性能监控系统 apim.xs25.cn

小手Api性能监控系统使用php语言，laravel框架开发。数据基于elasticsearch中的NGIN请求日志。通过分析日志，处理请求时间,访问量,错误码信息。

- 及时发现响应慢,报错接口
- 自由接入项目接口
- 接口使用频率统计
- 自动报警,可定制化报警
- 汇报统计（优化后效果）
- 历史数据永久保留



### 功能展示
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7avcv9l2j31jo0ry41j.jpg)
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7avofz0pj31jm0qidjb.jpg)
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7avzn3q0j31i40q2jtc.jpg)
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7av0qbizj31ig0pa786.jpg)

![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7atao6sij31pl0u0wo4.jpg)

![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7atpqd8jj324o0u0gri.jpg)

![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7au27q4fj31pa0u0dnr.jpg)
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7auaasqbj31j80u00zr.jpg)
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7aup2sboj31sn0u0teg.jpg)


### 联系作者
微信：5552123


### 使用说明
有空写个详情点的说明~~~

#### 1、nginx日志
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7ayytx32j30q80fiwg1.jpg)

    log_format log_json escape=json '{"timestamp": "$time_iso8601",'
        '"remote_addr": "$remote_addr",'
        '"request_method":"$request_method",'
        '"domain":"$host",'
        '"request":"$request_uri",'
        '"args":"$args",'
        '"request_time":$request_time,'
        '"upstream_response_time":$upstream_response_time,'
        '"response":$status,'
        '"http_referer":"$http_referer",'
        '"request_all":"$request",'
        '"request_length":"$request_length",'
        '"request_body":"$request_body",'
        '"bytes":$body_bytes_sent,'
        '"user_agent":"$http_user_agent",'
        '"server_addr": "$server_addr",'
        '"upstream_addr":"$upstream_addr",'
        '"x_forwarded":"$http_x_forwarded_for"}';
#### 2、安装Logstash收集日志
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7azsrrttj31c40oggnp.jpg)

#### 3、安装es,将日志同步到es中
![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7b0bqn0mj31j20pu777.jpg)

### 4、修改配置文件
config/elastic.php

```
   //es地址
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

```


#### 5、查看运行日志
需要开启的脚本，与laravel使用方法同理

php artisan queue:listen  --queue=apim:high,apim:middle,apim:low --tries=1  --memory=1204 --timeout=600

php artisan schedule:run  > /dev/null 2>&1




![](https://tva1.sinaimg.cn/large/0081Kckwly1gl7b16ly2fj31g20qqwjb.jpg)