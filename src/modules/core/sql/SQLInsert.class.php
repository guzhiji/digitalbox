<?php

LoadIBC1Class('SQLFieldValList', 'sql');

/**
 * a GENERAL command generator for INSERT
 * @version 0.4.20110313
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
class SQLInsert implements IFieldValList {

    protected $table;
    protected $valuelist;

    function __construct($t = '') {
        $this->valuelist = new SQLFieldValList();
        $this->SetTable($t);
    }

    public function SetTable($t) {
        $this->table = $t;
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
        $sql_f = '';
        $sql_v = '';
        $this->valuelist->MoveFirst();
        while (list($key, $value) = $this->valuelist->GetEach()) {
            if ($sql_f != '') {
                //$sql_v != ''
                $sql_f.=',';
                $sql_v.=',';
            }
            $sql_f.=$key;
            $sql_v.=$value[0];
        }
        return "INSERT INTO {$this->table} ({$sql_f}) VALUES ({$sql_v})";
    }

}
