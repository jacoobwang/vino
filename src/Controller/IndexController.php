<?php

namespace Ecc\Topic\Controller;

class IndexController extends \Mphp\BaseController{


    public function indexAction(){
      	$view = $this->di('twig');
      	echo $view->render('index.html', array(
      		'JS_CSS_DOMAIN' => BASE_URL.'templates',
      		'title' => 'Welcome To Use Mphp',
      		'author'=> 'Jacoob'
      	));
    }

}
