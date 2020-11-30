<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * API 地址
 *
 * @filename ApiUrl.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @property-read \App\Models\AdminUser $btAdminUser
 * @property-read \App\Models\ApiDomain $btApiDomain
 * @property-read \App\Models\ApiModule $btApiModule
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $api_domain_id 域名id
 * @property int $api_module_id 模块id
 * @property string $title 名称
 * @property string $url 地址
 * @property string|null $description 备注
 * @property int|null $orderlist 排序
 * @property int|null $admin_id 操作人
 * @property int|null $response_time_alert 响应报警阀值,0不报警
 * @property string|null $code_alert 状态码报警
 * @property int $sync_at 同步时间
 * @property int|null $time_alert_type 1数量，2百分比
 * @property int|null $time_alert_total 数量或百分比
 * @property int|null $status 1可用,2禁用
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereApiDomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereApiModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereCodeAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereOrderlist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereResponseTimeAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereTimeAlertTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereTimeAlertType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiUrl whereUrl($value)
 */
class ApiUrl extends Model
{
    protected $table = 'api_url';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    public $fillable = ['title', 'api_domain_id', 'url', 'description', 'admin_id', 'api_module_id', 'sync_at', 'response_time_alert','code_alert','time_alert_total','time_alert_type']; //可以注入
    public $messages = [
        'api_domain_id.required' => '域名不能为空',
        'url.required' => 'url不能为空',
    ];
    public $rules = [
        'url' => 'required|string|max:100|min:2',
        'api_domain_id' => 'required|int',
    ];


    public $response_time_alert_arr = [
        0 => '不使用',
        2 => '2秒',
        5 => '5秒',
        10 => '10秒',
        15 => '15秒',
        20 => '20秒',
        60 => '60秒',
        ];
    public $time_alert_type_arr = [
        1 => '数量',
        2 => '百分比',

    ];

    public $code_alert_arr = [
        '499' => '499(服务端处理时间过长)',
        '500' => '500(服务器内部错误)',
        '502' => '502(错误网关)',
        '504' => '504(网关超时)',
    ];

    public function btApiDomain()
    {
        return $this->belongsTo('\App\Models\ApiDomain', 'api_domain_id', 'id');
    }

    public function btApiModule()
    {
        return $this->belongsTo('\App\Models\ApiModule', 'api_module_id', 'id');
    }
    public function btAdminUser()
    {
        return $this->belongsTo('\App\Models\AdminUser', 'admin_id', 'id');
    }


}