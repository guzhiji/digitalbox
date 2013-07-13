<?php

LoadIBC1Class('SQLInsert', 'sql');

/**
 * a INSERT statement for MySQL via mysqli.
 * 
 * Here is an example:
 * <code>
 * require 'core1.lib.php';
 * require 'core.conf.php';
 * LoadIBC1Lib('common', 'sql');
 * 
 * $connp = new DBConnProvider();
 * $conn = $connp->OpenConn('localhost', 'root', '', 'test');
 * 
 * $insert = $conn->CreateInsertSTMT('tbl_tag');
 * $insert->AddValue('name', 'hello', IBC1_DATATYPE_PLAINTEXT);
 * $insert->AddValue('frequency', 0);
 * $insert->Execute();
 * echo $insert->GetLastInsertID();
 * $insert->CloseSTMT();
 * 
 * $conn->CloseDB();
 * </code>
 * @version 0.7.20110315
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql.mysqli
 */
class MySQLiInsert extends MySQLiSTMT implements IFieldValList {

    private $insert;
    private $_data = array();
    private $_datafile = array();

    function __construct($t = '', $conn = NULL) {
        parent::__construct($conn);
        $this->insert = new SQLInsert($t);
    }

    public function SetTable($t) {
        $this->insert->SetTable($t);
    }

    public function AddValue($f, $v, $t = IBC1_DATATYPE_INTEGER) {
        $this->insert->AddValue($f, '?', IBC1_DATATYPE_EXPRESSION);
        $this->AddParam($t, $v);
    }

    public function AddValues(/* ItemList */ $itemlist) {
        $this->insert->AddValues($itemlist);
    }

    public function ClearValues() {
        $this->insert->ClearValues();
        $this->ClearParams();
    }

    public function ValueCount() {
        return $this->insert->ValueCount();
    }

    public function SetData($f, $data) {
        $this->_data[] = array($this->ParamCount(), $data);
        $this->AddValue($f, NULL, IBC1_DATATYPE_BINARY);
    }

    public function SetDataFromFile($f, $filename) {
        $this->_datafile[] = array($this->ParamCount(), $filename);
        $this->AddValue($f, NULL, IBC1_DATATYPE_BINARY);
    }

    public function GetAffectedRowCount() {
        return mysqli_stmt_affected_rows($this->stmtObj);
    }

    public function GetLastInsertID() {
        return mysqli_stmt_insert_id($this->stmtObj);
    }

    public function Execute() {
        if (!$this->connObj) {
            throw new Exception('database unconnected', 4);
        }
        $this->sql = $this->insert->GetSQL();
        $this->_prepareSTMT();
        $this->_bindParams();
        //send long data
        foreach ($this->_data as $item) {
            mysqli_stmt_send_long_data($this->stmtObj, $item[0], $item[1]);
        }
        //send long data from file
        foreach ($this->_datafile as $item) {
            $fp = fopen($item[1], 'r');
            while (!feof($fp)) {
                mysqli_stmt_send_long_data($this->stmtObj, $item[0], fread($fp, 1024 * 8));
            }
            fclose($fp);
        }
        if (!mysqli_stmt_execute($this->stmtObj)) {
            throw new Exception(mysqli_stmt_error($this->stmtObj), 4);
        }
    }

}
