<?php
/**
 * 工作内容记录
 * @filename  WorkInfo.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/06/25 20:03
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkInfo
 *
 * @property-read \App\Models\AdminUser $btAdminUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $content 内容
 * @property int|null $reminder_status 是否提醒:1是,2否
 * @property int|null $admin_id
 * @property int|null $is_reminder 1未提醒,2已提醒
 * @property int|null $reminder_at 提醒日期
 * @property int|null $is_delete
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereIsReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereReminderAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereReminderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkInfo whereUpdatedAt($value)
 */
class WorkInfo extends Model
{
    protected $table = 'work_info';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    public $reminder_status_arr = [1 => '是', 2 => '否'];
    public $is_reminder_arr = [1 => '未提醒', 2 => '已提醒'];

    public function btAdminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'admin_id', 'id');
    }


}

