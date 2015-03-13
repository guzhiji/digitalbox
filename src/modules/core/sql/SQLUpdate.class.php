<?php

LoadIBC1Class('SQLFieldValList', 'sql');
LoadIBC1Class('SQLCondition', 'sql');

/**
 * a GENERAL command generator for UPDATE
 * @version 0.7.20110315
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
class SQLUpdate implements IFieldValList, ICondition {

    protected $table;
    protected $valuelist;
    protected $condition;

    function __construct($t = '') {
        $this->valuelist = new SQLFieldValList();
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

    public function AddValue($f, $v, $t = IBC1_DATATYPE_INTEGER) {
        $this->valuelist->AddValue($f, $v, $t);
    }

    public function AddValues(/* ItemList */ $itemlist) {
        $itemlist->MoveFirst();
        while (list($key, $item) = $itemlist->GetEach()) {
            $this->AddValue($key, $item[0], $item[1]);
        }
    }

    public function ClearValues() {
        $this->valuelist->Clear();
    }

    public function ValueCount() {
        return $this->valuelist->Count();
    }

    public function GetSQL() {
        $sql_v = '';
        $this->valuelist->MoveFirst();
        while (list($key, $value) = $this->valuelist->GetEach()) {
            if ($sql_v != '')
                $sql_v.=',';
            $sql_v.=$key . '=' . $value[0];
        }
        $sql = "UPDATE {$this->table} SET {$sql_v}";

        $sql_c = $this->condition->GetExpression();
        if ($sql_c != '')
            $sql.=" WHERE {$sql_c}";
        return $sql;
    }

}
