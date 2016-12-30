<?php

namespace Ecc\Topic\Controller;

class IndexController extends \Mphp\BaseController{


    public function indexAction(){
        $view = $this->di('view');

        $view->assign('JS_CSS_DOMAIN', BASE_URL.'templates');
        $view->assign('title', 'Welcome To Use Mphp');
        $view->assign('word', '非常感谢您选择了mphp，下面就开始您的web之旅吧！');
        $view->assign('author', 'Created By <span>Jacoob</span>');
        $view->assign('contact', '技术交流或合作，欢迎与我联系qq531532957');
        $view->display('index.html');
    }

}
