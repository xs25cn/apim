<?php
/**
 * 工作相关
 * @filename  WorkInfoService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/14 16:18
 */

namespace App\Services;

use App\Jobs\AsyncJob;

class WorkInfoService extends Service
{
    //提醒
    public function reminder()
    {
        $where = [];
        $where[] = ['t1.reminder_status', 1];
        $where[] = ['t1.is_reminder', 1];
        $where[] = ['t1.is_delete', 0];
        $where[] = ['t1.reminder_at', '<', time()];

        //接收人
        $lists = M('WorkInfo')->query()
            ->from('work_info as t1')
            ->leftJoin('admin_user as t2', 't1.admin_id', '=', 't2.id')
            ->select('t1.id','t1.admin_id', 't1.content', 't2.setting', 't2.name', 't2.realname', 't2.email')
            ->where($where)
            ->get();

        if (count($lists) > 0) {
            //系统设置
            $system_config = SiteService::getInstance()->getSetting();
            $system_alert_type = explode(',', $system_config['alert_type'][0]);

            foreach ($lists as $info) {
                if (!$info->setting) {
                    continue;
                }
                $info->setting = json_decode($info->setting);

                if (!isset($info->setting->alert_type)) {
                    continue;
                }
                $user_alert_type = explode(',', $info->setting->alert_type);

                //微信通知报警
                if (in_array('weixin', $system_alert_type) && in_array('weixin', $user_alert_type)) {

                    $data = [];
                    $data['userName'] = $info->name;
                    $data['msg1'] = '工作提醒';
                    $data['msg2'] = "Hi,{$info->realname}";
                    $data['msg3'] = $info->content;
                    $data['msg4'] = '';

                    //同步异步
                    $queue_name = $system_config['queue_weixin'][0];
                    if ($queue_name) {
                        AsyncJob::dispatch('NoticeService', 'sendWeixin', [$data['msg1'],$data['msg2'],$data['msg3'],$data['msg4'],$data['userName']])->onQueue($queue_name);
                    } else {
                        NoticeService::getInstance()->sendWeixin($data['msg1'],$data['msg2'],$data['msg3'],$data['msg4'],$data['userName']);
                    }
                }

                //邮件报警
                if (in_array('email', $system_alert_type) && in_array('email', $user_alert_type)) {
                    $subject = "Hi,{$info->realname}, 你有新的工作提醒";
                    $queue_name = $system_config['queue_email'][0];
                    if ($queue_name) {
                        AsyncJob::dispatch('NoticeService', 'sendMail', [$subject, $info->content, $info->email])->onQueue($queue_name);
                    } else {
                        NoticeService::getInstance()->sendEmail($subject, $info->content, $info->email);
                    }
                }

            }

            M('WorkInfo')->where('id',$info->id)->update(['is_reminder'=>2]);

        }
    }
}
