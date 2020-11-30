<?php

namespace App\Models;

/**
 * 菜单
 *
 * @filename AdminMenu.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name 菜单名称
 * @property int|null $parentid 上级
 * @property string|null $icon 图标
 * @property string $m 模块
 * @property string $c controller
 * @property string $a action
 * @property string|null $data 更多参数
 * @property string|null $group 分组
 * @property int|null $listorder
 * @property int $status 是否显示1:显示,2:不显示
 * @property int $write_log 记录日志:1记录,2不记录
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereListorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereParentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminMenu whereWriteLog($value)
 */

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';
    public $dateFormat = 'U';
    public $timestamps = true;
    //protected $guarded = []; //不可以注入
    public $status_arr = ['1' => '显示', '2' => '不显示'];
    public $write_log_arr = ['1' => '记录', '2' => '不记录'];
    public $fillable = ['name', 'parentid', 'icon', 'c', 'a', 'data', 'status', 'listorder', 'write_log']; //可以注入
    public $messages = [
        'name.required' => '名称不能为空',
        'c.required' => '文件不能为空',
        'a.required' => '方法不能为空',
        'status.required' => '状态不能为空'
    ];
    public $rules = [
        'name' => 'required|string|max:100|min:2',
        'c' => 'required|string',
        'a' => 'required|string',
        'status' => 'required|int',
    ];





}