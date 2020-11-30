<?php

namespace App\Models;

/**
 * ApiModel
 *
 * @filename ApiModule.php
 * @author Zhenxun Du <5552123@qq.com>
 * @date 2018/6/24 16:48
 * @property-read \App\Models\AdminUser $btAdminUser
 * @property-read \App\Models\ApiDomain $btApiDomain
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $api_domain_id 域名id
 * @property string|null $title 名称
 * @property string $prefix 前缀
 * @property string|null $description 备注
 * @property int|null $orderlist 排序
 * @property int|null $is_delete 1是,2否
 * @property int|null $sync_at
 * @property int|null $admin_id 操作人
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 修改时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereApiDomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereIsDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereOrderlist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereSyncAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ApiModule whereUpdatedAt($value)
 */
use Illuminate\Database\Eloquent\Model;

class ApiModule extends Model
{
    protected $table = 'api_module';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    public $fillable = ['title', 'api_domain_id','description', 'admin_id',]; //可以注入
    public $messages = [
        'title.required' => '名称不能为空',
        'api_domain_id.required' => '域名不能为空',
    ];
    public $rules = [
        'title' => 'required|string|max:100|min:1',
        'api_domain_id' => 'required|int',
    ];
    public function btApiDomain()
    {
        return $this->belongsTo('\App\Models\ApiDomain', 'api_domain_id', 'id');
    }


    public function btAdminUser()
    {
        return $this->belongsTo('\App\Models\AdminUser', 'admin_id', 'id');
    }
}
