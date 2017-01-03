<?php
/**
 * Created by PhpStorm..
 * User: jacoob
 * Date: 1/1/17
 * Time: 9:21 PM
 */

namespace Ecc\Topic\Service;

use Ecc\Topic\Model\UserModel;

class UserService
{
	
	private $_instance;	

	public function __construct(){
		$this->_instance = new UserModel();
	}

	/**
	 * 通过用户名＋密码验证是否准确
	 * return boolen true|false
	**/
	public function validateUserPwd($user, $pwd){
		$ret = $this->_instance->getOne('nickname', $user);
		if ($ret) {
			$password = $ret['password'] || false;
			if(md5($pwd) == $password) {
				//succ
				return true;
			}   
        } 
        return false;
	}

	/**
	 * 验证用户名是否存在
	 * return boolen true|false
	**/
	public function validateNicknameExists($user){
		$ret = $this->_instance->isExists($user);
		if($ret) {
			return true;
		}
		return false;
	}

	/**
	 * 注册用户
	**/
	public function reg($nickname, $password, $email){
		return $this->_instance->add($nickname, $password, $email);
	}

	/**
	 * 通过id获取用户信息
	**/
	public function getUserInfoById($id){
		return $this->_instance->getOne('id', $id);
	}

}

?>