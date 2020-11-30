<?php
/**
 *
 * @filename  AdminMenuService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2019/3/14 19:50
 * @version   $Id$
 */

namespace App\Services;

use App\Models\AdminGroupUser;
use App\Models\AdminMenu;
use App\Models\ApiUrl;

class AdminMenuService extends Service {
    /**
     * 所有操作菜单
     */
    public static function getMenuList($where = [])
    {
        $res = AdminMenu::query()->where($where)->orderBy('listorder', 'asc')->get()->toArray();
        $res = node_tree($res);
        return $res;
    }

    //下拉框菜单选择
    public function selectMenu()
    {
        $tmpArr = $this->getMenuList();
        $data = array();
        foreach ($tmpArr as $k => $v) {
            $name = $v['level'] == 0 ? '<b>' . $v['name'] . '</b>' : '├─' . $v['name'];
            $name = str_repeat("│        ", $v['level']) . $name;
            $data[$v['id']] = $name;
        }
        return $data;
    }

    /**
     * 我的菜单
     * @param int $status 状态 1 只查显示,0所有
     * @return array|bool
     */
    public function myMenu($status = 1)
    {
        $where = array();
        if ($status == 1) {
            $where[] = ['status', '=', 1];
        }
        $loginUser = request()->user('admin');
        $admin_id = $loginUser->id;

        //查看此人是否超级管理员组,如果是返回所有权限
        if ($loginUser->is_super == 1) {
            //超级管理员
            $menus = AdminMenu::query()->where($where)->orderBy('listorder', 'asc')->get()->toArray();
        } else {
            //查出用户所在组Id拥有的menus
            //select menus from erp_admin_group_access t1 left join erp_admin_group t2 on t1.group_id=t2.id where t1.admin_id=11
            $menu_arr = AdminGroupUser::query()
                ->from('admin_group_user as t1')
                ->leftJoin('admin_group as t2', 't1.group_id', '=', 't2.id')
                ->where('t1.admin_id', $admin_id)
                ->pluck('menus')->toArray();
            $menu_ids = array();
            foreach ($menu_arr as $k => $v) {
                if ($v) {
                    $menu_ids = array_unique(array_merge($menu_ids, explode(',', $v)));
                }
            }

            //菜单大于0查出
            if (count($menu_ids) > 0) {
                $menus = AdminMenu::query()->where($where)->wherein('id', $menu_ids)->orderBy('listorder', 'asc')->get()->toArray();
            } else {
                return false;
            }

        }


        //追加模拟菜单
        $lists = ApiDomainService::getInstance()->userDomain($admin_id);
        foreach ($lists as $k=>$v){
            $api_total = ApiUrl::query()->where('api_domain_id',$v['id'])->count();
            $menus[] = [
                'id' => '1000'.$v['id'],
                'name' => $v['title'].'<span class="badge badge-primary">'.$api_total.'</span>',
                'parentid' => 193,
                'icon' => '',
                'm' => 'admin',
                'c' => 'apiUrl',
                'a' => 'index',
                'data' => 'api_domain_id='.$v['id'],
            ];
        }


        return $menus;
    }

    /**我的菜单返回html
     * @return string
     */
    public function myMenuHtml()
    {
        $menuTree = list_to_tree($this->MyMenu(1));

        $html = '<ul class="nav nav-list">';
        $html .= $this->menuTree($menuTree);
        $html .= "
                </ul>";
        return $html;
    }

    private function menuTree($tree)
    {

        $html = '';
        if (is_array($tree)) {
            foreach ($tree as $val) {
                if (isset($val["name"])) {
                    $title = $val["name"];
                    $url = '/' . $val['m'] . '/' . $val['c'] . '/' . $val['a'];
                    $val['data'] ? $url .= '?' . $val['data'] : '';
                    if (empty($val["id"])) {
                        $id = $val["name"];
                    } else {
                        $id = $val["id"];
                    }
                    if (empty($val['icon'])) {
                        $icon = "fa-caret-right";
                    } else {
                        $icon = $val['icon'];
                    }
                    $pathinfo = explode('?', $_SERVER['REQUEST_URI'])[0];

                    if ($url == $pathinfo) {
                        $active = 'active ';
                    } else {
                        $active = '';
                    }

                    if (isset($val['_child'])) {
                        if ($val['id'] == 193) {
                            $open = 'open';
                        } else {
                            $open = '';
                        }
                        $html .= ' 
                            <li class="' . $open . '">
                            <a href="' . $url . '" class="dropdown-toggle">
                                <i class="menu-icon fa ' . $icon . '"></i>
                                <span class="menu-text"> ' . $title . ' </span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>
                            <ul class="submenu">
                            ';
                        $html .= $this->menuTree($val['_child']);
                        $html .= '              
                            </ul>
                        </li>
                        ';
                    } else {
                        $html .= '
                    <li class = "' . $active . '">
                    <a href = "' . $url . '">
                    <i class = "menu-icon fa ' . $icon . '"></i>
                    <span class = "menu-text"> ' . $title . ' </span>
                    </a>
                    <b class = "arrow"></b>
                    </li>
                    ';
                    }
                }
            }
        }
        return $html;
    }
}