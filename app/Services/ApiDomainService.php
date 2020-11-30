<?php
/**
 * 域名
 * @filename  ApiDomainService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/11 15:08
 */

namespace App\Services;


use App\Models\ApiDomainAdminUser;

class ApiDomainService extends Service
{


    //用户分配的域名
    public function userDomain($admin_user_id)
    {
        $user_domain = ApiDomainAdminUser::query()
            ->from('api_domain_admin_user as t1')
            ->select('t2.id', 't2.title','t2.domain')
            ->leftJoin('api_domain as t2','t1.api_domain_id','=','t2.id')
            ->where('admin_user_id', $admin_user_id)->get()->toArray();
        return $user_domain;
    }

    /**
     * 没分配的域名
     * @param $admin_user_id
     * @return mixed
     */
    public function userDomainNo($admin_user_id)
    {

        $allDomain = $this->allDomain();
        $userDomain = $this->userDomain($admin_user_id);

        //用户绑定的域名
        $userDomainTmp = [];
        foreach ($userDomain as $k=>$v){
            $userDomainTmp[$v['id']] = $v;
        }
        //用户没有绑定的域名
        $userDomainNo = [];
        foreach ($allDomain as $k=>$v){
            if(!array_key_exists($v['id'],$userDomainTmp)){
                $userDomainNo[] = $v;
            }
        }
        return $userDomainNo;

    }

    //所有域名
    public function allDomain($status = '')
    {
        $where = [];
        if ($status) {
            $where[] = ['status', $status];
        }
        $lists = M('ApiDomain')->query()->select('id', 'title','domain')->where($where)->get()->toArray();
        return $lists;
    }
}