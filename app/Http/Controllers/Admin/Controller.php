<?php

/**
 * 后台总控制器
 * @filename  Controllers
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-8-26 21:55:17
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use App\Models\AdminMenu;
use App\Models\Site;
use App\Services\AdminMenuService;
use App\Services\ApiDomainService;
use App\Services\SiteService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Helper\Jump;

class Controller extends BaseController
{

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        Jump;

    protected $login_user;
    protected $m = 'admin';//模块
    protected $c, $a, $q, $path_info;
    protected $menu_info;//菜单详情
    protected $site;//站点信息
    protected $user_domain;//项目权限

    //初始化
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //登陆验证
            $this->login_user = $request->user('admin');

            if (!$this->login_user) {
                return \Redirect::guest('/Admin/Index/index');
                if (request('back_url')) {
                    $back_url = request('back_url');
                } else {
                    $back_url = urlencode(request()->server('REQUEST_URI'));
                }
                return $this->error('请登陆', '/index/index?back_url=' . trim($back_url, "/"));
                // return \Redirect::guest('/admin/index/index?back_url='.trim($back_url,'/'));

            }
            $this->login_user->setting = json_decode($this->login_user->setting, true);

            //项目权限
            $this->user_domain = ApiDomainService::getInstance()->userDomain($this->login_user->id);

            $this->_getPathInfo();//获取请求信息

            //当前请求菜单详情
            $this->menu_info = AdminMenu::query()->where([['c', $this->c], ['a', $this->a]])->first();

            if ((!$this->menu_info) && (!preg_match('/^public/', $this->a))) {
                return $this->error('请求地址不存在,请添加!');
            }

            //检查请求权限
            if (!$this->_checkRole()) {
                return $this->error('没有权限');
            }
            //获取请求的module信息
            //$this->module_id = M('Module')->where('tablename',$this->c)->value('id');

            //站点信息
            $this->site = SiteService::getInstance()->getInfo();

            //记录日志
            if (isset($this->menu_info) && $this->menu_info->write_log == 1) {
                $this->_saveLog($this->menu_info->id);
            }


            return $next($request);
        });

    }

    //获取请求信息 模块,控制器,方法
    private function _getPathInfo()
    {
        $request_uri = explode('?', $_SERVER['REQUEST_URI']);

        if (isset($request_uri[1])) {
            $this->q = $request_uri[1];
        }

        $this->path_info = trim($request_uri[0], '/');

        if ($this->path_info) {
            $mca = explode('/', $this->path_info);

            if (count($mca) == 3) {
                list($this->m, $this->c, $this->a) = $mca;
            } elseif (count($mca) == 2) {
                $this->m = 'admin';
                list($this->c, $this->a) = $mca;
            } elseif (count($mca) == 1) {
                $this->m = 'admin';
            }

        } else {
            $this->m = 'admin';
            $this->c = 'adminHome';
            $this->a = 'publicIndex';
        }

    }

    //检测权限
    private function _checkRole()
    {

        //公开菜单
        if (preg_match('/^public/', $this->a)) {
            return true;
        }
        //超级管理员请求
        if ($this->login_user->is_super == 1) {
            return true;
        }

        //没有分配菜单
        $my_menu = AdminMenuService::getInstance()->myMenu(0);
        if (!$my_menu || !isset($this->menu_info->id)) {
            return false;
        }
        //访问菜单不在自己菜单中
        if (!in_array($this->menu_info->id, array_column($my_menu, 'id'))) {
            return false;
        }

        return true;


    }

    //写日志
    private function _saveLog($admin_menu_id)
    {
        $data = [];
        $data['admin_menu_id'] = $admin_menu_id;
        $data['querystring'] = $this->q;
        $data['admin_id'] = $this->login_user->id;
        $data['ip'] = request()->ip();
        if (request()->method() == 'POST') {
            $data['data'] = json_encode(request()->except('_token'));
            if (isset(request()->post()['id'])) {
                $data['primary_id'] = request()->post()['id'];
            }
            if (isset(request()->post()['info']['id'])) {
                $data['primary_id'] = request()->post()['info']['id'];
            }
        }
        AdminLog::query()->create($data);
    }

    //返回视图
    protected function view($data = [], $tpl = '')
    {

        if (empty($tpl)) {
            $tpl = $this->m . '/' . $this->c . '/' . $this->a;
        }
        $data = $data ? $data : [];
        $data['_m'] = $this->m;
        $data['_c'] = $this->c;
        $data['_a'] = $this->a;
        //$data['module_id'] = $this->module_id;
        $data['login_user'] = $this->login_user;
        $data['site'] = $this->site;
        $data['menu_info'] = $this->menu_info;

        return view($tpl, $data);
    }

    //公用添加,可覆盖
    protected function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //公用修改,可覆盖
    protected function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //公用存储,可覆盖
    private function storage()
    {
        //dd(request());
        $this->validate(request(), $this->M->rules, $this->M->messages);
        $params = request($this->M->fillable); //可以添加或修改的参数

        $params['admin_id'] = $this->login_user->id;

        if (request('id')) {
            $rs = $this->M->where('id', request('id'))->update($params);
        } else {
            $rs = $this->M->create($params);
        }
        return $rs;
    }

    //公用状态禁用,可覆盖
    protected function status()
    {
        $info = $this->M->find(request('id'));
        if (!$info) {
            return $this->error('找不到这条信息');
        }
        $info->status = $info->status == 1 ? 2 : 1;
        $info->save();
        return $this->success();
    }

    //公用删除,可覆盖
    protected function del()
    {
        $this->M->where('id', request('id'))->update(['is_delete' => 1]);
        return $this->success();
    }


}
