<?php

namespace App\Models;

/**
 * 接口报警数据
 *
 * @filename ApiAlert.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/9/5 10:48
 * @property-read \App\Models\ApiDomain $btApiDomain
 * @property-read \App\Models\ApiUrl $btApiUrl
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $api_domain_id 域名id
 * @property int $api_url_id apiid
 * @property int|null $type 1时间，2状态码
 * @property string $timestamp 时间
 * @property int $over_total 超出
 * @property int|null $total 数量
 * @property float|null $max 最大时间
 * @property int|null $status 1可用,2禁用
 * @property string|null $code 状态码报警
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereApiDomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereApiUrlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereOverTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiAlert whereUpdatedAt($value)
 */
use Illuminate\Database\Eloquent\Model;

class ApiAlert extends Model
{
    protected $table = 'api_alert';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入

    public $type = [
        1 => '状态码',
        2 => '响应时长',
        ];

    public function btApiDomain()
    {
        return $this->belongsTo('\App\Models\ApiDomain','api_domain_id', 'id');
    }

    public function btApiUrl()
    {
        return $this->belongsTo('\App\Models\ApiUrl', 'api_url_id', 'id');
    }


}