<?php

LoadIBC1Class('SQLUpdate', 'sql');

/**
 * a UPDATE statement for MySQL via mysqli.
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
 * $update = $conn->CreateUpdateSTMT('tbl_tag');
 * $update->AddEqual('name', 'hello', IBC1_DATATYPE_PLAINTEXT);
 * $update->AddValue('frequency', 1);
 * $update->Execute();
 * echo $update->GetAffectedRowCount();
 * $update->CloseSTMT();
 * 
 * $conn->CloseDB();
 * </code>
 * @version 0.7.20110315
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql.mysqli
 */
class MySQLiUpdate extends MySQLiSTMT implements IFieldValList, ICondition {

    private $update;
    private $_data = array();
    private $_datafile = array();

    function __construct($t = '', $conn = NULL) {
        parent::__construct($conn);
        $this->update = new SQLUpdate($t);
    }

    public function SetTable($t) {
        $this->update->SetTable($t);
    }

    public function AddCondition($c, $l = IBC1_LOGICAL_AND) {
        $this->update->AddCondition($c, $l);
    }

    public function ClearConditions() {
        $this->update->Clear();
        $this->ClearParams('condition');
    }

    public function ConditionCount() {
        return $this->update->Count();
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
        $this->update->AddCondition("$f=?", $l);
        $this->AddParam($t, $v, 'condition');
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
        $this->update->AddCondition($f . ' LIKE ?', $l);
        $v = '%' . str_replace('%', '\\%', $v) . '%';
        $this->AddParam(IBC1_DATATYPE_PLAINTEXT, $v, 'condition');
    }

    public function AddValue($f, $v, $t = IBC1_DATATYPE_INTEGER) {
        $this->update->AddValue($f, '?', IBC1_DATATYPE_EXPRESSION);
        $this->AddParam($t, $v, 'value');
    }

    public function AddValues(/* ItemList */ $itemlist) {
        $this->update->AddValues($itemlist);
    }

    public function ClearValues() {
        $this->update->ClearValues();
        $this->ClearParams('value');
    }

    public function ValueCount() {
        return $this->update->ValueCount();
    }

    public function SetData($f, $data) {
        $this->_data[] = array($this->ParamCount('value'), $data);
        $this->AddValue($f, NULL, IBC1_DATATYPE_BINARY);
    }

    public function SetDataFromFile($f, $filename) {
        $this->_datafile[] = array($this->ParamCount('value'), $filename);
        $this->AddValue($f, NULL, IBC1_DATATYPE_BINARY);
    }

    public function Execute() {
        if (!$this->connObj) {
            throw new Exception('database unconnected', 4);
        }
        $this->sql = $this->update->GetSQL();
        $this->_prepareSTMT();
        $this->_bindParams(array('value', 'condition'));
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

    public function GetAffectedRowCount() {
        return mysqli_stmt_affected_rows($this->stmtObj);
    }

}
