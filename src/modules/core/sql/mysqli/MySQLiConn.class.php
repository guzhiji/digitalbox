<?php

LoadIBC1Class('MySQLiSTMT', 'sql.mysqli');

/**
 * database connection via mysqli.
 * 
 * @version 0.8.20110313
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql.mysqli
 */
class MySQLiConn extends DBConn {

    /**
     * create a connection to a MySQL server and open database.
     * 
     * @param string $host
     * the address of MySQL server;
     * if the database is running on a special port,
     * add it to the end of the string with a colon to separate
     * @param string $user
     * @param string $pass
     * @param string $db
     */
    public function OpenDB($host, $user, $pass, $db) {
        $h = explode(':', $host);
        if (count($h) == 1) {
            $r = mysqli_connect($host, $user, $pass, $db);
        } else {
            $r = mysqli_connect($h[0], $user, $pass, $db, $h[1]);
        }
        if (!$r) {
            $this->CloseDB();
            throw new Exception('fail to connect to database', 1);
        }
        $this->connObj = $r;
        $this->dbname = $db;
        $this->hostname = $host;
        $this->username = $user;
    }

    public function CloseDB() {
        @mysqli_close($this->connObj);
        parent::CloseDB();
    }

    public function GetDBDriver() {
        return 'mysqli';
    }

    /**
     * create a Select statement.
     * 
     * @param string $tablename
     * @return MySQLiSelect 
     */
    public function CreateSelectSTMT($tablename = '') {
        LoadIBC1Class('MySQLiSelect', 'sql.mysqli');
        return new MySQLiSelect($tablename, $this->connObj);
    }

    /**
     * create an Insert statement.
     * 
     * @param string $tablename
     * @return MySQLiInsert 
     */
    public function CreateInsertSTMT($tablename = '') {
        LoadIBC1Class('MySQLiInsert', 'sql.mysqli');
        return new MySQLiInsert($tablename, $this->connObj);
    }

    /**
     * create an Update statement.
     * 
     * @param string $tablename
     * @return MySQLiUpdate 
     */
    public function CreateUpdateSTMT($tablename = '') {
        LoadIBC1Class('MySQLiUpdate', 'sql.mysqli');
        return new MySQLiUpdate($tablename, $this->connObj);
    }

    /**
     * create a Delete statement.
     * 
     * @param string $tablename
     * @return MySQLiDelete 
     */
    public function CreateDeleteSTMT($tablename = '') {
        LoadIBC1Class('MySQLiDelete', 'sql.mysqli');
        return new MySQLiDelete($tablename, $this->connObj);
    }

    /**
     * create a statement that modifies a table.
     * 
     * @param string $mode
     * -create
     * -drop
     * -optimize
     * -addcolumn
     * -dropcolumn
     * @param string $tablename
     * @return MySQLiTable 
     */
    public function CreateTableSTMT($mode, $tablename = '') {
        LoadIBC1Class('MySQLiTable', 'sql.mysqli');
        return new MySQLiTable($mode, $tablename, $this->connObj);
    }

    /**
     * create a custom SQL statement.
     * 
     * @param string $sql
     * @return MySQLiSTMT 
     */
    public function CreateSTMT($sql = NULL) {
        return new MySQLiSTMT($this->connObj, $sql);
    }

    public function TableExists($table) {
        if (!ValidateTableName($table))
            return FALSE;
        $sql = $this->CreateSTMT('SHOW TABLES FROM ' . $this->GetDBName() . " LIKE \"$table\";");
        $sql->Execute();
        $r = $sql->Fetch();
        $sql->CloseSTMT();
        return (!!$r);
    }

    public function FieldExists($table, $field) {
        if (!ValidateTableName($table))
            return FALSE;
        if (!ValidateFieldName($field))
            return FALSE;
        $sql = $this->CreateSTMT('SHOW COLUMNS FROM ' . $this->GetDBName() . '.' . $table . " LIKE \"$field\";");
        $sql->Execute();
        $r = $sql->Fetch();
        $sql->CloseSTMT();
        return (!!$r);
    }

    public function GetTableList() {
        $sql = $this->CreateSTMT('SHOW TABLES FROM ' . $this->GetDBName());
        $sql->Execute();
        //TODO: generate a table list
    }

    public function GetFieldList($table) {
        if (!ValidateTableName($table))
            return FALSE;
        $sql = $this->CreateSTMT('SHOW COLUMNS FROM ' . $this->GetDBName() . '.' . $table);
        $sql->Execute();
        //TODO: generate a field list
    }

}

/*
  public function SetRowNumber($i) {
  //return @mysqli_data_seek($this->QueryID,$i);
  mysqli_stmt_data_seek($this->dbSTMT, $i);
  }

  public function GetRowCount() {
  //return @mysqli_num_rows($this->QueryID);
  return mysqli_stmt_num_rows($this->dbSTMT);
  }

 */
