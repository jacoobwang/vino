<?php

namespace Ecc\Topic\Controller;

use Ecc\Topic\Service\UserService;

class UserController extends \Mphp\BaseController{

    /**
     * render user login and register page
     */
    public function userAction(){
        $view = $this->di('twig');
        echo $view->render('register.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'csrfToken'     => $this->csrfToken()
        ));
    }

    /**
     * render user page
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
     * login logic
     */
    public function loginAction(){
        $data = [
            'nickname' => $this->getRequest()->post('user'),
            'password' => $this->getRequest()->post('post')
        ];

        $user = new UserService();
        $ret = $user->findUser($data['nickname'], $data['password']);
        
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
     * logut logic
    **/ 
    public function logoutAction(){
        $session = $this->di('session');
        $session->delete('user');
        $this->redirectUrl(BASE_URL.'login');
    }

    /**
     * register logic
     */
    public function regAction(){
        $data = [
            'nickname' => $this->getRequest()->post('user'),
            'password' => $this->getRequest()->post('pwd'),
            'email'    => $this->getRequest()->post('email')
        ];

        $user = new UserService();

        if($user->validateUsername($data['nickname'])) {
            //nickname exists
            $this->getResponse()->jsonResponse('该用户名已被占用，请换用其他用户名', 1);
        } else {
            $ret = $user->addUser($data['nickname'], $data['password'], $data['email']);

            if ($ret) {
                $this->getResponse()->jsonResponse(['msg' => '注册成功，' .$data['nickname']]);
            } else {
                $this->getResponse()->jsonResponse('Db error',1);
            }
        }
    }

    /**
     * get userInfo first get it from redis than mysql
     */
    public function getUserInfoAction($id){
        $idx  = 'user_'.$id;
        $data = array();

        // get redis
        $redis= $this->di('redis');
        $data = $redis->get($idx);

        // get db
        if (empty($data)) {
            $user = new UserService();
            $data = $user->getUserById($id);
            $redis->set($idx, $data, 3600000);    
        }

        $this->getResponse()->jsonResponse($data);
    }

}
