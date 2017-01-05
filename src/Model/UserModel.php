<?php
/**
 * Created by PhpStorm..
 * User: jacoob
 * Date: 9/7/15
 * Time: 2:54 PM
 */

namespace Ecc\Topic\Model;

use Mphp\App;
use Mphp\Db;
use Mphp\QSelect;
use Mphp\QWhere;

/**
 * User Db Model
 *
 * Class UserModel
 * @package Ecc\Topic\Model
 */
class UserModel
{
    private $di;
    private $model;

    /**
     * @var string
     */
    private $table = 'ecc_user';

    public function __construct(){
        $this->di = App::getSingleton()->getSingleton()->di();
        $this->model = $this->di->get('db');
    }

    /**
     * insert one user record
     *
     * @param string $nickname
     * @param string $password
     * @param string $email
     * @return boolean
     */
    public function add($nickname, $password, $email){
        $data = array(
            'nickname' => $nickname,
            'password' => md5($password),
            'email'    => $email,
            'cdate'    => App::getSingleton()->getDatetime(),
        );
        return $this->model->insert($this->table, $data) == 1;
    }

    /**
     * update user record
     *
     * @param string $nickname
     * @param string $password
     * @param string $email
     * @param integer $id
     * @return integer
     */
    public function update($nickname, $password, $email, $id){
        $data = [
            'nickname' => $nickname,
            'password' => $password,
            'email'    => $email,
        ];
        return $this->model->update($this->table, $data, QWhere::create()->eq('id',$id));
    }

    /**
     * delete user record
     *
     * @param string  $id
     * @param integer $limit
     * @return mixed
     */
    public function delete($id, $limit){
        return $this->model->delete($this->table, QWhere::create()->eq('id',$id), $limit);
    }

    /**
     * check username exists
     *
     * @param string $nickname
     * @return boolean
     */
    public function isNicknameExists($nickname){
        $sel = QSelect::create()
        ->select('id')
        ->from($this->table)
        ->where(QWhere::create()->eq('nickname', $nickname));

        $data = $this->model->fetchRow($sel);
        return !empty($data);
    }

    /**
     * get record according column
     *
     * @param string $colmn table column
     * @param string $val  value
     * @return mixed
     */
    public function getOne($colmn, $val){
        $sel = QSelect::create()
        ->selectAll()
        ->from($this->table)
        ->where(QWhere::create()->eq($colmn, $val));

        $data = $this->model->fetchRow($sel);
        return $data;
    }

}
