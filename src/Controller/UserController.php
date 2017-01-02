<?php

namespace Ecc\Topic\Controller;

use Ecc\Topic\Service\UserService;

class UserController extends \Mphp\BaseController{

    /**
     * 渲染reigster界面
     */
    public function userAction(){
        $view = $this->di('twig');
        echo $view->render('register.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'csrfToken'     => $this->csrfToken()
        ));
    }

    /**
     * 渲染用户中心
    **/
    public function userCenterAction(){
        $view = $this->di('twig');
        $session = $this->di('session');
        echo $view->render('user.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'username'      => $session->get('user')
        ));
    }

    /**
     * 登录
     */
    public function loginAction(){
        $data = [
            'nickname' => $_POST['user'],
            'password' => $_POST['pwd'],
        ];

        $user = new UserService();
        $ret = $user->validateUserPwd($data['nickname'], $data['password']);
        
        if ($ret) {
            unset($data['password']);
            $session = $this->di('session');
            $session->set('user', $data['nickname']);
            $this->getResponse()->jsonResponse($data);
        } else {
            $this->getResponse()->jsonResponse('Db error',1);
        }
    }

    /**
     * 登出
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

        $user = new UserService();

        if($user->validateNicknameExists($data['nickname'])) { 
            //nickname exists
            $this->getResponse()->jsonResponse('该用户名已被占用，请换用其他用户名', 1);
        } else {
            $ret = $user->reg($data['nickname'], $data['password'], $data['email']);

            if ($ret) {
                $this->getResponse()->jsonResponse(['msg' => '注册成功，' .$data['nickname']]);
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
        if($redis){
            $data = $redis->get($idx);
        }
            
        //从db中拉取
        if (empty($data)) {
            $user = new UserService();
            $data = $user->getOne('id', $id);
            $redis->set($idx, $data, 3600000);
        }

        $this->getResponse()->jsonResponse($data);
    }

}
