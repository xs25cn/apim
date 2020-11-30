<?php

/**
 * 
 * @filename  Jump
 * @author    Zhenxun Du <5552123@qq.com> 
 * @date      2017-8-17 16:40:22  
 * @version   SVN:$Id:$ 
 */

namespace App\Helper;

trait Jump {

    /**
     * 成功
     * @param type $msg 消息
     * @param type $url 跳转地址
     * @param type $wait 等待时间
     * @return type
     */
    protected function success($msg='', $url = '', $wait = 1) {
	if (!$url) {
	    $url = $this->getRefererUrl();
	}else{
        $url='/'.$this->m.$url;
    }

	if(!$msg){
	    $msg = '操作成功!';
	}
       // app('session')->put(['msg'=>$msg]);
	//dd($url);
	return redirect('/'.$this->m.'/msg/index')->with(['msg' => $msg, 'url' => $url, 'wait' => $wait, 'code' => 1]);
    }

    /**
     * 失败
     * @param type $msg 消息
     * @param type $url 跳转地址
     * @param type $wait 等待时间
     * @return type
     */
    protected function error($msg='', $url = '', $wait = 3) {
	if (!$url) {
	    $url = $this->getRefererUrl();
	}else{
        $url='/'.$this->m.$url;
    }
	if(!$msg){
	    $msg = '操作失败!';
	}
	return redirect('/'.$this->m.'/msg/index')->with(['msg' => $msg, 'url' => $url, 'wait' => $wait, 'code' =>0]);
    }
    
    /**
     * 
     * @return string
     */
    private function getRefererUrl() {
	if (isset($_SERVER['HTTP_REFERER'])) {
	    $urlInfo = parse_url($_SERVER['HTTP_REFERER']);

	    $query = isset($urlInfo['query']) ? '?' . $urlInfo['query'] : '';
	    $url = $urlInfo['path'] . $query;
	} else {
	    $url = '/';
	}
	return $url;
    }

}
