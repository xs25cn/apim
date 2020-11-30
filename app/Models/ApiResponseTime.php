<?php

namespace App\Models;

/**
 * API响应时间
 *
 * @filename ApiUrl.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/26 16:48
 * @property-read \App\Models\ApiDomain $btApiDomain
 * @property-read \App\Models\ApiUrl $btApiUrl
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $api_domain_id api域名
 * @property int $api_url_id api地址
 * @property string $timestamp 时间段
 * @property float|null $avg 平均时间
 * @property float|null $min 最小时间
 * @property float|null $max 最大时间
 * @property int|null $total 数量
 * @property int|null $total_1 0-1秒 数量
 * @property int|null $total_2 1-5 秒 数量
 * @property int|null $total_3 5-10秒 数量
 * @property int|null $total_4 10秒以上 数量
 * @property int|null $time_alert_total 超过阀值次数
 * @property int|null $code_200
 * @property int|null $code_302
 * @property int|null $code_304
 * @property int|null $code_400
 * @property int|null $code_404
 * @property int|null $code_500
 * @property int|null $code_504
 * @property int|null $code_other
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereApiDomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereApiUrlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereAvg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode200($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode302($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode304($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode400($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode404($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode500($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCode504($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCodeOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTimeAlertTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTotal1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTotal2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTotal3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereTotal4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiResponseTime whereUpdatedAt($value)
 */

use Illuminate\Database\Eloquent\Model;

class ApiResponseTime extends Model
{
    protected $table = 'api_response_time';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    public $messages = [
        'api_domain_id.required' => '域名 id不能为空',
        'api_url_id.required' => 'url id不能为空',
    ];
    public $rules = [
        'api_domain_id' => 'required|int',
        'api_url_id' => 'required|int',
    ];

    public $view_type_arr = [
      '1'=>'分钟',
      '2'=>'小时',
      '3'=>'日',
      '4'=>'月',
    ];

    public function btApiDomain()
    {
        return $this->belongsTo('\App\Models\ApiDomain', 'api_domain_id', 'id');
    }

    public function btApiUrl()
    {
        return $this->belongsTo('\App\Models\ApiUrl', 'api_url_id', 'id');
    }


}