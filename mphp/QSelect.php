<?php
namespace Mphp;



class QSelect {
    private $_order_by_list = array();
    private $_limit;
    private $_table_name;
    private $_cols = array();
    private $_where = null;
    private $_group_by;
    private $_having;
    private $_params = array();
    private $_col_index = 0;

    public static function create() {
        return new self();
    }


    private function newHolder() {
        return ':gfld' . $this->_col_index++;
    }

    /**
     * 选择的列，支持的格式  'a,b,c' or array('a', 'b')
     * @return $this
     */
    public function select() {
        foreach (func_get_args() as $v) {
            if (is_string($v)) {
                if (strpos($v, ',') !== false) {
                    $arr = explode(',', $v);
                    foreach ($arr as $v2) {
                        $this->addCol($v2);
                    }
                } else {
                    $this->addCol($v);
                }
            } else if (is_array($v)) {
                foreach ($v as $v2) {
                    $this->addCol($v2);
                }
            }
        }
        return $this;
    }

    public function selectAll() {
        $this->_cols[] = '*';
        return $this;
    }

    public function escapeFieldName($col) {
        $col = trim($col);

        if (strpos($col, '`') !== false) {
            return $col;
        }

        if (strpos($col, ' ') !== false) {
            return $col;
        }

        if (strpos($col, '(') !== false) {
            return $col;
        }

        return"`$col`";
    }

    /**
     * 对列名添加反转符号，不处理特殊表达式，sum(price), a as b, a+b
     * @param $col
     * @return string
     */
    private function addCol($col) {
        $this->_cols[] = $this->escapeFieldName($col);
    }

    /**
     * 表名
     * @param $table
     * @return $this
     */
    public function from($table) {
        $this->_table_name = $table;
        return $this;
    }

    /**
     * @param $w string|array|QWhere
     * @return $this
     */
    public function where($w) {
        $this->_where = $w;
        return $this;
    }

    /**
     * Limit 一个参数表示限制数量，两个参数时，1：数量，2：位置
     * @return $this
     */
    public function limit() {
        $this->_limit = func_get_args();
        return $this;
    }

    public function asc($col) {
        $this->_order_by_list[] = Db::mysqlQuote($col) . ' ASC';
        return $this;
    }

    public function desc($col) {
        $this->_order_by_list[] = Db::mysqlQuote($col) . ' DESC';
        return $this;
    }


    public function groupBy($col) {
        $this->_group_by = $this->escapeFieldName($col);;
        return $this;
    }

    /**
     * Having 子句 eg: ('score > ? and level > ?', 98, 2)
     * @param string $expr
     * @param int|string $value
     * @return $this
     * @throws \Exception
     */
    public function having($expr, $value) {
        $lines = array();
        $arr = explode('?', $expr);
        $args = func_get_args();
        array_shift($args);
        $lines[] = array_shift($arr);
        if (count($arr) !== count($args)) {
            throw ExceptionHelper::create('Having sql error', 794332);
        }
        $i = 0;
        while(!empty($arr)) {
            $h = $this->newHolder();
            $lines[] = $h;
            $lines[] = array_shift($arr);
            $this->_params[$h] = $args[$i++];
        }

        $this->_having = implode(' ', $lines);
        return $this;
    }

    public function getParamList() {
        return $this->_params;
    }

    public function toSql() {
        $lines = array('SELECT');
        $lines[] = implode(',', $this->_cols);
        $lines[] = 'FROM';
        $lines[] = $this->_table_name;

        // Where
        if (!empty($this->_where)) {
            $lines[] = 'WHERE';
            if ($this->_where instanceof QWhere) {
                $this->_where = $this->_where->toSqlArray();
            }
            if (is_array($this->_where))  {
                $lines[] = $this->_where[0];
                unset($this->_where[0]);
                if (!empty($this->_where)) {
                    $this->_params = array_merge($this->_params, $this->_where);
                }
            } else {
                $lines[] = $this->_where;
            }
        }

        // Group by
        if (!empty($this->_group_by)) {
            $lines[] = 'GROUP BY ' . $this->_group_by;
        }

        // Having
        if (!empty($this->_having)) {
            $lines[] = 'HAVING';
            $lines[] = $this->_having;
        }

        // Order by
        if (!empty($this->_order_by_list)) {
            $lines[] = 'ORDER BY';
            $lines[] = implode(',', $this->_order_by_list);
        }

        // Limit
        if (!empty($this->_limit)) {
            $lines[] = 'LIMIT';
            $lines[] = implode(', ', $this->_limit);
        }

        return implode(' ', $lines);
    }
}
