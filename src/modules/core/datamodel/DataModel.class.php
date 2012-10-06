<?php

/**
 * A general class for data models, providing basic methods for 
 * managing data services.
 * 
 * A data model is a type of data service, such as the catalog data model;
 * a data service is an instance of data model, such as a catalog service 
 * named 'catalogtest'.
 * To use a data service, a service name for the instance should be given 
 * so as to open the service and a database connection is estabilished for 
 * the service and the corresponding data tables dedicated to the service 
 * become accessible.
 * 
 * @version 0.8.20111205
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.datamodel
 */
abstract class DataModel {

    private $_dbconnp = NULL;
    private $_servicename = '';
    private $_issrvopen = FALSE;
    private $_dbconn = NULL;
    private $_dbhost;
    private $_dbuser;
    private $_dbpwd;
    private $_dbname;
    private $_dbdriver;

    protected function OpenService($ServiceName, $ServiceType) {

        //close before open
        if ($this->_dbconnp != NULL) {
            $this->CloseService();
        }

        //prepare connection provider
        $this->_dbconnp = new DBConnProvider();

        //find data service and db conn info
        if (!$this->_CheckCacheRegistry($ServiceName, $ServiceType)) {
            if (!$this->_CheckDBRegistry($ServiceName, $ServiceType)) {
                throw new Exception("cannot find the datamodel: $ServiceName", 1);
            } else {
                //TODO refresh cache
            }
        }

        $this->_servicename = $ServiceName;
        $this->_issrvopen = TRUE;
    }

    private function _CheckCacheRegistry($ServiceName, $ServiceType) {
        LoadIBC1Class('CacheProvider', 'cache');
        $cp = new CacheProvider(dirname(__FILE__));
        $reg = $cp->GetReader('registry_instance');
        $t = $reg->GetValue($ServiceName);
        if (is_array($t)) {
            $this->_dbhost = $t['dbhost'];
            $this->_dbuser = $t['dbuser'];
            $this->_dbpwd = $t['dbpwd'];
            $this->_dbname = $t['dbname'];
            $this->_dbdriver = $t['dbdriver'];
            return TRUE;
        }
        return FALSE;
    }

    private function _CheckDBRegistry($ServiceName, $ServiceType) {
        $conn = $this->_dbconnp->OpenConn(
                IBC1_CENTRALDB_HOST, IBC1_CENTRALDB_USER, IBC1_CENTRALDB_PWD, IBC1_CENTRALDB_NAME
        );
        $sql = &$conn->CreateSelectSTMT(IBC1_PREFIX . '_datamodel');
        $sql->AddField('*');
        $sql->AddEqual('ServiceName', $ServiceName, IBC1_DATATYPE_PURETEXT, IBC1_LOGICAL_AND);
        $sql->AddEqual('ServiceType', $ServiceType, IBC1_DATATYPE_PURETEXT, IBC1_LOGICAL_AND);
        $sql->Execute();
        $r = $sql->Fetch();
        $sql->CloseSTMT();
        if ($r) {
            $this->_dbhost = $r['DBHost'];
            $this->_dbuser = $r['DBUser'];
            $this->_dbpwd = $r['DBPwd'];
            $this->_dbname = $r['DBName'];
            $this->_dbdriver = $r['DBDriver'];
            return TRUE;
        }
        return FALSE;
    }

    public function CloseService() {
        $this->_servicename = '';
        $this->_dbconnp = NULL;
        $this->_dbconn = NULL;
        $this->_issrvopen = FALSE;
    }

    public function IsServiceOpen() {
        return $this->_issrvopen;
    }

    public function GetServiceName() {
        if ($this->_servicename == '')
            throw new Exception('service is not open');
        return $this->_servicename;
    }

    protected function GetDataTableName($table) {
        if ($this->_servicename == '')
            throw new Exception('service is not open');
        return IBC1_PREFIX . '_' . $this->_servicename . '_' . $table;
    }

    public function GetDBConnProvider() {
        return $this->_dbconnp;
    }

    public function GetDBConn() {
        //no database connector provider
        if ($this->_dbconnp == NULL)
            throw new Exception('no database connector provider');
        //used & with the same software
        if ($this->_dbconn != NULL) {
            if (!$this->_dbconn->IsConnected()) {
                //re-connect
                $this->_dbconn->OpenDB($this->_dbhost, $this->_dbuser, $this->_dbpwd, $this->_dbname);
            }
            return $this->_dbconn;
        }
        //connect with specified connector software
        $this->_dbconn = $this->_dbconnp->OpenConn(
                $this->_dbhost, $this->_dbuser, $this->_dbpwd, $this->_dbname, $this->_dbdriver
        );
        return $this->_dbconn;
    }

}

class ServiceException extends Exception {
    
}