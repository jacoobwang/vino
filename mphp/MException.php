<?php
/**
 * Created by PhpStorm.
 * User: jacoob
 * Date: 7/8/15
 * Time: 11:08 AM
 */

namespace Mphp;

/**
 * Class MException
 * @package Mphp
 */
class MException extends \Exception {

    public static function create($msg, $code) {
        return new \Exception($msg, $code);
    }

}
