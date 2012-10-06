<?php

LoadIBC1Class('ICondition', 'sql');
LoadIBC1Class('SQLDelete', 'sql');

/**
 * a DELETE statement for MySQL via mysqli
 * @version 0.7.20110315
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.sql.mysqli
 */
class MySQLiDelete extends MySQLiSTMT implements ICondition {

    private $delete;

    function __construct($t = "", $conn = NULL) {
        parent::__construct($conn);
        $this->delete = new SQLDelete($t);
    }

    public function SetTable($t) {
        $this->delete->SetTable($t);
    }

    public function AddCondition($c, $l = IBC1_LOGICAL_AND) {
        $this->delete->AddCondition($c, $l);
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
        $this->delete->AddCondition("$f=?", $l);
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
        $this->delete->AddCondition($f . " LIKE " . $formatter->GetSQLValue(TRUE), $l);
    }

    public function ClearConditions() {
        $this->delete->ClearConditions();
        $this->ClearParams();
    }

    public function ConditionCount() {
        return $this->delete->ConditionCount();
    }

    public function Execute() {
        $this->sql = $this->delete->GetSQL();
        return parent::Execute();
    }

}
