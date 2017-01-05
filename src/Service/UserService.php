<?php
/**
 * Created by PhpStorm..
 * User: jacoob
 * Date: 1/1/17
 * Time: 9:21 PM
 */

namespace Ecc\Topic\Service;

use Ecc\Topic\Model\UserModel;

/**
 * UserService is a class that handle user function collection,it's support functions use mysql
 *
 * Class UserService
 * @package Ecc\Topic\Service
 */
class UserService
{
	
	private $instance;

	public function __construct(){
		$this->instance = new UserModel();
	}

    /**
     * check user + password is match
     *
     * @param string $user
     * @param string $pwd
     * @return boolean
     */
	public function findUser($user, $pwd){
		$ret = $this->instance->getOne('nickname', $user);
		if ($ret) {
			$password = $ret['password'];
			return md5($pwd) === $password;
		} 
		return false;
	}

	/**
	 * check user is exists
     *
     * @param string $user
     * @return boolean
     */
	public function validateUsername($user){
		return $this->instance->isNicknameExists($user);
	}

    /**
     * reg user
     *
     * @param string $nickname
     * @param string $password
     * @param string $email
     * @return boolean
     */
	public function addUser($nickname, $password, $email){
		return $this->instance->add($nickname, $password, $email);
	}

    /**
     * get userInfo by id
     *
     * @param integer $id
     * @return mixed|null
     */
	public function getUserById($id){
		return $this->instance->getOne('id', $id);
	}

}

?>