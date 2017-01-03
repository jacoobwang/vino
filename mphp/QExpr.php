<?php

namespace Mphp;

/**
 * SQL表达式及参数
 * Class QExpression
 * @package Cutephp
 */
class QExpr {
    private static $index = 0;
    public $expression;
    public $params = array();

    /**
     * count => QExpress('count+?', -2)  <==> count = count - :fld0
     * @param $expression
     * @throws \Exception
     */
    public function __construct($expression) {
        $arr = explode('?', $expression);
        $c = count($arr);
        if ($c === 1) {
            $this->expression = $expression;
        } else {
            if ($c !== func_num_args()) {
                throw ExceptionHelper::create('Invalid arguments', 640642);
            }

            $lines = array($arr[0]);

            for ($i = 1; $i < $c; ++$i) {
                $k = ':epr' . ++self::$index;
                $this->params[$k] = func_get_arg($i);
                $lines[] = $k;
                $lines[] = $arr[$i];
            }
            $this->expression = implode(' ', $lines);
        }
    }

    public function __toString() {
        return $this->expression;
    }
}