<?php

namespace Ecc\Topic\Controller;

class IndexController extends \Vino\BaseController{

    /**
     * render index page
     */
    public function indexAction(){
      //echo $ss;
    	$view = $this->di('twig');
    	echo $view->render('index.html', 
        array(
    		  'JS_CSS_DOMAIN' => BASE_URL.'templates',
    		  'title' => 'Welcome To Use VINO',
    		  'author'=> 'Jacoob'
    	  )
      );
    }

}
