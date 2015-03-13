<?php

/**
 * a GENERAL command generator for DELETE
 * @version 0.7.20110315
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
class SQLDelete implements ICondition {

    protected $table;
    protected $condition;

    function __construct($t = '') {
        $this->condition = new SQLCondition();
        $this->SetTable($t);
    }

    public function SetTable($t) {
        $this->table = $t;
    }

    public function AddCondition($c, $l = IBC1_LOGICAL_AND) {
        $this->condition->AddCondition($c, $l);
    }

    public function ClearConditions() {
        $this->condition->Clear();
    }

    public function ConditionCount() {
        return $this->condition->Count();
    }

    public function GetSQL() {
        $sql = 'DELETE FROM ' . $this->table;
        $sql_c = $this->condition->GetExpression();
        if ($sql_c != '')
            $sql.=' WHERE ' . $sql_c;

        return $sql;
    }

}
