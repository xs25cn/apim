<?php

/**
 * 管理员
 * @filename  AdminGroupController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-6-24 18:20:12
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use App\Services\AdminUserService;
use App\Services\ApiDomainService;


class AdminUserController extends Controller
{

    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new AdminUser();

    }


    //列表
    public function index()
    {

        $where = [];
        if (request('name')) {
            $where[] = ['name', 'like', '%' . request('name') . '%'];
        }
        if (request('type')) {
            $where[] = ['type', request('type')];
        }
        if (request('status')) {
            $where[] = ['status', request('status')];
        }

        $lists = AdminUserService::getInstance()->adminUserLists($where);

        return $this->view(compact('lists'));

    }

    //详情
    public function info()
    {
        $info = $this->M->find(request('id'));
        if ($info) {
            $groups = AdminUserService::getInstance()->getAdminGroupUser($info->id, 'admin_id');
            if ($groups) {
                $info['group_ids'] = implode(',', array_column($groups, 'id'));
            }
            $info->setting = json_decode($info->setting, true);

        }

        return $this->view(compact('info'));

    }

    //添加
    public function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //修改
    public function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功', '/' . $this->c . '/index');
        } else {
            return $this->error();
        }
    }

    //删除
    public function  del()
    {
        $id = request('id');
        $this->M->where('id', $id)->delete();
        return $this->success();
    }

    /*
     * 存储
     */
    private function storage()
    {
        $params = request($this->M->fillable);

        if(request('setting')){
            foreach(request('setting') as $k=>$arr){
                if(is_array($arr)){
                    $params['setting'][$k]= implode(',',$arr);
                }
            }
            $params['setting'] = json_encode($params['setting'], 64 | 256);
        }else{
            $params['setting'] = '';
        }

        $params['admin_id'] = $this->login_user->id;

        if (request('id')) {
            //修改
            $admin_id = request('id');

            $rs = $this->M->where('id', $admin_id)->update($params);

        } else {
            //添加
            //等有空整理个统一验证代码 $this->validate(request(), $this->M->rules, $this->M->messages);

            //域账号不需要密码
            if ($params['type'] == 2) {
                unset($params['password']);
            } else {
                $params['password'] = bcrypt($params['password']);
            }
            $rs = $this->M->create($params);
            $admin_id = $rs->id;
        }

        //用户分组
        if (request('group_id')) {
            $group_ids = request('group_id');
            AdminUserService::getInstance()->saveAdminGroupUser($admin_id, $group_ids);
        } else {
            M('AdminGroupUser')->where('admin_id',$admin_id)->delete();
        }

        return $rs;
    }


    //修改密码
    public function changePwd()
    {
        $info = $this->M->find(request('id'));
        if (!$info) {
            return $this->error('非法请求');
        }
        if (request('password')) {
            $this->validate(request(),
                [
                    'password' => 'required|min:3|max:20|confirmed'
                ],
                [
                    'password.required' => '密码不能为空',
                    'password.confirmed' => '密码与确认密码不一致'
                ]
            );

            $this->M->where('id', request('id'))->update(['password' => bcrypt(request('password'))]);

            return $this->success('修改成功', '/' . $this->c . '/index');
        } else {
            return $this->view(compact('info'));
        }
    }

    //查找域账号
    public function domainAccount()
    {
        $username = request('username');

        if (!$username) {
            return ['code' => -1, 'msg' => '用户名不能为空'];
        }

        $id = M('AdminUser')->where('name', $username)->value('id');
        if ($id) {
            $data = ['code' => -1, 'msg' => $username . '已存在'];
        } else {
            $data = AdminUserService::getInstance()->getStaffInfo($username);
        }
        return $data;
    }

    //用户域名分配
    public function userApiDomainInfo()
    {
        //用户的信息
        $user_info = $this->M->find(request('id'));
        if ($user_info == null) {
            return $this->error('非法操作');
        }
        $ApiDomainService = ApiDomainService::getInstance();
        //已绑域名
        $userDomain = $ApiDomainService->userDomain($user_info->id);
        array_multisort(array_column($userDomain,'domain'),SORT_ASC,array_column($userDomain,'title'),SORT_ASC,$userDomain);
        //未绑域名
        $userDomainNo = $ApiDomainService->userDomainNo($user_info->id);
        array_multisort(array_column($userDomainNo,'domain'),SORT_ASC,array_column($userDomainNo,'title'),SORT_ASC,$userDomainNo);
        return $this->view(compact('user_info', 'userDomain', 'userDomainNo'));

    }

    //保存用户绑定域名
    public function userApiDomainEdit()
    {
        $ApiDomainAdminUser = m('ApiDomainAdminUser');
        $admin_user_id = request('admin_user_id');
        $api_domain_ids = request('api_domain_ids');

        if (!$admin_user_id) {
            return $this->error('非法操作');
        }
        //去除所有
        $ApiDomainAdminUser->where('admin_user_id', $admin_user_id)->delete();

        // 添加
        if (is_array($api_domain_ids)) {
            $api_domain_ids = array_unique($api_domain_ids);
            $data = [];
            $data['admin_user_id'] = $admin_user_id;
            foreach ($api_domain_ids as $v) {
                $data['api_domain_id'] = $v;
                $ApiDomainAdminUser->create($data);
            }
        }
        return $this->success('操作成功','/' . $this->c . '/index');
    }




    /**
     * 参数校验
     * @author DuZhenxun <5552123@qq.com>
     * @param $params
     * @param $key
     */
    private function _paramsValidator($params, $key)
    {
        $roule = [];//校验规则
        $roule['addEnquiryDealerLog'] = [
            [
                'masterid' => ['required', 'numeric', function ($attr, $value, $fail) use ($params) {
                    $info = EnquiryProxyService::getInstance()->getTaskInfo($params['enquiry_proxy_id']);
                    if ($value != $info['task_masterid']) {
                        $fail(sprintf("%s 传值有问题，不是此任务交撮员", $attr));
                    }
                }],
                'car_id' => 'required',
                'full_carid' => 'required',
                'enquiry_proxy_id' => 'required',
                'content' => 'required'
            ]
        ];

        $roule['followUp'] = [
            [
                'taskid' => 'required',
                'masterid' => 'required',
                'toStatus' => function ($attr, $value, $fail) {
                    if ($value != 2) {
                        $fail(sprintf("%s 必须是2", $attr));
                    }
                }
            ], [], []
        ];
        $roule['cancelTask'] = [
            ['taskid' => 'required', 'defeat_reason' => 'required', 'masterid' => 'required', 'car_zg_status' => 'required'],
            [],
            ['masterid' => '交撮员', 'car_zg_status' => '是否下架']
        ];
        $roule['cancelOrder'] = [
            ['taskid' => 'required', 'masterid' => 'required', 'defeat_reason' => 'required',  'toStatus' => function ($attr, $value, $fail) {
                if ($value != 34) {
                    $fail(sprintf("%s 必须是34", $attr));
                }
            }]
        ];
        $roule['transferPrice'] = [
            ['taskid' => 'required', 'masterid' => 'required', 'bank_prop_type' => 'required',  'toStatus' => function ($attr, $value, $fail) {
                if ($value != 16) {
                    $fail(sprintf("%s 必须是16", $attr));
                }
            }]
        ];
        $roule['changeDealerPrice'] = [
            ['taskid' => 'required', 'masterid' => 'required', 'before_dealer_price' => 'required', 'before_profit' => 'required', 'after_dealer_price' => 'required', 'after_profit' => 'required']
        ];

        $roule['continueDealStep'] = [
            ['order_id' => 'required', 'api_type' => 'required']
        ];
        if (!empty($roule[$key])) {
            $validator = \Illuminate\Support\Facades\Validator::make($params, ...$roule[$key]);
            if ($validator->fails()) {
                $message = $validator->messages()->first();
                throw new Exception($message);
            }
        }
    }


}
