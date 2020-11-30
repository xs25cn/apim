<?php
/**
 *
 * @filename  AdminLogService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/3/14 19:50
 * @version   $Id$
 */

namespace App\Services;

use App\Models\AdminLog;

class AdminLogService extends Service {
    /**
     * 获取日志详情
     * @param $id
     * @return mixed
     */
    public function getInfo($id){
        $info = AdminLog::query()->select(
            't1.id',
            't1.admin_menu_id',
            't1.querystring',
            't1.data',
            't1.ip',
            't1.admin_id',
            't1.created_at',
            't1.primary_id',
            't2.c',
            't2.a',
            't2.name'
        )
            ->where('t1.id',$id)
            ->froM('admin_log as t1')
            ->leftJoin('admin_menu as t2','t2.id','=','t1.admin_menu_id')
            ->first();
        return $info;
    }
}