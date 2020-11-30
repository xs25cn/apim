<?php

namespace App\Models;

/**
 * API 域名
 *
 * @filename ApiDomain.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @property-read \App\Models\AdminUser $btAdminUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $title 名称
 * @property string $domain 域名
 * @property string|null $description 备注
 * @property int|null $orderlist 排序
 * @property int|null $response_time_alert 响应报警阀值,0不报警
 * @property int|null $status 状态:1可用,2不可用
 * @property int|null $admin_id 操作人
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property int|null $sync_at 同步时间
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereOrderlist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereResponseTimeAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomain whereUpdatedAt($value)
 */

use Illuminate\Database\Eloquent\Model;

class ApiDomain extends Model
{
    protected $table = 'api_domain';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    //public $fillable = []; //仅可注入
    public $fillable = ['title', 'domain', 'description', 'status', 'admin_id','es_index','env_type', 'sync_at']; //可以注入
    public $messages = [
        'title.required' => '名称不能为空',
        'domain.required' => '域名不能为空',
    ];
    public $rules = [
        'title' => 'required|string|max:100|min:2',
        'domain' => 'required|string',
    ];

    public $status_arr = [1 => '正常', 2 => '禁用'];
    public $es_index_arr = [
        1 => "access_log*(PHP)",
        2 => "access_json_log*(GO)",
    ];
    public $env_type_arr = [
        1 => "正式环境",
        2 => "测试环境",
    ];

    public function btAdminUser()
    {
        return $this->belongsTo('\App\Models\AdminUser', 'admin_id', 'id');
    }
}
