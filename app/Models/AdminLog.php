<?php
namespace App\Models;

/**
 * 操作日志
 *
 * @filename AdminLog.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $admin_menu_id 菜单id
 * @property string|null $querystring 参数
 * @property string|null $data POST数据
 * @property string $ip
 * @property int $admin_id 操作人
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @property int|null $primary_id 表中主键ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereAdminMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog wherePrimaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereQuerystring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminLog whereUpdatedAt($value)
 */
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_log';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    //public $fillable = []; //仅可注入


}
