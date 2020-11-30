<?php

/**
 * 后台个人页
 * @filename  UserHomeController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-8-16 10:20:11
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminHomeController extends Controller
{

    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new AdminUser();
    }

    public function publicIndex()
    {
        $start_time = date('Y-m-d').' 00:00:00';
        $end_time = date('Y-m-d 23:59:59');

        if(request('api_domain_id')){
            $api_domain_ids = [request('api_domain_id')];
        }else{
            $api_domain_ids = array_column($this->user_domain,'id');
        }

        //我的项目
        $lists = [];
        if ($this->user_domain) {
            $lists = M('ApiResponseTime')
                ->select(DB::raw('api_url_id,max(`max`) as max_time'))
                ->where([['timestamp', '>=', $start_time], ['timestamp', '<', $end_time]])
                ->whereIn('api_domain_id',$api_domain_ids)
                ->groupBy('api_url_id')
                ->orderBy('max_time', 'desc')
                ->limit(50)
                ->get()
                ->toArray();
                 if (count($lists)) {
                     $api_urls = M('ApiUrl')
                         ->select('t1.id', 't1.title', 't1.url', 't1.response_time_alert', 't2.domain', 't2.title as domain_title')
                         ->from('api_url as t1')
                         ->leftJoin('api_domain as t2', 't1.api_domain_id', '=', 't2.id')
                         ->whereIn('t1.id', array_column($lists, 'api_url_id'))
                         ->get()
                         ->toArray();

                     $tmp = [];
                     foreach ($api_urls as $k => $v) {
                         $tmp[$v['id']] = $v;
                     }

                     foreach ($lists as $k => $v) {
                         $lists[$k]['domain'] = $tmp[$v['api_url_id']]['domain'];
                         $lists[$k]['url'] = $tmp[$v['api_url_id']]['url'];
                         $lists[$k]['time_alert'] = $tmp[$v['api_url_id']]['response_time_alert'];
                         $lists[$k]['domain_title'] = $tmp[$v['api_url_id']]['domain_title'];
                         $lists[$k]['title'] = $tmp[$v['api_url_id']]['title'];
                     }
                 }
        }

        $domains=[];
        foreach ($this->user_domain as $k=>$v){

            $domains[$v['id']]='【'.$v['domain'].'】'.$v['title'];
        }


        return $this->view(compact('lists','domains'));


    }

    /**
     * 个人修改资料
     */
    public function publicInfo()
    {
        $info = $this->login_user;

        if (request('id')) {
            //修改
            if ($info->id) {
                $params = request(['mobile', 'realname', 'email']); //可以添加或修改的参数

                $this->M->where('id', $info->id)->update($params);
            }
            return $this->success();
        } else {
            return $this->view(compact('info'));
        }
    }

    /**
     * 个人修改资料
     */
    public function publicAlert()
    {
        $info = $this->login_user;

        if (request('id')) {
            //修改
            if ($info->id) {
                $params=[];
                if (request('setting')) {
                    foreach (request('setting') as $k => $arr) {
                        if (is_array($arr)) {
                            $params['setting'][$k] = implode(',', $arr);
                        }
                    }
                    $params['setting'] = json_encode($params['setting'], 64 | 256);
                } else {
                    $params['setting'] = '';
                }
                $this->M->where('id', $info->id)->update($params);
            }
            return $this->success();
        } else {
            return $this->view(compact('info'));
        }
    }


    /**
     * 个人修改密码
     */
    public function publicChangePwd()
    {
        $info = $this->M->find($this->login_user->id);

        if (!$info) {
            return $this->error('操作非法');
        }

        if (request('id')) {
            $this->validate(request(), [
                'passwordOld' => 'required',
                'password' => 'required | min:3 | max:20 | confirmed',
            ], [
                'passwordOld . required' => '旧密码不能为空',
                'password . required' => '密码不能为空',
                'password . confirmed' => '密码与确认密码不一致',
            ]);
            //旧密码不正确
            if (!Hash::check(request('passwordOld'), $info->password)) {
                return $this->error('旧密码不正确');
            }

            $this->M->where('id', $info->id)->update(['password' => bcrypt(request('password'))]);
            return $this->success();
        } else {
            return $this->view(compact('info'));
        }
    }



    //绑定微信
    public function publicBindWeixin()
    {
        //已绑过微信
        if ($this->login_user->weixin_openid) {
            return $this->error('此账号已绑定微信', ' / adminHome / publicInfo');
        }
        if (request('json')) {
            $weixin_info = json_decode(request('json'), true);
            if (!$weixin_info['openid']) {
                return $this->error('非法操作');
            }
            $openid = $weixin_info['openid'];
            //查看此 openid 是否被别人绑定过
            $info = M('AdminUser')->where('weixin_openid', $openid)->first();
            if ($info) {
                return $this->error('此微信号已绑定账号', ' / adminHome / publicInfo');
            }
            //处理头像.....

            $data = [];
            $data['headimgurl'] = $weixin_info['headimgurl'];
            $data['weixin_openid'] = $weixin_info['openid'];
            //修改用户信息
            M('AdminUser')->where('id', $this->login_user->id)->update($data);
            return $this->success('绑定成功', ' / adminHome / publicInfo');

        } else {
            return $this->view();
        }
    }

    //解绑
    public function publicUnBindWeixin()
    {
        if (!$this->login_user->weixin_openid) {
            return $this->error('还没绑定', ' / adminHome / publicInfo');
        } else {
            //修改用户信息
            M('AdminUser')->where('id', $this->login_user->id)->update(['weixin_openid' => '']);
            return $this->success('解绑成功', ' / adminHome / publicInfo');
        }
    }

    public function publicEnv(){
        dump($_SERVER);
    }


}
