<?php
/**
 * Created by PhpStorm.
 * User: jacoob
 * Date: 7/8/15
 * Time: 11:56 AM
 */

namespace Mphp;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Processor\WebProcessor;


class MLogger implements ILogger {
    /**
     * @var Logger
     */
    private $_logger;
    private $_log_file;


    /**
     * @param string $log_file LOG文件
     * @param string $tag_name 日志TAG
     */
    public function __construct($log_file, $tag_name = 'mphp') {
        $this->_log_file = $log_file;
        $this->_logger = $this->createLogger($tag_name, $log_file);
    }

    public function createLogger($tag_name, $log_file) {
        $log = new Logger($tag_name);
        if (!empty($log_file)) {
            $log->pushHandler(new RotatingFileHandler($log_file, Logger::DEBUG));
        }

        return $log;
    }

    /**
     * @return Logger
     */
    public function getLogger() {
        return $this->_logger;
    }


    public function argToString() {
        $outputs = array();
        foreach (func_get_args() as $v) {
            if (is_string($v)) {
                $outputs[] = $v;
            } else {
                $outputs[] = var_export($v, true);
            }
        }
        return implode(' ', $outputs);
    }

    /**
     * 允许输出到日志到CHROME的控制台，需安装扩展
     * https://chrome.google.com/webstore/detail/chromephp/noaneddfkdjfnfdakjjmocngnfkfehhd
     * 详见: https://craig.is/writing/chrome-logger
     */
    public function setAllowChromeLog() {
        $this->_logger->pushHandler(new ChromePHPHandler());
    }

    public function setWebProcessor() {
        $this->_logger->pushProcessor(new WebProcessor());
    }

    public function debug() {
        call_user_func_array(array($this->_logger, 'debug'), func_get_args());
    }

    public function info() {
        call_user_func_array(array($this->_logger, 'info'), func_get_args());
    }

    public function warn() {
        call_user_func_array(array($this->_logger, 'warn'), func_get_args());
    }

    public function error() {
        call_user_func_array(array($this->_logger, 'err'), func_get_args());
    }

    public function alert() {
        call_user_func_array(array($this->_logger, 'alert'), func_get_args());
    }

    public function emerg() {
        call_user_func_array(array($this->_logger, 'emerg'), func_get_args());
    }



    public function get($tag_name) {
        return new self($this->_log_file, $tag_name);
    }


}
