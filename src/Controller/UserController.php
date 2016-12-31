<?php

namespace Ecc\Topic\Controller;

use Ecc\Topic\Model\UserModel;

class UserController extends \Mphp\BaseController{

    /**
     * 渲染reigster界面
     */
    public function userAction(){
        $view = $this->di('view');
        $view->assign('csrfToken', $this->csrfToken());
        $view->assign('JS_CSS_DOMAIN', BASE_URL.'templates');
        $view->display('register.html');
    }

    /**
     * 渲染用户中心
    **/
    public function userCenterAction(){
        $view = $this->di('view');
        $view->assign('JS_CSS_DOMAIN', BASE_URL.'templates');
        $view->display('user.html');
    }

    /**
     * @param $id
     * 登录
     */
    public function loginAction(){
        $data = [
            'nickname' => $_POST['user'],
            'password' => $_POST['pwd'],
        ];

        $user = new UserModel();
        $ret = $user->login($data['nickname'], $data['password']);

        if ($ret) {
            $rs = [
                'nickname'  => $data['nickname']
            ];
            $session = $this->di('session');
            $session->set('user', $data['nickname']);
            $this->getResponse()->jsonResponse($rs);
        } else {
            $this->getResponse()->jsonResponse('Db error',1);
        }
    }

    /**
     * logout
    **/ 
    public function logoutAction(){
        $session = $this->di('session');
        $session->delete('user');
        $this->redirectUrl(BASE_URL.'login');
    }

    /**
     * 注册
     */
    public function regAction(){
        $data = [
            'nickname' => $_POST['user'],
            'password' => $_POST['pwd'],
            'email'    => $_POST['email'],
        ];

        $user = new UserModel();
        $check = $user->getOne('nickname', $data['nickname']);
        if($check) {
            //nickname exis
            $msg = '该用户名已被占用，请换用其他用户名'; 
            $this->getResponse()->jsonResponse($msg, 1);
        }else {
            $ret = $user->add($data['nickname'], $data['password'], $data['email']);

            if ($ret) {
                $msg  = '注册成功，' .$data['nickname'];
                $this->getResponse()->jsonResponse(['msg' => $msg]);
            } else {
                $this->getResponse()->jsonResponse('Db error',1);
            }
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
            $data = $user->getOne('id', $id);
            $redis->set($idx, $data, 1000);
        }

        $this->getResponse()->jsonResponse($data);
    }

}
