<?php
/**
 * 管理员域名分配
 * @filename  ApiDomainAdminUser.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/12 10:24
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ApiDomainAdminUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomainAdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomainAdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomainAdminUser query()
 * @mixin \Eloquent
 * @property int|null $admin_user_id 管理员id
 * @property int|null $api_domain_id 域名 id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomainAdminUser whereAdminUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiDomainAdminUser whereApiDomainId($value)
 */
class ApiDomainAdminUser extends Model{

    protected $table = 'api_domain_admin_user';
    public $dateFormat = 'U';
    public $timestamps = false;
    protected $guarded = []; //不可以注入
}