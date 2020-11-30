<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crontab
 *
 * @property-read \App\Models\AdminUser $btAdminUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name 任务名
 * @property string $code 代码
 * @property string $crontab 时间
 * @property int $status 状态:1可用,2不可用
 * @property string|null $description 备注
 * @property int $admin_id 管理员id
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereCrontab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crontab whereUpdatedAt($value)
 */
class Crontab extends Model
{

    public $dateFormat = 'U';
    public $timestamps = true;
    protected $table = 'crontab';
    public $status_arr = [1 => '正常', 2 => '禁用'];
    protected $guarded = []; //不可以注入

    //可以注入
    public $fillable = [
        'name',
        'code',
        'crontab',
        'description',
        'status',
        'admin_id',

    ];

    public $messages = [
        'name.required' => '名称不能为空',
        'code.required' => '代码不能为空',
        'crontab.required' => '时间不能为空',
        'status.required' => '状态不能为空',
    ];
    public $rules = [
        'name' => 'required|string|max:50|min:2',
        'code' => 'required',
        'crontab' => 'required',
        'status' => 'required',

    ];




    public function btAdminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'admin_id', 'id');
    }


}
