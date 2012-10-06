<?php

/**
 * database connection
 * @version 0.6.20110417
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
abstract class DBConn {

    protected $dbname = "";
    protected $hostname = "";
    protected $username = "";
    protected $connObj = NULL;

    function __construct($host, $user, $pass, $db) {
        $this->OpenDB($host, $user, $pass, $db);
    }

    function __destruct() {
        $this->CloseDB();
    }

    public function IsConnected() {
        return $this->connObj !== NULL;
    }

    public abstract function GetDBDriver();

    public abstract function OpenDB($host, $user, $pass, $db);

    /**
     * close database connection and erase connection information like hostname
     *
     * The actual operation to close database has not been implemented
     * and requires to be realized in the subclasses.
     * This method currently only releases connection resources.
     * Therefore, database connection must be closed before
     * calling this method in its subclasses.
     */
    public function CloseDB() {
        $this->dbname = "";
        $this->hostname = "";
        $this->username = "";
        $this->connObj = NULL;
    }

    public function GetDBName() {
        return $this->dbname;
    }

    public function GetHostName() {
        return $this->hostname;
    }

    public function GetUserName() {
        return $this->username;
    }

    public abstract function CreateSelectSTMT();

    public abstract function CreateInsertSTMT();

    public abstract function CreateUpdateSTMT();

    public abstract function CreateDeleteSTMT();

    public abstract function CreateTableSTMT($mode);

    public abstract function CreateSTMT($sql = NULL);

//TODO: the following methods are being considered whether in this class or in TableSTMT
    public abstract function TableExists($table);

    public abstract function FieldExists($table, $field);
    //public abstract function GetTableList();
    //public abstract function GetFieldList($table);
    //public abstract function Execute($sql="");
    //public abstract function CloseSTMT();
    //public abstract function Fetch($t=0);
    //public abstract function SetRowNumber($i);
    //public abstract function GetRowCount();
    //public abstract function GetLastInsertID();
    //public abstract function GetAffectedRowCount();
}
