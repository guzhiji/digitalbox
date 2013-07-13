<?php

define('IBC1_DATATYPE_INTEGER', 0);
define('IBC1_DATATYPE_DECIMAL', 1);
define('IBC1_DATATYPE_PLAINTEXT', 2); //ensure the first string-type
define('IBC1_DATATYPE_RICHTEXT', 3);
define('IBC1_DATATYPE_DATETIME', 4);
define('IBC1_DATATYPE_DATE', 5);
define('IBC1_DATATYPE_TIME', 6);
define('IBC1_DATATYPE_URL', 7);
define('IBC1_DATATYPE_EMAIL', 8);
define('IBC1_DATATYPE_PWD', 9);
define('IBC1_DATATYPE_WORDLIST', 10);
define('IBC1_DATATYPE_BINARY', 11);
define('IBC1_DATATYPE_EXPRESSION', 12);

define('IBC1_LOGICAL_AND', 0);
define('IBC1_LOGICAL_OR', 1);

define('IBC1_ORDER_ASC', 0);
define('IBC1_ORDER_DESC', 1);

define('IBC1_VALUEMODE_VALUEONLY', 0);
define('IBC1_VALUEMODE_TYPEONLY', 1);
define('IBC1_VALUEMODE_ALL', 2);

$GLOBALS['IBC1_DATA_FORMATTERS'] = array(
    IBC1_DATATYPE_INTEGER => 'intval',
    IBC1_DATATYPE_DECIMAL => 'floatval',
    IBC1_DATATYPE_PLAINTEXT => 'text2html',
    IBC1_DATATYPE_RICHTEXT => 'filterhtml',
    IBC1_DATATYPE_DATETIME => 'FormatDateTime',
    IBC1_DATATYPE_DATE => 'FormatDate',
    IBC1_DATATYPE_TIME => 'FormatTime',
    IBC1_DATATYPE_WORDLIST => ''
);

$GLOBALS['IBC1_DATA_VALIDATORS'] = array(
    IBC1_DATATYPE_INTEGER => 'ValidateInt',
    IBC1_DATATYPE_DECIMAL => 'ValidateFloat',
    IBC1_DATATYPE_DATETIME => '',
    IBC1_DATATYPE_DATE => '',
    IBC1_DATATYPE_TIME => '',
    IBC1_DATATYPE_URL => 'ValidateURL',
    IBC1_DATATYPE_EMAIL => 'ValidateEMail',
    IBC1_DATATYPE_PWD => 'ValidatePWD'
);

function ValidateInt($int) {
    return intval($int) == $int;
}

function ValidateFloat($float) {
    return floatval($float) == $float;
}

function ValidateURL($url) {
    return (!!preg_match('/^(\w+):\/\/([^/:]+)(:\d*)?([^# ]*)$/i', $url));
}

function ValidateEMail($email) {
    return(!!preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/i', $email));
}

function ValidateUID($uid) {
    return(!!preg_match('/^[0-9a-z_]{3,256}$/i', $uid));
}

function ValidatePWD($pwd) {
    return (!!preg_match('/^[0-9a-z]{6,}$/i', $pwd));
}

function ValidateFieldName($fieldname) {
    return (!!preg_match('/^[a-z_][0-9a-z_]{0,63}$/i', $fieldname));
}

function ValidateTableName($tablename) {
    return (!!preg_match('/^[a-z_][0-9a-z_]{0,63}$/i', $tablename));
}

function ValidateServiceName($fieldname) {
    return (!!preg_match('/^[0-9a-z_]{0,32}$/i', $fieldname));
}

/**
 * provider of all database connections
 * @version 0.9.20121121
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
class DBConnProvider {

    /**
     * find the required database connector module,
     * and then open a connection to the database.
     * 
     * @param string $host
     * If database is running on a special port,
     * add to the end of this string with a colon separated.
     * e.g. 
     * <code>
     * $obj->OpenDB("localhost:10000","root","","mydb");
     * </code>
     * @param string $user
     * @param string $pwd
     * @param string $db
     * @param string $dbdriver
     * Currently, only "mysqli" is fully supported and it is the default value.
     * @return DBConn 
     */
    public function OpenConn($host, $user, $pwd, $db, $dbdriver = '') {
        LoadIBC1File('drivers.config.php', 'sql');
        if (empty($dbdriver))
            $dbdriver = IBC1_DEFAULT_DBDRIVER;
        $drivers = &$GLOBALS['IBC1_DB_DRIVERS'];
        if (isset($drivers) && isset($drivers[$dbdriver])) {
            $class = $drivers[$dbdriver];
            LoadIBC1Class($class, 'sql.' . $dbdriver);
            return new $class($host, $user, $pwd, $db);
        } else {
            throw new Exception("cannot find the db connector module for {$dbdriver}");
        }
    }

}

/**
 * The abstract database connector
 * 
 * @version 0.6.20110417
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
abstract class DBConn {

    protected $dbname = '';
    protected $hostname = '';
    protected $username = '';
    protected $connObj = NULL;

    function __construct($host, $user, $pass, $db) {
        $this->OpenDB($host, $user, $pass, $db);
    }

    function __destruct() {
        $this->CloseDB();
    }

    /**
     * test if a database connection is established.
     * 
     * @return bool 
     */
    public function IsConnected() {
        return $this->connObj !== NULL;
    }

    /**
     * get name of the driver module.
     * 
     * @return string 
     */
    public abstract function GetDBDriver();

    /**
     * create a connection to a database server and open database.
     * 
     * @param string $host
     * the address of database server;
     * if the database is running on a special port,
     * add it to the end of the string with a colon to separate
     * @param string $user
     * @param string $pass
     * @param string $db
     */
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
        $this->dbname = '';
        $this->hostname = '';
        $this->username = '';
        $this->connObj = NULL;
    }

    /**
     * get the name of the opened database.
     * 
     * @return string 
     */
    public function GetDBName() {
        return $this->dbname;
    }

    /**
     * get the host name of the opened database.
     * 
     * @return string 
     */
    public function GetHostName() {
        return $this->hostname;
    }

    /**
     * get the user name who owns the current database connection.
     * 
     * @return string 
     */
    public function GetUserName() {
        return $this->username;
    }

    public abstract function CreateSelectSTMT($tablename = '');

    public abstract function CreateInsertSTMT($tablename = '');

    public abstract function CreateUpdateSTMT($tablename = '');

    public abstract function CreateDeleteSTMT($tablename = '');

    public abstract function CreateTableSTMT($mode, $tablename = '');

    public abstract function CreateSTMT($sql = NULL);

    //TODO: the following methods are being considered whether in this class or in TableSTMT
    /**
     * test if a table exists in the current database.
     * 
     * @param string $table
     * @return bool 
     */
    public abstract function TableExists($table);

    /**
     * test if a field exists in the specified table.
     * 
     * @param string $table
     * @param string $field
     * @return bool
     */
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

/**
 * an abstract sql statement
 * @version 0.4.20110327
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
abstract class DBSQLSTMT {

    protected $connObj = NULL;
    protected $stmtObj = NULL;

    /**
     * sql string containing '?' where puts a parameter.
     * @var string
     */
    protected $sql;

    /**
     * an array of bound result variables
     * @var array
     */
    protected $resultArr = array();

    /**
     * an array of parameters corresponding to '?' in the SQL string
     * @var array
     */
    protected $paramArr = array();

    /**
     * If result variables are not bound manully and this property is TRUE,
     * they will be generated automatically after execution.
     * @var bool
     */
    protected $autobound = TRUE;

    /**
     * TODO: paramcategorized desc
     * @var bool
     */
    protected $paramCategorized = FALSE;

    /**
     * the constructor
     * @param string $conn
     * @param string $sql
     */
    function __construct(&$conn, $sql = NULL) {
        $this->connObj = &$conn;
        $this->sql = $sql;
    }

    /**
     * add a parameter with information of type and value
     * @param string $type
     * @param mixed $var
     * @param string $category
     * TODO: category desc
     */
    public function AddParam($type, $value, $category = NULL) {
        if ($category) {
            $this->paramCategorized = TRUE;
            if (!isset($this->paramArr[$category]))
                $this->paramArr[$category] = array();
            $this->paramArr[$category][] = array($type, $value);
        } else if (!$this->paramCategorized) {
            $this->paramArr[] = array($type, $value);
        }else
            throw new Exception('require a category name because parameters are categorized');
    }

    /**
     * remove all parameters
     * @param string $category
     */
    public function ClearParams($category = NULL) {
        if ($category) {
            unset($this->paramArr[$category]);
        } else {
            $this->paramArr = array();
            $this->paramCategorized = FALSE;
        }
    }

    /**
     * get the number of parameters
     * @param string $category
     */
    public function ParamCount($category = NULL) {
        if ($category) {
            return count($this->paramArr[$category]);
        } else if ($this->paramCategorized) {
            $c = 0;
            foreach ($this->paramArr as &$category) {
                $c+=count($category);
            }
            return $c;
        } else {
            return count($this->paramArr);
        }
    }

    /**
     * bind a variable to a field in a select statement
     *
     * It is optional but should be involked before Execute();
     * Sequence depends on the SQL statement.
     *
     * @param mixed $var
     */
    public function BindResult(&$var) {
        if ($this->autobound && count($this->resultArr) == 0) {
            $this->autobound = FALSE;
            $this->resultArr[0] = NULL; //preserve place for the atrribute, stmtObj
        }
        $this->resultArr[] = &$var;
    }

    /**
     * remove all bound results
     */
    public function ClearBoundResults() {
        $this->resultArr = array();
        $this->autobound = TRUE;
    }

    /**
     * execute the sql statement
     * (add parameters using AddParam() before invoking this one)
     * @abstract
     * @return bool
     */
    abstract public function Execute();

    /**
     * set free all resources used in an sql execution,
     * except for the SQL string, added parameters and bound variables
     */
    abstract public function CloseSTMT();

    /**
     * fetch a row from the result
     * @param int $t
     * <table>
     * <tr><th>0</th><td>(default)return an array with field names as keys</td></tr>
     * <tr><th>1</th><td>return an object with field names as attributes</td></tr>
     * <tr><th>2</th><td>return an array with numbers as keys</td></tr>
     * <tr><th>3</th><td>return an array with both field names and numbers as keys</td></tr>
     * </table>
     * @return mixed
     * if nothing is fetched then return FALSE;<br />
     * if result is manually bound, return TRUE when succeeding;<br />
     * if it is automatically bound to a buit-in attribute, <br />
     * return an object when the parameter $t is 1 or an array when it is not 1.
     */
    public function Fetch($t = 0) {
        if ($this->stmtObj == NULL)
            return FALSE;
        if (!mysqli_stmt_fetch($this->stmtObj))
            return FALSE;
        if (!$this->autobound)
            return TRUE;
        switch ($t) {
            case 1:
                $r = new stdClass;
                foreach ($this->resultArr as $key => $value) {
                    $r->$key = $value;
                }
                return $r;
            case 2:
                $r = array();
                $i = 0;
                foreach ($this->resultArr as $key => $value) {
                    $r[$i] = $value;
                    $i++;
                }
                return $r;
            case 3:
                $r = array();
                $i = 0;
                foreach ($this->resultArr as $key => $value) {
                    $r[$key] = $value;
                    $r[$i] = $value;
                    $i++;
                }
                return $r;
            default:
                return $this->resultArr;
        }
    }

    public function FetchAll($t = 0) {
        if (!$this->autobound)
            throw new Exception('Results are manually bound and thus it is impossible to fetch all at once.');
        $list = array();
        while ($r = $this->Fetch($t))
            $list[] = $r;
        return $list;
    }

    public function GetSTMT() {
        return $this->stmtObj;
    }

}

/**
 * a statement that explains how to select data by a set of conditions, like a 'WHERE' statement in SQL
 * @version 0.1.20110313
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
interface ICondition {

    /**
     * add an expression into a set of conditions that selects special records in the database
     * @param string $c
     * an expresion that specifies a condition
     * @param int $l
     * an integer code for logical operators 'AND' and 'OR',
     * which connects the new condition and old ones;<br />
     * constants and their values are listed below:
     * <ul>
     * <li>0 - IBC1_LOGICAL_AND</li>
     * <li>1 - IBC1_LOGICAL_OR</li>
     * </ul>
     */
    public function AddCondition($c, $l = IBC1_LOGICAL_AND);

    /**
     * remove all conditions
     */
    public function ClearConditions();

    /**
     * get the number of conditions
     * @return int
     */
    public function ConditionCount();
}

/**
 * interface for a field list with greater details for field definition
 * (a CREATE TABLE generator has to realize this interface)
 * @version 0.1.20110314
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
interface IFieldDefList {

    /**
     * add a field definition into the list
     * @param string $fieldname
     * @param int $fieldtype
     * constants defined as IBC1_DATATYPE_[type name];default value is IBC1_DATATYPE_INTEGER
     * @param int $length
     * default value is 1
     * @param bool $isnull
     * default value is TRUE
     * @param string $default
     * default value is null
     * @param bool $ispkey
     * TRUE if the field is the primary key of the table; default value is FALSE
     * @param string $keyname
     * @param bool $autoincrement
     * Only when data type of the field is int or
     * $fieldtype is IBC1_DATATYPE_INTEGER,
     * this parameter can be given the value of TRUE.
     */
    public function AddField($fieldname, $fieldtype = IBC1_DATATYPE_INTEGER, $length = 1, $isnull = TRUE, $default = NULL, $ispkey = FALSE, $keyname = '', $autoincrement = FALSE);

    /**
     * remove all field definitions in the list
     */
    public function ClearFields();

    /**
     * get the number of field definitions in the list
     * @return int
     */
    public function FieldCount();
}

/**
 * interface for a simple field list with support of expressions and alias in a SQL statement
 * (a SELECT generator has to realize this interface)
 * @version 0.1.20110314
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
interface IFieldExpList {

    /**
     * add a field name or an expression with its alias name if provided
     * to create a field in the list
     * @param string $exp
     * a field name or an expression
     * @param string $alias
     * alias name for the field, optional
     */
    public function AddField($exp, $alias = '');

    /**
     * remove all fields or expressions in the list
     */
    public function ClearFields();

    /**
     * get the number of fields in the list
     */
    public function FieldCount();
}

/**
 * interface for a field list with values and their types
 * (an UPDATE or an INSERT generator has to realize this interface)
 * @version 0.1.20110314
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
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
    public function AddValues(/* ItemList */ $itemlist);

    /**
     * remove all fields in the list
     */
    public function ClearValues();

    /**
     * get the number of fields in the list
     */
    public function ValueCount();
}
