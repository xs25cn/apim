<?php
/**
 *
 * @filename  ApiModuleService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/3/14 20:04
 * @version   $Id$
 */
namespace App\Services;
use App\Models\ApiModule;

class ApiModuleService extends Service{
    public function getApiModel($domain_id=''){
        $where = [];
        $where[] = ['is_delete', 2];
        if($domain_id){
            $where[] = ['api_domain_id',$domain_id];
        }

        $lists = ApiModule::query()->where($where)->pluck('title','id');
        if(count($lists)){
            return $lists->toArray();
        }else{
            return [];
        }

    }
}