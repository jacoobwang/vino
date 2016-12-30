<?php

namespace Mphp;


class Db {
    private static $db_connections = array();


    /**
     * @static
     * @param string $key
     * @return Db
     * @throws Exception
     */
    public static function getConnection($key = 'm') {
        if (array_key_exists($key, self::$db_connections)) {
            return self::$db_connections[$key];
        }

        $di = App::getSingleton()->di('config');
        $config = $di->get('db/' . $key);
        if ($config == null) {
            throw new Exception('Database config error', -100002);
        }


        $conn = new Db($config['dsn'], $config['username'], $config['password']);
        self::$db_connections[$key] = $conn;
        return $conn;
    }


    const PARAM_PREFIX = ':fld';
    private $conn = null;
    private $fetch_style = \PDO::FETCH_ASSOC;
    private $_error = array();
    private $_last_sql = array();


    function __construct($dsn, $user, $password) {
        try {
            $this->conn = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            throw new ExceptionHelper('DB error: ' . $e->getMessage(), 500);
        }
        $this->setCharset();
    }

    public function getError() {
        return $this->_error;
    }

    private function dbError($err_info, $line) {
        if (defined('DEBUG') && DEBUG) {
            print_r(debug_backtrace());
        }
        throw ExceptionHelper::create('DB error: ' . var_export($this->_last_sql, true) . '|' . implode('|', $err_info) . ' Line: ' . $line, 936084);
    }

    public function getLastSql() {
        return $this->_last_sql;
    }

    private function setLastSql($sql, $bind_data = array()) {
        $this->_last_sql = array($sql, $bind_data);
    }

    public function setCharset($charset = 'utf8mb4') {
        $this->execute("SET NAMES '{$charset}';");
    }

    public function execute($sql, $params = null) {
        if ($sql instanceof QSelect) {
            $sql2 = $sql->toSql();
            $params = $sql->getParamList();
            $sql = $sql2;
        }
        if ($params == null) {
            // 不带参数的SQL
            $this->setLastSql($sql);
            $rows = $this->conn->exec($sql);
            if ($rows === false) {
                $this->dbError($this->conn->errorInfo(), __LINE__);
            }
        } else {
            $this->setLastSql($sql, $params);
            $cmd = $this->conn->prepare($sql);
            if ($cmd === false) {
                $this->dbError($this->conn->errorInfo(), __LINE__);
            }
            $cmd->execute($params);
            if ($cmd->errorCode() != '000') {
                $this->dbError($cmd->errorInfo(), __LINE__);
            }
            $rows = $cmd->rowCount();
            $cmd->closeCursor();
        }
        return $rows;
    }

    private function _query($sql, $params = null) {
        if ($sql instanceof QSelect) {
            $sql2 = $sql->toSql();
            $params = $sql->getParamList();
            $sql = $sql2;
        }
        if ($params == null) {
            $this->setLastSql($sql);
            $cmd = $this->conn->query($sql);
            if ($cmd === false) {
                $this->dbError($this->conn->errorInfo(), __LINE__);
            }
            $cmd->execute();
        } else {
            $this->setLastSql($sql, $params);
            $cmd = $this->conn->prepare($sql);
            if ($cmd === false) {
                $this->dbError($this->conn->errorInfo(), __LINE__);
            }
            $cmd->execute($params);
        }
        // 直接通过错误码判断是否成功，无错误则表示成功
        if ($cmd->errorCode() != '000') {
            $this->dbError($cmd->errorInfo(), __LINE__);
        }
        return $cmd;
    }

    public function fetchVar($sql, $params = null) {
        if ($sql instanceof QSelect) {
            $sql->limit(1);
        }
        $cmd = $this->_query($sql, $params);
        $r = $cmd->fetch(\PDO::FETCH_NUM);
        $cmd->closeCursor();
        return ($r === false) ? null : $r[0];
    }

    public function fetchRow($sql, $params = null) {
        if ($sql instanceof QSelect) {
            $sql->limit(1);
        }
        $cmd = $this->_query($sql, $params);
        $r = $cmd->fetch($this->fetch_style);
        $cmd->closeCursor();
        return ($r === false) ? null : $r;
    }

    public function fetchCol($sql, $params = null, $col = 0) {
        $cmd = $this->_query($sql, $params);
        $result = $cmd->fetchAll(\PDO::FETCH_COLUMN, $col);
        $cmd->closeCursor();
        return empty($result) ? null : $result;
    }

    public function fetchAll($sql, $params = null) {
        $cmd = $this->_query($sql, $params);
        $result = $cmd->fetchAll($this->fetch_style);
        $cmd->closeCursor();
        return empty($result) ? null : $result;
    }

    public function quote($value) {
        return $this->conn->quote($value);
    }

    public static function mysqlQuote($val) {
        if (strpos($val, '.') !== FALSE) {
            return $val;
        }
        return "`$val`";
    }

    public static function escapeSearch($str) {
        return strtr($str, array('%' => '\%', '_' => '\_', '\\' => '\\\\'));
    }

    public function applyCondition($sql, &$params, $where) {
        if ($where instanceof QWhere) {
            $where = $where->toSqlArray();
        }
        if (is_array($where)) {
            $condition = $where[0];
            unset($where[0]);
            if (!empty($where)) {
                $params = array_merge($params, $where);
            }
        } else {
            $condition = $where;
        }
        $sql = $sql . ' WHERE ' . $condition;
        return $sql;
    }


    /**
     * 插入数据，允许多值
     * @param $table
     * @param $data array('uid'=>1, 'name'=>'dayu') | array(array('uid'=>1, 'name'=>'dayu'), array('uid'=>2, 'name'=>'yugw'))
     * @return int 返回写入记录数量
     */
    public function insert($table, $data) {
        $table = self::mysqlQuote($table);
        $values = array();
        $placeholders = array();
        $i = 0;
        $dt = is_array(current($data)) ? $data : array($data);
        // 对键名进行排序
        $v = $dt[0];
        ksort($v);
        $fields = array_keys($v);
        foreach ($dt as $data) {
            ksort($data);
            $holders = array();
            foreach ($data as $v) {
                if ($v instanceof QExpr) {
                    $holders[] = $v->expression;
                    foreach ($v->params as $n => $v2)
                        $values[$n] = $v2;
                } else {
                    $holders[] = self::PARAM_PREFIX . $i;
                    $values[self::PARAM_PREFIX . $i] = $v;
                    $i++;
                }
            }
            $placeholders[] = implode(', ', $holders);
        }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $fields) . '`) VALUES (' . implode('), (', $placeholders) . ')';
        //		$this->set_last_sql( $sql, $values );
        //		$cmd = $this->conn->prepare($sql);
        //		foreach($values as $name=>$value)
        //		{
        //			$cmd->bindValue($name,$value);
        //		}
        //		$ret = $cmd->execute();
        $ret = $this->execute($sql, $values);
        return $ret;
    }

    /**
     * $where 可以是字符串，或者类似的数组
     * array('id>:id and cid = :cid', ':id'=>5, ':cid'=> 10)
     * 数组的第一个元素是带参数SQL语句，之后的键值配对元素是需要绑定的参数
     * @param $table
     * @param $data
     * @param $where
     * @param int $limit
     * @return int 受影响的记录数
     */
    public function update($table, $data, $where = null, $limit = 0) {
        $table = self::mysqlQuote($table);
        $fields = array();
        $values = array();
        $i = 0;
        foreach ($data as $k => $v) {
            if ($v instanceof QExpr) {
                $fields[] = "`$k`=" . $v->expression;
                foreach ($v->params as $n => $v2)
                    $values[$n] = $v2;
            } else {
                $fields[] = "`$k`=" . self::PARAM_PREFIX . $i;
                $values[self::PARAM_PREFIX . $i] = $v;
                $i++;
            }
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $fields);
        if (!empty($where)) {
            $sql = $this->applyCondition($sql, $values, $where);
        }
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }
        $ret = $this->execute($sql, $values);
        return $ret;
    }

    public function delete($table, $where, $limit = 0) {
        $table = self::mysqlQuote($table);
        $sql = "DELETE FROM {$table} ";
        $values = array();
        $sql = $this->applyCondition($sql, $values, $where);
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }
        $ret = $this->execute($sql, $values);
        return $ret;
    }

    public function getInsertId() {
        return $this->conn->lastInsertId();
    }

    public function beginTran() {
        return $this->conn->beginTransaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollback() {
        return $this->conn->rollBack();
    }


}
