<?php

namespace Mphp;


/**
 * Class ExceptionHelper
 * @package Cutephp
 */
class ExceptionHelper extends \Exception {

    public static function create($msg, $code) {
        return new \Exception($msg, $code);
    }

    //
    //
    //    private $_code = '';
    //
    //    private $_message = '';
    //
    //    private function __construct($code, $msg) {
    //        $this->_code = $code;
    //        $this->_message = $msg;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getCode()
    //    {
    //        return $this->_code;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getMessage()
    //    {
    //        return $this->_message;
    //    }



}
