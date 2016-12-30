<?php
/**
 * Created by PhpStorm.
 * User: jacoob
 * Date: 2015/7/13
 * Time: 15:42
 */

namespace Mphp;


class BaseFormValidate
{
    //预定义规则 添加新的预定义规则只需要把规则名添加到$_preRules,然后定义baseRule方法
    private $_preRules = ['require',
                          'minLen','maxLen','betweenLen',
                          'min','max','between',
                          'email','mobile',
                          'same','reg',];
    //实际需要校验规则
    private $_rules = [];
    //校验结果
    private $_result = true;
    //校验结果信息
    private $_msg = [];
    //待校验的数据
    private $_data = [];


    /**
     * 填充验证规则
     * [
     *   'name'=> [
     *              ['key'=>'name','rule'=>'baseRequire','msg'=>'xxx],
     *              ['key'=>'name','rule'=>'baseMinLen','msg'=>'yyy','ops'=>6],
     *              ['key'=>'name','rule'=>'MaxLen','msg'=>'zzz','ops'=>8], //自定义规则不加前缀
     *              ['key'=>'name','rule'=>'baseBetweenLen','msg'=>'zzz','ops'=>[6,8]],
     *            ],
     *  'email'=> [
     *              ['key=>'email','rule'=>'baseRequire','msg'=>'www],
     *              ['key=>'email','rule'=>'baseEmail', 'msg'=>'vvv'],
     *              ['key=>'email','rule'=>'baseReg', 'msg'=>'uuu','ops'=>'/pattern/iu',
     *            ],
     * ]
     * Base类预定义的rule以'base'打头, 用户自定义的rule以自定义为准,如MaxLen
     * 当rule为正则pattern(被//包含), check时使用preg_match匹配
     * @param $key
     * @param $rule
     * @param $msg
     * @param array $ops
     * @return void
     */
    public function addRule($key, $rule, $msg, $ops=[]) {
        if(!isset($this->_rules[$key])) {
            $this->_rules[$key] = [];
        }
        if(in_array($rule,$this->_preRules)) {
            $prefix = 'base';
        } else {
            $prefix = '';
        }
        $argNum = func_num_args();
        if($argNum==4) {
            $ops = func_get_arg(3);
            $this->_rules[$key][] = ['key'=>$key, 'rule'=>$prefix.ucfirst($rule), 'msg'=>$msg, 'ops'=>$ops];
        } else {
            $this->_rules[$key][] = ['key'=>$key, 'rule'=>$prefix.$rule, 'msg'=>$msg];
        }
    }

    /**
     * require校验,填充校验结果
     * @param $field
     * @return bool
     */
    protected function baseRequire($field) {
        if(is_string($this->_data[$field]) && strlen($this->_data[$field])>0) {
            $result = true;
        } elseif(is_array($this->_data[$field]) && !empty($this->_data[$field])) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    protected function baseMinLen($field, $ops) {
        $len = $ops;
        return mb_strlen($this->_data[$field]) >= $len;
    }

    protected function baseMaxLen($field, $ops) {
        $len = $ops;
        return mb_strlen($this->_data[$field]) <= $len;
    }

    protected function baseBetweenLen($field, $ops) {
        $minLen = $ops[0];
        $maxLen = $ops[1];
        $len = mb_strlen($this->_data[$field]);
        return $len>=$minLen && $len<=$maxLen;
    }

    protected function baseMin($field, $ops) {
        $min = $ops;
        return is_numeric($this->_data[$field]) && $this->_data[$field]>=$min;
    }

    protected function baseMax($field, $ops) {
        $max = $ops;
        return is_numeric($this->_data[$field]) && $this->_data[$field]<=$max;
    }

    protected function baseBetween($field, $ops) {
        $min = $ops[0];
        $max = $ops[1];
        return is_numeric($this->_data[$field]) && $this->_data[$field]>=$min && $this->_data[$field]<=$max;
    }

    protected function baseEmail($field) {
        return filter_var($this->_data[$field],FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function baseMobile($field) {
        $mobile_pattern = '/^1[34578]\d{9}$/';
        return preg_match($mobile_pattern,$this->_data[$field]);
    }

    protected function baseSame($field, $ops) {
        $field2 = $ops;
        return isset($this->_data[$field2]) && $this->_data[$field] === $this->_data[$field2];
    }

    protected function baseReg($field, $ops) {
        $pattern = $ops;
        return preg_match($pattern, $this->_data[$field]);
    }

    /**
     * 循环校验$this->_rules,某一个field校验失败后,纪录msg,更新$this->_result,然后校验下一个field
     * @param $data (Input数据)
     * @return bool ($this->_result)
     */
    public function check($data) {
        $this->_data = $data;
        foreach ($this->_rules as $field=>$rules) {
            foreach($rules as $rule) {
                $func = $rule['rule'];
                $msg = $rule['msg'];
                if(!isset($this->_data[$field])) {
                    $checkResult = false;
                    $msg = "$field not found";
                } else {
                    if(isset($rule['ops'])) {
                        $checkResult = $this->$func($field, $rule['ops']);
                    } else {
                        $checkResult = $this->$func($field);
                    }
                }
                $this->_result = $this->_result && $checkResult;
                if(!$checkResult) {
                    $this->_msg[$field] = $msg;
                    break;
                }
            }
        }

        return $this->_result;
    }

    /**
     * 返回全部出错信息或根据field查询对应出错信息
     * @return array|string
     */
    public function message() {
        $argNum = func_num_args();
        if($argNum==1) {
            $field = func_get_arg(0);
            if(isset($this->_msg[$field])) {
                return $this->_msg[$field];
            } else {
                return '';
            }
        } else {
            return $this->_msg;
        }
    }

    public function getPreRules() {
        return $this->_preRules;
    }

    public function getRules() {
        return $this->_rules;
    }

}
