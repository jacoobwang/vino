<?php

namespace Ecc\Topic\Middleware;

use Mphp\Middleware;

/**
 * Class AuthMiddleware
 * 验证用户信息是否存在
 * @package Ecc\Topic\Middleware
 */
class AuthMiddleware extends Middleware{

    public function handle()
    {
        if ($this->_auth()) {
            return true;
        }
        $this->redirect(BASE_URL.'reg');
    }

    /**
     * @return bool
     */
    private function _auth()
    {
        $session = $this->di('session');
        if ($session->exist('user')) {
            return true;
        }
        return true;
    }

}