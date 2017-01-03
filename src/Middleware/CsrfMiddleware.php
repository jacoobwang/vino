<?php

namespace Ecc\Topic\Middleware;

use Mphp\Middleware;
use Mphp\Response;

/**
 * Class AuthMiddleware
 * 验证用户信息是否存在
 * @package Ecc\Topic\Middleware
 */
class CsrfMiddleware extends Middleware{

    public function handle()
    {
        if ($this->_vertify()) {
            return true;
        } else {
            Response::jsonResponse('Csrf error',1);
            exit;
        }
    }

    /**
     * @return bool
     */
    private function _vertify()
    {
        $session = $this->di('session');
        $token  = $session->get('csrf_token');

        if ($_POST['csrf']==$token) {
            $session->set('csrf_token', '');
            return true;
        }
        return false;
    }

}