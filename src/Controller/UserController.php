<?php

namespace Ecc\Topic\Controller;

use Ecc\Topic\Service\UserService;

class UserController extends \Vino\BaseController{

    /**
     * render user page
    **/
    public function userCenterAction(){
        $view = $this->di('twig');
        $session = $this->di('session');
        echo $view->render('user.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'username'      => $session->get('user'),
            'msg'           => '做咩啊，扑街噢噢噢噢。'
        ));
    }

    /**
     * render user page
    **/
    public function homeCenterAction(){
        $view = $this->di('twig');
        $session = $this->di('session');
        echo $view->render('user.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'msg'           => '<img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489677649901&di=81ff3691855eec9ace155bbd678d17c9&imgtype=0&src=http%3A%2F%2Fg.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2Ff703738da97739125daca7e5fb198618377ae2a8.jpg" />'
        ));
    }

    public function labCenterAction(){
        $view = $this->di('twig');
        $session = $this->di('session');
        echo $view->render('user.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'msg'           => '<img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489677650119&di=621de77c15108aab452263ba1318b931&imgtype=0&src=http%3A%2F%2Fd.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2Fb3fb43166d224f4a034954fd0df790529922d10f.jpg" />'
        ));
    }

    public function allCenterAction(){
        $view = $this->di('twig');
        echo $view->render('user.html', array(
            'JS_CSS_DOMAIN' => BASE_URL.'templates',
            'msg'           => '<img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489677650118&di=3aef2075a133bee26f4e1e9da8b4d534&imgtype=0&src=http%3A%2F%2Fc.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2F54fbb2fb43166d226ded327a422309f79152d241.jpg" />'
        ));
    }

    /**
     * login logic
     */
    public function loginAction(){
        $data = [
            'nickname' => $this->getRequest()->post('user'),
            'password' => $this->getRequest()->post('pwd')
        ];

        // vaildate post data 
        $validator = $this->getValidator()->make($data, [
            'nickname' => ['required|min:4','用户名字数不能少于4'],
            'password' => ['required|min:6','密码最小长度为6位']
        ]);

        // if fail return errors to response
        if($validator->fails()) {
            $this->getResponse()->jsonResponse($validator->message(),2);
            exit;
        }

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

        // vaildate post data 
        $validator = $this->getValidator()->make($data, [
            'nickname' => ['required|min:4','用户名字数不能少于4'],
            'password' => ['required|min:6','密码最小长度为6位'],
            'email'    => ['email','请填写正确的邮箱格式']
        ]);

        // if fail return errors to response
        if($validator->fails()) {
            $this->getResponse()->jsonResponse($validator->message(),2);
            exit;
        }

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

}
