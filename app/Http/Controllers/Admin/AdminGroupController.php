<?php

/**
 * 分组
 * @filename  AdminGroupController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-6-24 18:20:12
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminGroup;
use App\Services\AdminMenuService;

class AdminGroupController extends Controller
{

    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new AdminGroup();
    }

    //列表
    public function index()
    {
        $where = [];

        if (request('title')) {
            $where[] = ['title', 'like', '%' . request('title') . '%'];
        }
        if (request('type')) {
            $where[] = ['type', request('type')];
        }
        if (request('status')) {
            $where[] = ['status', request('status')];
        }

        $lists = $this->M->where($where)->orderBy('id', 'desc')->paginate(20);
        return $this->view(compact('lists'));
    }

    //详情
    public function info()
    {
        $info = $this->M->find(request('id'));
        $menu_lists = AdminMenuService::getInstance()->getMenuList();

        if (isset($info->menus) &&$info->menus != null) {
            $menus_in = explode(',', $info->menus);
        } else {
            $menus_in = [];
        }

        foreach ($menu_lists as $k => $v) {
            $menus[$k] = ['id' => $v['id'], 'pId' => $v['parentid'], 'name' => $v['name'], 'open' => true];
            if (in_array($v['id'], $menus_in)) {
                $menus[$k]['checked'] = true;
            }
        }

        return $this->view(compact('info', 'menus'));

    }


}
