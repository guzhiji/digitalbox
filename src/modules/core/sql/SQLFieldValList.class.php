<?php

LoadIBC1Class('PropertyList', 'util');

/**
 * field list with values and their types
 * @version 0.3.20110327
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
class SQLFieldValList extends PropertyList {

    /**
     * add a field with its value to the list
     * @param string $f field name
     * @param mixed $v  field value
     * @param int $t
     * Code for types is declared as constants like IBC1_DATATYPE_[type name].
     * It's optional and the default type is integer (IBC1_DATATYPE_INTEGER=0).
     */
    public function AddValue($f, $v, $t = IBC1_DATATYPE_INTEGER) {
        if ($t >= IBC1_DATATYPE_PLAINTEXT && $t != IBC1_DATATYPE_EXPRESSION)
            $v = toScriptString($v);
        $this->SetValue($f, $v, $t);
    }

}
