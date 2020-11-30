<?php
/**
 * 异步执行
 * @filename  Async.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @time      2018/11/07 下午1:43
 * @version   SVN:$Id:$
 */

namespace App\Console\Commands;

use App\Services\AsyncService;
use Illuminate\Console\Command;

/**
 * Class Async
 *
 *  使用方法
 * 例一,service名::方法名 参数....
 * php artisan async --method=TestService::test --args=duzhenxun,32
 * php artisan async --method=Enquiry\\EnquiryService::editTaskStatus --args=6911,2,1234 //指定service下的文件
 * php artisan async --method=\\App\\Models\\Enquiry\\EnquiryProxyUserModel::getEnquiryProxyUser //任意文件
 *
 * 如果方法中需要传数组可以用以下命令  例(['age'=>19,'name'=>'duzhenxun'],'duzhenxun',['arr0','arr1'])
 * php artisan async --method=Async\\TestService::test2 --args=age=19#name=duzhenxun,string,arr0#arr1
 *
 * 例二, 参数如果是多维数组可以使用base64方式,最后一个字段传入base64后的信息
 * 第一步初始化$data数组,
 * 第二步将$data base64_encode(json_encode($data))
 * $data['service'] = '\App\Services\TestService'; 类位置
 * $data['method'] = 'test'; 方法名
 * $data['params'] = [duzhenxun,32]; 方法不需要参数,则不添加此变量
 * $str=base64_encode(json_encode($data))
 * php artisan async  --method=base64 eyJzZXJ2aWNlIjoiXFxBcHBcXFNlcnZpY2VzXFxUZXN0U2VydmljZSIsIm1ldGhvZCI6InRlc3QiLCJwYXJhbXMiOlsiZHV6aGVueHVuIiwzMl19
 *
 * @package App\Console\Commands
 */
class Async extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'async {str=0} {--method=} {--args=}';

    /**
     * The console command description.
     *
     * @var string
     */


    protected $description = 'php artisan async --method=TestService::test --args=duzhenxun,32';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $data = $this->_formatData(); //格式化参数
            //执行代码
            $res = AsyncService::getInstance()->execute($data);
            print_r($res);
        } catch (\Exception $e) {
            $msg = stripslashes($e->getMessage());
            echo $msg;
        }
        return true;
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    private function _formatData()
    {
        $method = $this->option('method');
        $args = $this->option('args');
        $str = $this->argument('str');

        if ($method == 'base64') {
            if (!$str) {
                throw new \Exception(sprintf('method:base64,缺少值'));
            }
            $data = json_decode(base64_decode($str), true);
            if (!is_array($data)) {
                throw new \Exception('base64 值有问题:' . $str);
            }
        } else {
            if (!strstr($method, '::')) {
                throw new \Exception(sprintf('method:%s有问题', $method));
            } else {
                $data = [];
                list($data['service'], $data['method']) = explode('::', $method);
                //方法所需参数
                if ($args) {
                    $arr = explode(',', trim($args));
                    foreach($arr as $k=>$v){
                        if(strstr($v,'#')){
                            $tmp=explode('#', trim($v));
                            foreach ($tmp as $kk=>$vv){
                                if(strstr($vv,'=')){
                                    $tmp2 = explode('=',$vv);
                                    $data['params'][$k][$tmp2[0]]=$tmp2[1];
                                }else{
                                    $data['params'][$k][]=$vv;
                                }
                            }
                        }else{
                            $data['params'][$k]=$v;
                        }
                    }
                }
            }
        }

        return $data;
    }

}
