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

class UserModel
{
    private $di;

    /**
     * @var string
     */
    private $table = 'ecc_user';

    public function __construct()
    {
        $this->di = App::getSingleton()->getSingleton()->di();
    }

    /**
     * @return Db
     * @throws \Exception
     */
    private function getDb()
    {
        return $this->di->get('db');
    }

    /**
     * @return string
     */
    private function getTableName()
    {
        return $this->table;
    }

    function add($nickname, $password, $email)
    {
        $data = array(
            'nickname' => $nickname,
            'password' => md5($password),
            'email'    => $email,
            'cdate'    => App::getSingleton()->getDatetime(),
        );
        return $this->getDb()->insert($this->getTableName(), $data) == 1;
    }

    function update($nickname, $password, $email, $id)
    {
        $data = [
            'nickname' => $nickname,
            'password' => $password,
            'email'    => $email,
        ];
        return $this->getDb()->update($this->getTableName(), $data, QWhere::create()->eq('id',$id));
    }

    function isExists($nickname)
    {
        $sel = QSelect::create()
        ->select('id')
        ->from($this->getTableName())
        ->where(QWhere::create()->eq('nickname', $nickname));

        $data = $this->getDb()->fetchRow($sel);
        return $data;
    }

    function getOne($colmn, $val)
    {
        $sel = QSelect::create()
        ->selectAll()
        ->from($this->getTableName())
        ->where(QWhere::create()->eq($colmn, $val));

        $data = $this->getDb()->fetchRow($sel);
        return $data;
    }

}
