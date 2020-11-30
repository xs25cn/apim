<?php
/**
 * AdminUserService
 * @filename  AdminUserService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/8 16:18
 */

namespace App\Services;


use App\Models\AdminGroupUser;
use App\Models\AdminUser;

class AdminUserService extends Service
{


    /**
     * 获取用户
     * @param type $where
     * @return type
     */
    public function adminUserLists($where)
    {
        $res = AdminUser::where($where)->orderBy('id', 'desc')->paginate(20);

        foreach ($res as $k => $v) {
            if ($tmp = $this->getAdminGroupUser($v['id'])) {

                $res[$k]['groups'] = implode(',', array_column($tmp, 'name'));
            }
        }
        return $res;
    }


    /**
     * 获取域账号信息
     * @param  string $mastername 域帐号名称
     * @return array [mastername] => 域帐号名称
     *         [fullname] => 全名
     *         [mobile] => 手机号
     *         [email] => 邮箱
     *         [deptname] => 所属部门信息
     *         [title] => 职称
     *         [employee_id] => 工号
     */
    public function getStaffInfo($mastername)
    {

        $params = array(
            'mastername' => $mastername,
            'src' => 'apim.xs25.cn',
        );
        $secret = config('xin.domain_get_account_secret');
        ksort($params);
        $expect = md5(urldecode(http_build_query($params) . $secret));
        $params['sign'] = $expect;
        $domain_get_account_url = config('xin.domain_get_account_url') . "?" . http_build_query($params);

        $result = my_curl($domain_get_account_url);

        $res = json_decode($result, true);

        if (!$res) {
            //无法转换成 json,返回文字
            $res = ['code' => -1, 'msg' => $res, 'data' => []];
        } else {
            //成功是返回 code=0,改为1
            if (isset($res['code']) && $res['code'] == 0) {
                if (count($res['data']) <= 0) {
                    $res['code'] = -1;
                    $res['msg'] = '查不到域账号信息';
                } else {
                    $res['code'] = 1;
                }

            }
        }

        return $res;

    }

    /**
     * @note 域账户登陆新接口
     * @param string $mastername 域账户名称
     * @param string $pwd 用户密码
     * @param int $encrypt 密码是否加密（默认为0，0=密码未加密，1=密码已加密）
     * @return [type] [description]
     */
    public function getUserFromLdapNew($mastername, $pwd, $encrypt = 0)
    {

        $domain_account_url = config('xin.domain_account_url');
        $user_param = [
            'username' => $mastername,
            'pwd' => $pwd,
            'encrypt' => $encrypt,
            'src' => 'apim.xin.com',
        ];

        $result = my_curl($domain_account_url, $user_param);
        $res = json_decode($result, true);

        if (!$res) {
            //无法转换成 json,返回文字
            $res = ['code' => -1, 'msg' => $res, 'data' => []];
        } else {
            //成功是返回 code=0,改为1
            if (isset($res['code'])) {
                if($res['code'] === 0){
                    $res['code'] = 1;
                }elseif($res['code']==2101){
                    $res['code']=-1;
                    $res['msg']='域账号密码不正确';
                }else{
                    $res['code']=-1;
                }
            }
        }
        return $res;

    }


    /**
     * 根据类型id获取信息
     */
    public function getAdminGroupUser($id, $type = 'admin_id')
    {

        $where = [];
        if ($id) {
            if ($type == 'admin_id') {
                $where[] = ['t1.admin_id', $id];
            } else {
                $where[] = ['t1.group_id', $id];
            }
        }

        $res = AdminGroupUser::select('t2.id', 't2.name')
            ->from('admin_group_user as t1')
            ->leftJoin('admin_group as t2', 't1.group_id', '=', 't2.id')
            ->where($where)
            ->get()
            ->toArray();

        return $res;
    }

    /**
     *保存分组
     * @param type int $admin_id
     * @param type Array $group_ids
     */
    public function saveAdminGroupUser($admin_id, $group_ids)
    {
        //删除原账号对应分组
        $AdminGroupUser = M('AdminGroupUser');
        $AdminGroupUser->where('admin_id', $admin_id)->delete();
        //添加新账号对应分组
        foreach ($group_ids as $group_id) {
            $AdminGroupUser->create(['admin_id' => $admin_id, 'group_id' => $group_id]);
        }
    }

    /**
     * @param $encrypt_pwd
     * 返回真实密码
     */
    public function getPassWorkByKey($encrypt_pwd)
    {
        $real_pwd = '';
        $private_key = Config('common.login_private_key');
        $pi_key = openssl_pkey_get_private($private_key);
        openssl_private_decrypt(base64_decode($encrypt_pwd), $real_pwd, $pi_key);
        return $real_pwd;

    }
}
