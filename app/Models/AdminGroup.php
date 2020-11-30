<?php
namespace App\Models;

/**
 * 用户组
 *
 * @filename AdminGroup.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $description 备注
 * @property string|null $menus 用户组拥有的菜单id
 * @property int|null $listorder 排序
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property int $admin_id 操作人
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereListorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereMenus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AdminGroup whereUpdatedAt($value)
 */
use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{
    protected $table = 'admin_group';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    public $fillable = ['name', 'description','menus'];
    public $messages = [
        'name.required' => '名不能为空',
    ];
    public $rules = [
        'name' => 'required|string|max:100|min:2'
    ];


}
