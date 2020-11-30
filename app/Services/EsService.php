<?php
/**
 * elasticSearch 相当操作
 * @filename  EsService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/5 09:50
 */

namespace App\Services;

use App\Models\ApiDomain;
use GuzzleHttp\Client;
use \Illuminate\Support\Facades\Log;
use Elasticsearch\ClientBuilder;

class EsService extends Service
{

    /**
     * 格式化API响应时间数据
     * @param $results
     * @return array|bool
     */
    public function formatApiResponseTime($results)
    {
        $results = json_decode($results, true);
        //对返回的信息做判断,是否超时,查取不到信息等
        if (!isset($results['aggregations']['res']['buckets']) || count($results['aggregations']['res']['buckets']) == 0) {
            return false;
        }

        $res = $results['aggregations']['res']['buckets'];

        $data = [];
        foreach ($res as $k => $v) {
            $data[$v['key_as_string']]['timestamp'] = $v['key_as_string'];
            $data[$v['key_as_string']]['total'] = $v['stats_time']['count'];
            $data[$v['key_as_string']]['min'] = $v['stats_time']['min'];
            $data[$v['key_as_string']]['max'] = $v['stats_time']['max'];
            $data[$v['key_as_string']]['avg'] = $v['stats_time']['avg'];
            $data[$v['key_as_string']]['code'] = $v['code']['buckets'];
        }
        return $data;

    }

    /**
     * 获取所有域名
     * @param $start_time
     * @param $end_time
     * @param string $domain
     * @return array
     */
    public function getAllDomain($start_time, $end_time, $domain = 'xs25')
    {
        $elasticConf = config('elastic');
        $json = '{
            "_source": [],
            "size": 0,
            "query": {
                "bool": {
                    "must": [
                        {
                            "regexp": {
                                "'.$elasticConf['es_search_filed']['domain'].'.keyword": "[a-zA-Z0-9]+\\\.' . $domain . '\\\.com"
                            }
                        },
                        {
                            "range": {
                                "'.$elasticConf['es_search_filed']['timestamp'].'": {
                                    "gte": "' . $start_time . '000",
                                     "lt": "' . $end_time . '000",
                                    "format": "epoch_millis"
                                }
                            }
                        }
                    ]
                }
            },
           "aggs" : {
            "actors" : {
              "terms" : {
                 "field" : "'.$elasticConf['es_search_filed']['domain'].'.keyword",
                 "size" : 1000,
                 "collect_mode" :"breadth_first"
              }
         
            }
          }
        }';
        //参数
        $params = [
            'index' =>$elasticConf['es_index'],
            'type' => $elasticConf['es_type'],
            'body' => $json
        ];

        $client = ClientBuilder::create()->setHosts(config('elastic.es_host'))->build();
        $results = $client->search($params);

        $res = [];
        if (!empty($results['aggregations']['actors']['buckets'])) {
            $res = $results['aggregations']['actors']['buckets'];
        }
        return $res;
    }


    /**
     * 获取api最近20条响应数据
     * @param $domain
     * @param $url
     * @param int $start_time
     * @param int $end_time
     * @param int $size
     * @return string
     */
    public function getApiLastResponseJson($domain, $url, $start_time = 0, $end_time = 0, $size = 20)
    {
        $elasticConf = config('elastic');
        //查询数据
        $json = '{
                "query": {
                    "bool": {
                        "must": [
                            {
                                "term": {
                                    "'.$elasticConf['es_search_filed']['domain'].'.keyword": "' . $domain . '"
                                }
                            },
                            {
                                "prefix": {
                                    "'.$elasticConf['es_search_filed']['request'].'.keyword": "' . $url . '"
                                }
                            },
                
                            {
                                "range": {
                                    "'.$elasticConf['es_search_filed']['timestamp'].'": 
                                    {
                                        "gte": "' . $start_time . '000",
                                        "lt": "' . $end_time . '000",
                                        "format": "epoch_millis"
                                    }
            
                                }
                            }
                        ]
                    }
                },
                 "sort": [
                            {
                                "'.$elasticConf['es_search_filed']['request_time'].'": 
                                {
                                    "order": "desc"
                                }
                            }
                        ],
                "size": ' . $size . '
            }';

        return $json;
    }


    /**
     * api响应时间分组查询
     * @param $domain
     * @param $url
     * @param string $time_hist
     * @param int $start_time
     * @param int $end_time
     * @param int $request_time_gt
     * @param int $request_time_lt
     * @return string
     */
    public function getApiResponseTimeJson($domain, $url, $time_hist = '10m', $start_time = 0, $end_time = 0, $request_time_gt = 0, $request_time_lt = 1000)
    {
        $elasticConf = config('elastic');
        $url = trim($url, '/');
        //查询数据
        $json = '{
                    "_source": [
                        "'.$elasticConf['es_search_filed']['domain'].'",
                        "'.$elasticConf['es_search_filed']['request_time'].'",
                        "'.$elasticConf['es_search_filed']['timestamp'].'",
                        "'.$elasticConf['es_search_filed']['request'].'"
                    ],
                    "size": 1,
                    "sort": [
                        {
                            "'.$elasticConf['es_search_filed']['timestamp'].'": {
                                "order": "asc",
                                "unmapped_type": "boolean"
                            }
                        }
                    ],
                    "aggs": {
                       "res": {
                            "date_histogram": {
                                "field": "'.$elasticConf['es_search_filed']['timestamp'].'",
                                "interval": "' . $time_hist . '",
                                "format": "yyyy-MM-dd HH:mm:ss",
                                "time_zone": "Asia/Shanghai",
                                "min_doc_count": 1
                            },
                            "aggs": {
                                "stats_time": {
                                    "stats": {
                                        "field": "'.$elasticConf['es_search_filed']['request_time'].'"
                                    }
                                },
                    
                            "code": {
                                "terms": {
                                    "field": "'.$elasticConf['es_search_filed']['status'].'"                               
                                    }
                                }
                            }
                        }
                    },
                    "query": {
                        "bool": {
                            "must": [
                                {
                                    "term": {
                                        "'.$elasticConf['es_search_filed']['domain'].'.keyword": "' . $domain . '"
                                    }
                                },
                                {
                                    "prefix": {
                                        "'.$elasticConf['es_search_filed']['request'].'.keyword": "/' . $url . '"
                                    }
                                },
                                {
                                    "range": {
                                        "'.$elasticConf['es_search_filed']['timestamp'].'": {
                                            "gte": "' . $start_time . '000",
                                            "lt": "' . $end_time . '000",
                                            "format": "epoch_millis"
                                        }
                                    }
                                },
                                {
                                    "range": {
                                        "'.$elasticConf['es_search_filed']['request_time'].'": {
                                           "gte": "' . $request_time_gt . '",
                                            "lt": "' . $request_time_lt . '"
                                        }
                                    }
                                }
                            ]
                        }
                    }
                }';

        //参数
        return $json;
    }


    /**
     * 通过域名获取url地址
     * @param $domain
     * @param $prefix
     * @param $start_time
     * @param $end_time
     * @return string
     */
    public function getDomainApiUrlJson($domain, $prefix, $start_time, $end_time)
    {
        $elasticConf = config('elastic');
        $json = '{
                     "_source": false,
                      "query": {
                            "bool": {
                                "must": [
                                    {
                                        "term": {
                                            "'.$elasticConf['es_search_filed']['domain'].'.keyword": "' . $domain . '"
                                        }
                                    },
                                   {
                                         "prefix": {
                                             "'.$elasticConf['es_search_filed']['request'].'.keyword": "' . $prefix . '"
                                          }
                                    },
                                    {
                                    "range": {
                                        "'.$elasticConf['es_search_filed']['timestamp'].'": {
                                            "gte": "' . $start_time . '000",
                                            "lt": "' . $end_time . '000",
                                            "format": "epoch_millis"
                                        }
                                    }
                                }
                                ]
                            }
                        },
                      "aggs" : {
                        "res" : {
                          "terms" : {
                             "field" : "'.$elasticConf['es_search_filed']['request'].'.keyword",
                             "size" : 1000,
                             "collect_mode" :"breadth_first"
                          }
                        }
                      }
                    }';

        return $json;

    }


    public function getEsDomain(){
        return config('elastic.es_host');
    }
    /**
     * 通过域名获取index
     * @param $es_index
     * @return string
     */
    public function getEsIndex($es_index = 1)
    {
        $elasticConf = config('elastic');
        if (!empty($elasticConf['es_index'][$es_index])) {
            $index = $elasticConf['es_index'][$es_index];
        }else{
            $index='';
        }
        return $index;
    }

    public function getEsType()
    {
        return config('elastic.es_type');
    }




}
