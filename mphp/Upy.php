<?php 
namespace Mphp;

class Upy {

    private $_key = '';
    private $_apiDomain = '';

    private $_params = [];
    private $_saveKey = '/{year}/{mon}{day}/{filemd5}{.suffix}';
    private $_expiration = 0;
    private $_policy = '';
    private $_signature = '';

    public function __construct($bucket,$key) {
        $this->_key = $key;
        $this->_apiDomain = 'http://v0.api.upyun.com/'.$bucket;
        
        $this->_params['bucket'] = $bucket;
        $this->_params['expiration'] = time()+1800;
        $this->_params['save-key'] = $this->_saveKey;
        $this->genPolicy();
        $this->genSignature();
    }

    public function setParams($data) {
        $this->_params = array_merge($this->_params,$data);
        $this->genPolicy();
        $this->genSignature();
    }

    public function getPolicy() {
        return $this->_policy;
    }

    public function getSignature() {
        return $this->_signature;
    }

    public function getSavekey() {
        return $this->_params['save-key'];
    }

    public function getApiDomain() {
        return $this->_apiDomain;
    }

    private function genPolicy() {
        $this->_policy = base64_encode(json_encode($this->_params));
    }

    private function genSignature() {
        $this->_signature = md5($this->_policy.'&'.$this->_key);
    }
}
?>
