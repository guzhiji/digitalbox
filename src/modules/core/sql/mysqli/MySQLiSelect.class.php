<?php

LoadIBC1Class('IFieldExpList', 'sql');
LoadIBC1Class('ICondition', 'sql');
LoadIBC1Class('SQLSelect', 'sql');

/**
 * a SELECT statement for MySQL via mysqli
 * @version 0.7.20110315
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.sql.mysqli
 */
class MySQLiSelect extends MySQLiSTMT implements IFieldExpList, ICondition {

    protected $select;
    protected $limit = "";

    function __construct($t = "", &$conn = NULL) {
        parent::__construct($conn);
        $this->select = new SQLSelect($t);
    }

    public function SetTable($t) {
        $this->select->SetTable($t);
    }

    public function JoinTable($t, $on) {
        $this->select->JoinTable($t, $on);
    }

    public function AddField($exp, $alias = "") {
        $this->select->AddField($exp, $alias);
    }

    public function ClearFields() {
        $this->select->ClearFields();
    }

    public function FieldCount() {
        return $this->select->FieldCount();
    }

    public function AddCondition($c, $l = IBC1_LOGICAL_AND) {
        $this->select->AddCondition($c, $l);
    }

    /**
     * specify a condition where the values of the field exactly equal the indicated value
     * @param string $f field name
     * @param mixed $v  value
     * @param int $t
     * Code for types is declared as constants like IBC1_DATATYPE_[type name].
     * It's optional and the default type is integer (IBC1_DATATYPE_INTEGER=0).
     * @param int $l
     * an integer code for logical operators 'AND' and 'OR',
     * which connects the new condition and old ones;<br />
     * constants and their values are listed below:
     * <ul>
     * <li>0 - IBC1_LOGICAL_AND</li>
     * <li>1 - IBC1_LOGICAL_OR</li>
     * </ul>
     */
    public function AddEqual($f, $v, $t = IBC1_DATATYPE_INTEGER, $l = IBC1_LOGICAL_AND) {
        $this->select->AddCondition("$f=?", $l);
        $this->AddParam($t, $v);
    }

    /**
     * specify a condition where the values of the field resemble the indicated value
     * @param string $f field name
     * @param mixed $v  value
     * @param int $l
     * an integer code for logical operators 'AND' and 'OR',
     * which connects the new condition and old ones;<br />
     * constants and their values are listed below:
     * <ul>
     * <li>0 - IBC1_LOGICAL_AND</li>
     * <li>1 - IBC1_LOGICAL_OR</li>
     * </ul>
     */
    public function AddLike($f, $v, $l = IBC1_LOGICAL_AND) {
        $formatter = new DataFormatter($v, IBC1_DATATYPE_PURETEXT);
        if ($this->HasError())
            throw new Exception("the value is malformated", 2);
        $this->select->AddCondition($f . " LIKE " . $formatter->GetSQLValue(TRUE), $l);
    }

    public function ClearConditions() {
        $this->select->ClearConditions();
        $this->ClearParams();
    }

    public function ConditionCount() {
        return $this->select->ConditionCount();
    }

    public function OrderBy($field, $mode = IBC1_ORDER_ASC) {
        $this->select->OrderBy($field, $mode);
    }

    public function GroupBy($field, $having = "") {
        $this->select->GroupBy($field, $having);
    }

    public function SetLimit($PageSize, $PageNumber) {
        $PageNumber = intval($PageNumber);
        if ($PageNumber < 1)
            $PageNumber = 1;
        $length = intval($PageSize);
        if ($length < 1) {
            $this->limit = "";
        } else {
            $start = ($PageNumber - 1) * $length;
            //start from 0
            $this->limit = " LIMIT $start , $length";
        }
    }

    public function Execute() {
        $sql = $this->select->GetSQL();
        if ($this->limit != "")
            $sql.=$this->limit;
        $this->sql = &$sql;
        return parent::Execute();
    }

}
