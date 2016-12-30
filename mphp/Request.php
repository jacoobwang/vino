<?php
/**
 * Created by PhpStorm.
 * User: jacoob
 * Date: 7/8/15
 * Time: 4:25 PM
 */

namespace Mphp;

class Request {
    private static $_singleton;

    /**
     * @return Request
     */
    public static function getSingleton() {
        if (self::$_singleton == null) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }


    private $_get_params;
    private $_route_params = array();

    private function __construct() {
        $this->_get_params = $_GET;
    }

    /**
     * @param array $route_params
     */
    public function setRouteParams($route_params) {
        $this->_route_params = $route_params;
    }

    private function _getValue($arr, $key, $default = null) {
        if (is_array($key)) {
            $ret = [];
            foreach ($key as $v) {
                $ret[$v] = $this->_getValue($arr, $v, $default);
            }
            return $ret;
        }
        if (isset($arr[$key])) {
            return $arr[$key];
        } else {
            return $default;
        }
    }

    /**
     * 获取GET参数值，如果为空继续检索路由参数
     * @param $key
     * @param null $default
     * @return null
     */
    public function paramGet($key, $default = null) {
        $ret = $this->_getValue($this->_get_params, $key, null);
        if (is_null($ret)) {
            $ret = $this->paramRouter($key, $default);
        }
        return $ret;
    }

    public function paramPost($key, $default = null) {
        return $this->_getValue($_POST, $key, $default);
    }

    public function paramRequest($key, $default = null) {
        if (isset($this->_get_params[$key])) {
            return $this->_get_params[$key];
        } else if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return $default;
        }
    }

    /**
     * 获取路由参数
     * @param $key
     * @param null $default
     * @return null
     */
    public function paramRouter($key, $default = null) {
        return $this->_getValue($this->_route_params, ":$key", $default);
    }

    public function getMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function isPost() {
        return $this->getMethod() == 'POST';
    }

    /**
     * 所有的查询参数
     * @return array
     */
    public function getAllQueryParam() {
        return $this->_get_params;
    }

    public function removeParam($key) {
        if (isset($this->_get_params[$key])) {
            unset($this->_get_params[$key]);
        }
    }

    public function setParam($key, $value) {
        $this->_get_params[$key] = $value;
    }

    public function hasGetParam($key) {
        return isset($this->_get_params[$key]);
    }

    public function hasPostParam($key) {
        return isset($_POST[$key]);
    }

    public function getClientIp() {
        return isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
    }
}
