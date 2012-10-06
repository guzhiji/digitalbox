<?php

LoadIBC1Class('DataModelManager', 'datamodel');

/**
 *
 * @version 0.6
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.datamodel.keyvalue
 */
class KeyValueModelManager extends DataModelManager {

    const E_EXISTS = 1;
    const E_NOT_FOUND = 2;
    const E_CREATE_FAILED = 3;

    //const E_INVALID_NAME = 4;

    /**
     * create an user model instance as a data service.
     * 
     * @param string $ServiceName
     * @param array $DB
     * structure:
     * <code>
     * array(
     *      'host'=>'[db host]',
     *      'user'=>'[username]',
     *      'pwd'=>'[user password]',
     *      'dbname'=>'[database name]',
     *      'driver'=>'mysqli'//optional
     * )
     * </code>
     * @param array $Config
     * structure:
     * <code>
     * array(
     *      'binding_type'=>[data type code],//optional
     *      'binding_length'=>[length of the a binding value],//optional
     *      'value_type'=>[data type code],
     *      'value_length'=>[max length of value],
     *      'time_included'=>[TRUE/FALSE]//optional, FALSE by default
     * )
     * </code>
     * @throws ManagerException 
     */
    public function Create($ServiceName, $DB, $Config) {

        //validate service name
        if ($this->Exists($ServiceName)) {
            throw new ManagerException("service '$ServiceName' has already been created", KeyValueModelManager::E_EXISTS);
        }

        //connect to the database server
        $DBHost = $DB['host'];
        $DBUser = $DB['user'];
        $DBPwd = $DB['pwd'];
        $DBName = $DB['dbname'];
        $DBDriver = isset($DB['driver']) ? $DB['driver'] : IBC1_DEFAULT_DBDRIVER;
        $conn = $this->OpenDBConn($DBHost, $DBUser, $DBPwd, $DBName, $DBDriver);

        //config
        $BindingType = isset($Config['binding_type']) ? intval($Config['binding_type']) : NULL;
        $BindingLength = isset($Config['binding_length']) ? intval($Config['binding_length']) : NULL;
        $ValueType = intval($Config['value_type']);
        $ValueLength = intval($Config['value_length']);
        $TimeIncluded = isset($Config['time_included']) && $Config['time_included'];

        //create tables
        $sqlset = array();
        $i = 0;
        $sqlset[] = array(
            $conn->CreateTableSTMT('create', IBC1_PREFIX . '_' . $ServiceName . '_list'),
            IBC1_PREFIX . '_' . $ServiceName . '_list'
        );
        $sql = &$sqlset[$i][0];
        $sql->AddField('kvID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, '', TRUE);
        if ($BindingType !== NULL && $BindingLength !== NULL)
            $sql->AddField('kvBindingValue', $BindingType, $BindingLength, TRUE, NULL, FALSE, 'k_binding');
        $sql->AddField('kvKey', IBC1_DATATYPE_PURETEXT, 256, FALSE, NULL, FALSE, 'k_key');
        $sql->AddField('kvValue', $ValueType, $ValueLength, TRUE);
        if ($TimeIncluded) {
            $sql->AddField('kvTimeCreated', IBC1_DATATYPE_DATETIME, 0, TRUE);
            $sql->AddField('kvTimeUpdated', IBC1_DATATYPE_DATETIME, 0, TRUE);
            $TimeIncluded = 1;
        } else {
            $TimeIncluded = 0;
        }

        $r = $this->CreateTables($sqlset, $conn);
        if ($r == FALSE) {
            throw new ManagerException('fail to create a Key-Value service', KeyValueModelManager::E_CREATE_FAILED);
        }

        //register service
        $this->Register($ServiceName, 'keyvalue', $DBHost, $DBUser, $DBPwd, $DBName, $DBDriver);
    }

    /**
     * delete a service using the catalog model
     * @param string $ServiceName
     * @throws ManagerException 
     */
    public function Delete($ServiceName) {
        if (!$this->Exists($ServiceName, 'keyvalue')) {
            throw new ManagerException("cannot find service '$ServiceName'", KeyValueModelManager::E_NOT_FOUND);
        }

        //drop table
        $conn = $this->GetDBConn();
        $sql = $conn->CreateTableSTMT('drop', IBC1_PREFIX . '_' . $ServiceName . '_list');
        $sql->Execute();
        $sql->CloseSTMT();

        //unregister service
        $this->Unregister($ServiceName);
    }

    /**
     * optimize data tables for the service
     * @param string $ServiceName
     * @throws ManagerException 
     */
    public function Optimize($ServiceName) {
        if (!$this->Exists($ServiceName, 'keyvalue')) {
            throw new ManagerException("cannot find service '$ServiceName'", KeyValueModelManager::E_NOT_FOUND);
        }
        $conn = $this->GetDBConn();
        $sql = $conn->CreateTableSTMT('optimize', IBC1_PREFIX . '_' . $ServiceName . '_list');
        $sql->Execute();
        $sql->CloseSTMT();
    }

}
