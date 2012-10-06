<?php

/**
 * interface for a field list with values and their types
 * (an UPDATE or an INSERT generator has to realize this interface)
 * @version 0.1.20110314
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
interface IFieldValList {

    /**
     * add a field with its value to the list
     * @param string $f field name
     * @param mixed $v  field value
     * @param int $t
     * Code for types is declared as constants like IBC1_DATATYPE_[type name].
     * It's optional and the default type is integer (IBC1_DATATYPE_INTEGER=0).
     */
    public function AddValue($f, $v, $t = IBC1_DATATYPE_INTEGER);

//TODO AddValues
    public function AddValues(DataItem $dataitem);

    /**
     * remove all fields in the list
     */
    public function ClearValues();

    /**
     * get the number of fields in the list
     */
    public function ValueCount();
}
