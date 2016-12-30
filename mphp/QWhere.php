<?php
namespace Mphp;

/**
 * Where 条件生成器
 * Class QWhere
 * @package Cutephp
 */
class QWhere {
    private $_col_index = 0;
    private $_params = array();
    private $_is_contact = false;
    private $_lines = array();


    /**
     * @return QWhere
     */
    public static function create() {
        return new self();
    }

    public function eq($col, $val) {
        $this->expr($col, '=', $val);
        return $this;
    }

    public function le($col, $val) {
        $this->expr($col, '<=', $val);
        return $this;
    }

    public function lt($col, $val) {
        $this->expr($col, '<', $val);
        return $this;
    }

    public function ge($col, $val) {
        $this->expr($col, '>=', $val);
        return $this;
    }

    public function gt($col, $val) {
        $this->expr($col, '>', $val);
        return $this;
    }

    public function ne($col, $val) {
        $this->expr($col, '!=', $val);
        return $this;
    }

    private function logicSign($is_or = false) {
        if ($this->_is_contact) {
            $logic = $is_or ? ' OR ' : ' AND ';
            $this->_lines[] = $logic;
        }
    }

    private function newHolder() {
        return ':wfld' . $this->_col_index++;
    }

    /**
     * 常规的SQL表达式生成
     * @param $col string 列名
     * @param $sign string 运算符
     * @param $val 值
     */
    private function expr($col, $sign, $val) {
        $holder = $this->newHolder();
        $this->logicSign(false);
        $this->_lines[] = Db::mysqlQuote($col) . " $sign $holder";
        $this->_params[$holder] = $val;
        $this->_is_contact = true;
    }

    public function endWith($col, $val) {
        $this->logicSign();
        $holder = $this->newHolder();
        $this->_lines[] = Db::mysqlQuote($col) . " LIKE CONCAT('%', $holder)";
        $this->_params[$holder] = $val;
        $this->_is_contact = true;
        return $this;
    }

    public function startWith($col, $val) {
        $this->logicSign();
        $holder = $this->newHolder();
        $this->_lines[] = Db::mysqlQuote($col) . " LIKE CONCAT($holder, '%')";
        $this->_params[$holder] = $val;
        $this->_is_contact = true;
        return $this;
    }

    public function isNull($col) {
        $this->logicSign();
        $this->_lines[] = Db::mysqlQuote($col) . ' IS NULL';
        $this->_is_contact = true;
        return $this;
    }

    public function isNotNull($col) {
        $this->logicSign();
        $this->_lines[] = Db::mysqlQuote($col) . ' IS NOT NULL';
        $this->_is_contact = true;
        return $this;
    }

    public function contain($col, $val) {
        $this->logicSign();
        $holder = $this->newHolder();
        $this->_lines[] = Db::mysqlQuote($col) . " LIKE CONCAT('%', $holder, '%')";
        $this->_params[$holder] = $val;
        $this->_is_contact = true;
        return $this;
    }

    /**
     * SQL in 表达式
     * @param $col string 列名
     * @param $val array 值
     * @return $this
     */
    public function in($col, $val) {
        $this->logicSign();
        $holders = array();
        foreach ($val as $v) {
            $holder = $this->newHolder();
            $holders[] = $holder;
            $this->_params[$holder] = $v;
        }
        $this->_lines[] = Db::mysqlQuote($col) . ' IN (' . implode(',', $holders) . ')';
        $this->_is_contact = true;
        return $this;
    }

    public function either() {
        $this->logicSign(true);
        $this->_is_contact = false;
        return $this;
    }

    public function groupBegin() {
        $this->logicSign();
        $this->_lines[] = '(';
        $this->_is_contact = false;
        return $this;
    }

    public function groupEnd() {
        $this->_lines[] = ')';
        $this->_is_contact = true;
        return $this;
    }

    /**
     * 返回where子句数组
     * array( 'uid = :uid', ':uid' => 123,)
     * @return array|string
     */
    public function toSqlArray() {
        $sql = implode('', $this->_lines);
        if (empty($this->_params)) {
            return array($sql);
        } else {
            $ret = $this->_params;
            $ret[0] = $sql;
            return $ret;
        }
    }

}
