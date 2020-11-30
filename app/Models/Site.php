<?php

namespace App\Models;

/**
 * 站点设置
 *
 * @filename Site.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $title 名称
 * @property string|null $keywords 关键词
 * @property string|null $description 说明
 * @property string|null $admin_title 后台名称
 * @property string|null $setting 其它设置
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereAdminTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Site whereUpdatedAt($value)
 */

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'site';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    //public $fillable = []; //仅可注入
    public $alert_type_arr = ['weixin' => '微信', 'email' => '邮件',];//系统报警类型

    public $alert_from_arr=['code_alert'=>'状态码异常','response_time_alert'=>'响应超时'];

    //不报警时间
    public $no_alert_time_arr = [
        '00' => '0点',
        '01' => '1点',
        '02' => '2点',
        '03' => '3点',
        '04' => '4点',
        '05' => '5点',
        '06' => '6点',
        '07' => '7点',
        '08' => '8点',
        '09' => '9点',
        '10' => '10点',
        '11' => '11点',
        '12' => '12点',
        '13' => '13点',
        '14' => '14点',
        '15' => '15点',
        '16' => '16点',
        '17' => '17点',
        '18' => '18点',
        '19' => '19点',
        '20' => '20点',
        '21' => '21点',
        '22' => '22点',
        '23' => '23点'];




}
