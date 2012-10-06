<?php

/**
 * the general manager of all data models:<br />
 * <ul>
 * <li>setup up necessary tables for the core</li>
 * <li>parent class for managers of all components in the core, like Catalog, User, etc.</li>
 * </ul>
 * @version 0.8.20111206
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.datamodel
 */
class DataModelManager {

    const E_INSTALLED = 1;
    const E_INSTALL_FAILED = 2;
    const E_CONNECT_FAILED = 4;
    const E_NOT_FOUND = 3;

    private $_dbconnp;
    private $_cachep;

    function __construct() {

        $this->_dbconnp = new DBConnProvider();
        LoadIBC1Class('CacheProvider', 'cache');
        $this->_cachep = new CacheProvider(dirname(__FILE__));
    }

    /**
     * It creates multiple tables in batch and ensure no failures happen,
     * otherwise drop all of them even if some of tables can be created successfully
     * @param array $sqlset
     * Each element in the array contains a DBSQLSTMT object
     * to create a table and the corresponding table name for dropping it
     * if it fails to be created.
     * @param DBConn $conn
     * optional, a database connection;
     * if not provided, a new connection will be established
     * @return bool
     */
    protected function CreateTables(&$sqlset, DBConn &$conn = NULL) {
        if ($conn == NULL)
            $conn = $this->GetDBConn();
        if ($conn == NULL)
            return FALSE;
        $c = count($sqlset);
        for ($i = 0; $i < $c; $i++) {
            $sql = $sqlset[$i];
            if (!$conn->TableExists($sql[1])) {
                try {
                    $sql[0]->Execute();
                } catch (Exception $ex) {
                    for (; $i >= 0; $i--) {
                        try {
                            $stmt = $conn->CreateTableSTMT('drop', $sql[1]);
                            $stmt->Execute();
                            $stmt->CloseSTMT();
                        } catch (Exception $ex2) {
                            //do nothing
                        }
                    }
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    /**
     * get the database connection provider
     * @return DBConnProvider 
     */
    public function GetDBConnProvider() {
        return $this->_dbconnp;
    }

    /**
     * get a database connection (DBConn) object
     * @return DBConn
     */
    public function GetDBConn($ServiceName = '') {
        if ($this->_dbconnp == NULL) {
            throw new Exception('no database connector provider', DataModelManager::E_CONNECT_FAILED);
        }
        if ($ServiceName == '') {
            return $this->_dbconnp->OpenConn(
                            IBC1_CENTRALDB_HOST, IBC1_CENTRALDB_USER, IBC1_CENTRALDB_PWD, IBC1_CENTRALDB_NAME
            );
        } else {
            $reg = $this->_cachep->GetReader('registry_instance');
            $srv = $reg->GetValue($ServiceName);
            if (!is_array($srv)) {
                throw new Exception('datamodel not found');
            }

            return $this->_dbconnp->OpenConn(
                            $srv['dbhost'], $srv['dbuser'], $srv['dbpwd'], $srv['dbname'], $srv['dbdriver']
            );
        }
    }

    public function OpenDBConn($DBHost, $DBUser, $DBPwd, $DBName, $DBDriver) {
        if ($this->_dbconnp == NULL) {
            throw new Exception('no database connector provider', DataModelManager::E_CONNECT_FAILED);
        }
        return $this->_dbconnp->OpenConn(
                        $DBHost, $DBUser, $DBPwd, $DBName, $DBDriver
        );
    }

    /**
     * see if the fundamental table for datamodel is created
     * @param bool $throwexception
     * If the core is not installed, an exception is thrown.
     * @return bool
     * If your database is not properly connected, it also returns FALSE.
     */
    public function IsInstalled($throwexception = FALSE) {
        $conn = $this->GetDBConn();
        if (!$conn->TableExists(IBC1_PREFIX . '_datamodel')) {
            if ($throwexception)
                throw new Exception('datamodel is not installed', DataModelManager::E_NOT_FOUND);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * create fundamental tables for datamodel
     */
    public function Install() {
        if ($this->IsInstalled()) {
            throw new Exception('already installed', DataModelManager::E_INSTALLED);
        }
        $conn = $this->GetDBConn();
        $sql = $conn->CreateTableSTMT('create', IBC1_PREFIX . '_datamodel');
        $sql->AddField('ServiceName', IBC1_DATATYPE_PURETEXT, 64, FALSE, NULL, TRUE);
        $sql->AddField('ServiceType', IBC1_DATATYPE_PURETEXT, 32, FALSE);
        $sql->AddField('DBHost', IBC1_DATATYPE_PURETEXT, 64, FALSE);
        $sql->AddField('DBUser', IBC1_DATATYPE_PURETEXT, 64, FALSE);
        $sql->AddField('DBPwd', IBC1_DATATYPE_PURETEXT, 64, FALSE);
        $sql->AddField('DBName', IBC1_DATATYPE_PURETEXT, 64, FALSE);
        $sql->AddField('DBDriver', IBC1_DATATYPE_PURETEXT, 32, FALSE);
        $sqlset = array(
            array(
                $sql,
                IBC1_PREFIX . '_datamodel'
            )
        );
        if (!$this->CreateTables($sqlset, $conn)) {
            throw new Exception('fail to install', DataModelManager::E_INSTALL_FAILED);
        }
    }

    /**
     * register in the datamodel table and cache the change
     */
    protected function Register($ServiceName, $ServiceType, $DBHost, $DBUser, $DBPwd, $DBName, $DBDriver) {

        $conn = $this->GetDBConn();

        $sql = $conn->CreateInsertSTMT();
        $sql->SetTable(IBC1_PREFIX . '_datamodel');
        $sql->AddValue('ServiceName', $ServiceName, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('ServiceType', $ServiceType, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('DBHost', $DBHost, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('DBUser', $DBUser, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('DBPwd', $DBPwd, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('DBName', $DBName, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('DBDriver', $DBDriver, IBC1_DATATYPE_PURETEXT);
        $sql->Execute();
        $sql->ClearValues();
        $sql->CloseSTMT();

        $reg = $this->_cachep->GetEditor('registry_instance');
        $reg->SetValue($ServiceName, array(
            'type' => $ServiceType,
            'dbhost' => $DBHost,
            'dbuser' => $DBUser,
            'dbpwd' => $DBPwd,
            'dbname' => $DBName,
            'dbdriver' => $DBDriver
        ));
        $reg->Save();
    }

    /**
     * unregister in the datamodel table and cache the change
     * @param string $ServiceName
     */
    protected function Unregister($ServiceName) {

        $conn = $this->GetDBConn();

        $sql = $conn->CreateDeleteSTMT();
        $sql->AddEqual('ServiceName', $ServiceName, IBC1_DATATYPE_PURETEXT);
        $sql->SetTable(IBC1_PREFIX . '_datamodel');
        $sql->Execute();
        $sql->CloseSTMT();

        $reg = $this->_cachep->GetEditor('registry_instance');
        $reg->Remove($ServiceName);
        $reg->Save();
    }

    /**
     * check if a service exists
     * @param string $servicename
     * @param string $servicetype
     * if ignore the parameter or give an empty string to it,
     * type of the designated service won't be checked
     * @return bool
     */
    public function Exists($servicename, $servicetype = '') {
        //validate service name
        if (!ValidateServiceName($servicename)) {
            return FALSE;
        }
        //check cache
        $reg = $this->_cachep->GetReader('registry_instance');
        $regentry = $reg->GetValue($servicename);
        if (is_array($regentry)) {
            //found in cache
            if ($servicetype != '')
                return $regentry['type'] == $servicetype;
            return TRUE;
        }
        //check database
        $conn = $this->GetDBConn();
        $sql = $conn->CreateSelectSTMT(IBC1_PREFIX . '_datamodel');
        $sql->AddField('ServiceName');
        $sql->AddEqual('ServiceName', $servicename, IBC1_DATATYPE_PURETEXT, IBC1_LOGICAL_AND);
        if ($servicetype != '') {
            $sql->AddEqual('ServiceType', $servicetype, IBC1_DATATYPE_PURETEXT, IBC1_LOGICAL_AND);
        }
        $sql->Execute();
        $r = $sql->Fetch();
        $sql->CloseSTMT();
        return !!$r;
    }

}

class ManagerException extends Exception {
    
}