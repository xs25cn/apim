<?php

namespace App\Models;

/**
 * 用户
 *
 * @filename AdminUser.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name 用户名
 * @property string|null $mobile
 * @property string $password 密码
 * @property string|null $remember_token
 * @property string $email 邮箱
 * @property string|null $realname 真实姓名
 * @property int|null $level 1,普通,2经理
 * @property int|null $status 1正常,2禁用
 * @property int|null $type 1:外部账号,2域账号
 * @property string|null $setting 其它设置
 * @property int|null $is_super 超级管理员,直接拥有所有权限
 * @property int|null $bind_weinxin_code
 * @property string|null $weixin_openid
 * @property string|null $headimgurl 微信远程头像
 * @property string|null $deptname 部门名称
 * @property int|null $admin_id
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereBindWeinxinCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereDeptname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereHeadimgurl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereIsSuper($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereRealname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminUser whereWeixinOpenid($value)
 */

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{

    protected $rememberTokenName = '';
    protected $hidden = [
        'password',
    ];
    protected $table = 'admin_user';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    public $fillable = ['name', 'mobile', 'password', 'realname', 'status', 'email', 'type','setting','admin_id'];
    public $status_arr = [1 => '正常', 2 => '禁用'];
    public $type_arr = [1 => '外部账号', 2 => '域账号'];

    public $messages = [
        'name.required' => '名不能为空',
    ];

    public $rules = [
        'name' => 'required|string|unique:admin_user|max:100|min:2',
    ];





}
