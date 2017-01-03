<?php
/**
 * Created by PhpStorm.
 * User: jacoob
 * Date: 7/8/15
 * Time: 2:04 PM
 */

namespace Mphp;

class BaseController {
    /**
     * @var App
     */
    private $_app;


    /**
     * @var Request
     */
    private $_request;


    /**
     * @var Response
     */
    private $_reponse;

    /**
     */
    public function __construct() {
        $this->_app = App::getSingleton();
        $this->di('log')->info('header');
    }

    /**
     * @return App
     */
    public function getApp()
    {
        return $this->_app;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if ($this->_request == null) {
            $this->_request = Request::getSingleton();
        }
        return $this->_request;
    }

    /**
     * @return Response
     */
    public function getResponse() {
        if ($this->_reponse == null) {
            $this->_reponse = new Response();
        }
        return $this->_reponse;
    }

    /**
     * @return string token
     */
    public function csrfToken() {
        $pool   = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $token  = hash('md5', substr(str_shuffle(str_repeat($pool, 3)), 0, 32));
        $session= $this->di('session');
        if($session !== null) {
            $session->set('csrf_token', $token);
        } else {
            DEBUG === true && Response::jsonResponse('Session is not available, pls check');
        }
        return $token;
    }

    /**
     * @return DI
     */
    public function di()
    {
        try{
            return call_user_func_array(array($this->_app, 'di'), func_get_args());
        }catch (\Exception $e){
            Response::jsonResponse($e->getMessage(), $e->getCode());
            $this->di('log')->error($e->getMessage());
        }
    }


    /**
     * @param $action string controller/action  or action
     * @param null $params array
     */
    public function redirect($action, $params=null) {
        /** @var Router $router */
        $router = $this->di('router');
        $url = $router->getActionUrl($action, $params);
        $this->redirectUrl($url);
    }

    /**
     * 跳转URL
     * @param $url string
     * @param null|array $params QUERY参数
     */
    public function redirectUrl($url, $params = null) {
        if (!empty($param)) {
            $qs = http_build_query($params);
            $char = (strpos($url, '?') === false) ? '?' : '&';
            $url = $url.$char.$qs;
        }
        header('Location: '. $url);
        exit;
    }

    public function getControllerName() {
        /** @var Router $router */
        $router = $this->di('router');
        return $router->getControllerName();
    }

    public function getActionName() {
        /** @var Router $router */
        $router = $this->di('router');
        return $router->getActionName();
    }


}
