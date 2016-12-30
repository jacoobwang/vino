<?php

namespace Mphp;

class RestAuth {

    public static function build_query_string($parameters) {
        //        $s = http_build_query($parameters);
        //        $s = str_replace( array('+', '%7E'), array('%20', '~'), $s);
        //        return $s;

        // RFC3986
        // http://en.wikipedia.org/wiki/Percent-encoding#References
        $arr = array();
        foreach ($parameters as $k => $v) {
            $arr[] = sprintf('%s=%s', rawurlencode($k), rawurlencode($v));
        }

        return implode('&', $arr);
    }


    private $key_ = '';

    private $timeout_ = 600;

    private $algorithm = 'md5';

    function __construct($key) {
        $this->key_ = $key;
    }

    /**
     * @param int $timeout_
     */
    public function setTimeout($timeout_) {
        $this->timeout_ = $timeout_;
    }


    /**
     * @param string $algorithm
     * @return bool
     */
    public function setAlgorithm($algorithm) {
        $support_algorithm = array('sha256', 'sha1', 'md5');
        if (in_array($algorithm, $support_algorithm)) {
            $this->algorithm = $algorithm;
            return true;
        } else {
            return false;
        }
    }


    public function hmac($params) {
        $key = $this->key_;
        ksort($params);
        $str_params = self::build_query_string($params);
        $ret = hash_hmac($this->algorithm, $str_params, $key);
        return $ret;
    }

    public function buildRequestParam($params, $appid) {
        $key = $this->key_;
        $params['expires'] = time() + $this->timeout_;
        $params['verify'] = $this->hmac($params, $key);
        $params['appid'] = $appid;
        return self::build_query_string($params);
    }


    /**
     * @param $params
     * @return int
     */
    public function verifyRequest($params) {
        $key = $this->key_;
        if (!isset($params['verify'])) {
            return 47510701;
        }
        if (!isset($params['expires'])) {
            return 47510702;
        }
        $verify = $params['verify'];
        $time = $params['expires'];
        unset($params['verify']);
        if (empty($params)) {
            return 47510703;
        }
        if ($verify !== $this->hmac($params, $key)) {
            return 47510704;
        }
        $now = time();
        if ($time < $now) {
            return 47510705;
        }
        return 0;
    }


}
