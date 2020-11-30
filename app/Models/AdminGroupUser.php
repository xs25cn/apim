<?php

namespace App\Models;

/**
 * 用户组与用户
 *
 * @filename AdminGroupUser.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @property-read \App\Models\AdminGroup $btAdminGroup
 * @property-read \App\Models\AdminUser $btAdminUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroupUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroupUser query()
 * @mixin \Eloquent
 * @property int $admin_id 用户id
 * @property int $group_id 角色id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroupUser whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroupUser whereGroupId($value)
 */
use Illuminate\Database\Eloquent\Model;

class AdminGroupUser extends Model
{

    protected $table = 'admin_group_user';
    public $dateFormat = 'U';
    public $timestamps = false;
    public $fillable = ['admin_id', 'group_id'];
    public $rules = [
        'admin_id' => 'required',
        'group_id' => 'required',
    ];

    public function btAdminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'admin_id', 'id');
    }

    public function btAdminGroup()
    {
        return $this->belongsTo('App\Models\AdminGroup', 'group_id', 'id');
    }

}
