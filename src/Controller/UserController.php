<?php

namespace Ecc\Topic\Controller;

use Ecc\Topic\Model\UserModel;

class UserController extends \Mphp\BaseController{

    /**
     * 渲染界面
     */
    public function userAction(){
        $view = $this->di('view');
        $view->assign('csrfToken', $this->csrfToken());
        $view->assign('JS_CSS_DOMAIN', BASE_URL.'templates');
        $view->display('register.html');
    }

    /**
     * @param $id
     * 登录
     */
    public function loginAction(){
        $msg = isset($_POST['user'])?$_POST['user']:'jacoob';
        $data = [
            'msg'   => 'hi,Welcome ' .$msg
        ];
        $this->getResponse()->jsonResponse($data);
    }

    /**
     * 注册
     */
    public function regAction(){
        $data = [
            'nickname' => 'jacoob',
            'password' => 123456,
            'email'    => '531532957@qq.com',
        ];
        $user = new UserModel();
        $ret = $user->add($data['nickname'], $data['password'], $data['email']);

        if ($ret) {
            $this->getResponse()->jsonResponse($data);
        } else {
            $this->getResponse()->jsonResponse('Db error',1);
        }
    }

    /**
     * 获取用户信息 先从redis中读，若没有则从存储中获取
     */
    public function getUserInfoAction($id){
        $idx  = 'user_'.$id;
        $data = array(); //初始化一个data

        // 从redis中拉取
        $redis= $this->di('redis');
        if($redis) $data = $redis->get($idx);

        //从db中拉取
        if (empty($data)) {
            $user = new UserModel();
            $data = $user->getOne($id);
            $redis->set($idx, $data, 1000);
        }

        $this->getResponse()->jsonResponse($data);
    }

}
