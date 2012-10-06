<?php

LoadIBC1Class('DataModelManager', 'datamodel');

/**
 *
 * @version 0.7.20111206
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.datamodel.catalog
 */
class CatalogModelManager extends DataModelManager {

    const E_EXISTS = 1;
    const E_NOT_FOUND = 2;
    const E_CREATE_FAILED = 3;
    const E_INVALID_NAME = 4;

    private function GetTableSQL($middlename, DBConn $conn) {
        $sqlset = array();

        $sqlset[0][0] = $conn->CreateTableSTMT('create');
        $sqlset[0][1] = IBC1_PREFIX . '_' . $middlename . '_content';
        $sql = &$sqlset[0][0];
        $sql->SetTable($sqlset[0][1]);
        $sql->AddField('cntID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, '', TRUE);
        $sql->AddField('cntOrdinal', IBC1_DATATYPE_INTEGER, 10, TRUE, 0);
        $sql->AddField('cntName', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField('cntCatalogID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, FALSE, 'parent');
        $sql->AddField('cntAuthor', IBC1_DATATYPE_PURETEXT, 256, TRUE);
        $sql->AddField('cntKeywords', IBC1_DATATYPE_WORDLIST, 255, TRUE);
        $sql->AddField('cntTimeCreated', IBC1_DATATYPE_DATETIME, 0, TRUE);
        $sql->AddField('cntTimeUpdated', IBC1_DATATYPE_DATETIME, 0, TRUE);
        $sql->AddField('cntTimeVisited', IBC1_DATATYPE_DATETIME, 0, TRUE);
        $sql->AddField('cntPointValue', IBC1_DATATYPE_INTEGER, 10, FALSE, 0);
        $sql->AddField('cntUID', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField('cntVisitCount', IBC1_DATATYPE_INTEGER, 10, FALSE, 0);
        $sql->AddField('cntAdminLevel', IBC1_DATATYPE_INTEGER, 10, TRUE);
        $sql->AddField('cntVisitLevel', IBC1_DATATYPE_INTEGER, 10, FALSE, 0);

        $sqlset[1][0] = $conn->CreateTableSTMT('create');
        $sqlset[1][1] = IBC1_PREFIX . '_' . $middlename . '_catalog';
        $sql = &$sqlset[1][0];
        $sql->SetTable($sqlset[1][1]);
        $sql->AddField('clgID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, '', TRUE);
        $sql->AddField('clgName', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField('clgOrdinal', IBC1_DATATYPE_INTEGER, 10, TRUE);
        $sql->AddField('clgUID', IBC1_DATATYPE_PURETEXT, 256, TRUE);
        $sql->AddField('clgParentID', IBC1_DATATYPE_INTEGER, 10, FALSE);
        $sql->AddField('clgGID', IBC1_DATATYPE_INTEGER, 10, FALSE, 0);
        $sql->AddField('clgAdminLevel', IBC1_DATATYPE_INTEGER, 10, FALSE);

        $sqlset[2][0] = $conn->CreateTableSTMT('create');
        $sqlset[2][1] = IBC1_PREFIX . '_' . $middlename . '_admin';
        $sql = &$sqlset[2][0];
        $sql->SetTable($sqlset[2][1]);
        $sql->AddField('admID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, '', TRUE);
        $sql->AddField('admCatalogID', IBC1_DATATYPE_INTEGER, 10, FALSE);
        $sql->AddField('admUID', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        return $sqlset;
    }

    /**
     * create a catalog model instance as a data service.
     * 
     * Demo:
     * <code>
     * LoadIBC1Class('CatalogModelManager', 'datamodel.catalog');
     * try{
     *      $m=new CatalogModelManager();
     *      if (!$m->IsInstalled()) $m->Install();
     *      $m->Create('catalogtest', array(
     *          'localhost:3306',
     *          'root',
     *          '',
     *          'dbname'
     *      ));
     * }catch(Exception $ex){
     *      echo $ex->getMessage();
     * }
     * </code>
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
     * @throws ManagerException 
     */
    public function Create($ServiceName, $DB) {

        //validate service name
        if ($this->Exists($ServiceName)) {
            throw new ManagerException("service '$ServiceName' has already been created", CatalogModelManager::E_EXISTS);
        }

        //TODO validate the rest params
        //connect to the database server
        $DBHost = $DB['host'];
        $DBUser = $DB['user'];
        $DBPwd = $DB['pwd'];
        $DBName = $DB['dbname'];
        $DBDriver = isset($DB['driver']) ? $DB['driver'] : IBC1_DEFAULT_DBDRIVER;
        $conn = $this->OpenDBConn($DBHost, $DBUser, $DBPwd, $DBName, $DBDriver);

        //create tables
        $r = $this->CreateTables($this->GetTableSQL($ServiceName, $conn), $conn);
        if ($r == FALSE) {
            throw new ManagerException('fail to create Catalog service', CatalogModelManager::E_CREATE_FAILED);
        }

        //register datamodel
        $this->Register($ServiceName, 'catalog', $DBHost, $DBUser, $DBPwd, $DBName, $DBDriver);
    }

    /**
     * delete a service using the catalog model
     * 
     * @param string $ServiceName
     * @throws ManagerException 
     */
    public function Delete($ServiceName) {
        //validate service name
        if (!ValidateServiceName($ServiceName)) {
            throw new ManagerException('invalid service name', CatalogModelManager::E_INVALID_NAME);
        }

        //connect to database server
        $conn = $this->GetDBConn($ServiceName);

        //drop tables
        $sql = $conn->CreateTableSTMT('drop');
        $tables = array(
            IBC1_PREFIX . '_' . $ServiceName . '_content',
            IBC1_PREFIX . '_' . $ServiceName . '_catalog',
            IBC1_PREFIX . '_' . $ServiceName . '_admin'
        );
        foreach ($tables as $table) {
            $sql->SetTable($table);
            $sql->Execute();
            $sql->CloseSTMT();
        }

        //unregister service
        $this->Unregister($ServiceName);
    }

    /**
     * optimize data tables for the service
     * 
     * @param string $ServiceName
     * @throws ManagerException 
     */
    public function Optimize($ServiceName) {
        if (!$this->Exists($ServiceName, 'catalog')) {
            throw new ManagerException("cannot find service '$ServiceName'", CatalogModelManager::E_NOT_FOUND);
        }
        $conn = $this->GetDBConn($ServiceName);
        $sql = $conn->CreateTableSTMT('optimize');
        $tables = array(
            IBC1_PREFIX . '_' . $ServiceName . '_content',
            IBC1_PREFIX . '_' . $ServiceName . '_catalog',
            IBC1_PREFIX . '_' . $ServiceName . '_admin'
        );
        foreach ($tables as $table) {
            $sql->SetTable($table);
            $sql->Execute();
            $sql->CloseSTMT();
        }
    }

}
