<?php

LoadIBC1Class('DataModelManager', 'datamodel');

/**
 *
 * @version 0.7.20111206
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.datamodel.user
 */
class UserModelManager extends DataModelManager {

    const E_EXISTS = 1;
    const E_NOT_FOUND = 2;
    const E_CREATE_FAILED = 3;
    const E_INVALID_VALUE = 4;
    const E_UNCONFIRMED = 5;

    private function GetTableSQL($ServiceName, DBConn $conn) {
        $sqlset = array();

        $sqlset[0][0] = $conn->CreateTableSTMT('create');
        $sqlset[0][1] = IBC1_PREFIX . '_' . $ServiceName . '_user';
        $sql = &$sqlset[0][0];
        $sql->SetTable($sqlset[0][1]);
        $sql->AddField('usrUID', IBC1_DATATYPE_PURETEXT, 256, FALSE, NULL, TRUE, '', FALSE);
        $sql->AddField('usrPWD', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField('usrFace', IBC1_DATATYPE_PURETEXT, 256, TRUE);
        $sql->AddField('usrNickName', IBC1_DATATYPE_PURETEXT, 256, TRUE);
        $sql->AddField('usrLevel', IBC1_DATATYPE_INTEGER, 2, FALSE, 1);
        $sql->AddField('usrPoints', IBC1_DATATYPE_INTEGER, 10, FALSE, 0);
        $sql->AddField('usrLoginCount', IBC1_DATATYPE_INTEGER, 10, FALSE, 0);
        $sql->AddField('usrLoginIP', IBC1_DATATYPE_PURETEXT, 50, TRUE);
        $sql->AddField('usrLoginTime', IBC1_DATATYPE_DATETIME, 0, TRUE);
        $sql->AddField('usrVisitTime', IBC1_DATATYPE_DATETIME, 0, TRUE);
        $sql->AddField('usrRegisterTime', IBC1_DATATYPE_DATETIME, 0, FALSE);
        $sql->AddField('usrIsOnline', IBC1_DATATYPE_INTEGER, 1, FALSE, 0);
        $sql->AddField('usrIsUserAdmin', IBC1_DATATYPE_INTEGER, 1, FALSE, 0);

        $sqlset[1][0] = $conn->CreateTableSTMT('create');
        $sqlset[1][1] = IBC1_PREFIX . '_' . $ServiceName . '_level';
        $sql = &$sqlset[1][0];
        $sql->SetTable($sqlset[1][1]);
        $sql->AddField('levNumber', IBC1_DATATYPE_INTEGER, 2, FALSE, NULL, TRUE, '', FALSE);
        $sql->AddField('levName', IBC1_DATATYPE_PURETEXT, 256, FALSE);

        $sqlset[2][0] = $conn->CreateTableSTMT('create');
        $sqlset[2][1] = IBC1_PREFIX . '_' . $ServiceName . '_groupuser';
        $sql = &$sqlset[2][0];
        $sql->SetTable($sqlset[2][1]);
        $sql->AddField('gpuID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, '', TRUE);
        $sql->AddField('gpuUID', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField('gpuGID', IBC1_DATATYPE_INTEGER, 10, FALSE);

        $sqlset[3][0] = $conn->CreateTableSTMT('create');
        $sqlset[3][1] = IBC1_PREFIX . '_' . $ServiceName . '_group';
        $sql = &$sqlset[3][0];
        $sql->SetTable($sqlset[3][1]);
        $sql->AddField('grpID', IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, '', TRUE);
        $sql->AddField('grpName', IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField('grpOwner', IBC1_DATATYPE_PURETEXT, 256, TRUE);
        $sql->AddField('grpType', IBC1_DATATYPE_INTEGER, 2, FALSE, 0);

        return $sqlset;
    }

    private function validateUserAdmin($uid, $pwd, $repeat) {
        //TODO validate uid
        if (!preg_match('/[0-9a-z]{6,}/i', $pwd)) {
            throw new ManagerException('invalid password', UserModelManager::E_INVALID_VALUE);
        }
        if ($pwd != $repeat) {
            throw new ManagerException('unconfirmed password', UserModelManager::E_UNCONFIRMED);
        }
    }

    private function addUserAdmin($sql, $uid, $pwd, $level) {
        LoadIBC1Lib('PWDSecurity', 'util');
        $sql->ClearValues();
        $pwd = PWDEncode($pwd);
        $sql->AddValue('usrUID', $uid, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue('usrPWD', $pwd, IBC1_DATATYPE_PWD);
        $sql->AddValue('usrLevel', $level);
        $sql->AddValue('usrRegisterTime', 'CURRENT_TIMESTAMP()', IBC1_DATATYPE_EXPRESSION);
        $sql->AddValue('usrIsUserAdmin', 1);
        $sql->Execute();
        $sql->CloseSTMT();
    }

    /**
     * create an user model instance as a data service.
     * 
     * Demo:
     * <code>
     * LoadIBC1Class('UserModelManager', 'datamodel.user');
     * $m=new UserModelManager();
     * try{
     *     $m->Create(
     *         'usertest',
     *         array(
     *             'host'=>'localhost:3306',
     *             'user'=>'root',
     *             'pwd'=>'',
     *             'dbname'=>'dbname'
     *         ),
     *         array(
     *             'level 1',
     *             'level 2',
     *             'level 3'
     *         ),
     *         array(
     *             'uid'=>'webmaster',
     *             'pwd'=>'mypwd',
     *             'repeat'=>'mypwd'
     *         )
     *     );
     *     echo "succeeded\n";
     * } catch(Exception $ex) {
     *     echo $ex->getMessage()."\n";
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
     * @param array $LevelList
     * structure:
     * <code>
     * array(
     *      'level 1',
     *      'level 2',
     *      'level 3'//...
     * )
     * </code>
     * @param array $UserAdmin
     * structure:
     * <code>
     * array(
     *      'uid'=>'[user id]',
     *      'pwd'=>'[user password]',
     *      'repeat'=>'[repeat password]'
     * )
     * </code>
     * @throws ManagerException 
     */
    public function Create($ServiceName, $DB, $LevelList, $UserAdmin) {

        //validate user admin
        $uid = $UserAdmin['uid'];
        $pwd = $UserAdmin['pwd'];
        $repeat = $UserAdmin['repeat'];

        $this->validateUserAdmin($uid, $pwd, $repeat);

        $c = count($LevelList);
        if ($c < 2) {
            throw new ManagerException('at least 2 user levels', UserModelManager::E_INVALID_VALUE);
        }
        if ($this->Exists($ServiceName)) {
            throw new ManagerException("service '$ServiceName' has already been created", UserModelManager::E_EXISTS);
        }

        //connect to database server
        $DBHost = $DB['host'];
        $DBUser = $DB['user'];
        $DBPwd = $DB['pwd'];
        $DBName = $DB['dbname'];
        $DBDriver = isset($DB['driver']) ? $DB['driver'] : IBC1_DEFAULT_DBDRIVER;
//        $conn = $this->_dbconnp->OpenConn(
//                $DBHost, $DBUser, $DBPwd, $DBName, $DBDriver
//        );
        $conn = $this->OpenDBConn($DBHost, $DBUser, $DBPwd, $DBName, $DBDriver);

        //create tables
        $r = $this->CreateTables($this->GetTableSQL($ServiceName, $conn), $conn);
        if ($r == FALSE) {
            throw new ManagerException('fail to create User service', UserModelManager::E_CREATE_FAILED);
        }

        //register service
        $this->Register($ServiceName, 'user', $DBHost, $DBUser, $DBPwd, $DBName, $DBDriver);

        //create user levels
        $sql = $conn->CreateInsertSTMT();
        $sql->SetTable(IBC1_PREFIX . '_' . $ServiceName . '_level');
        for ($i = 0; $i < $c; $i++) {
            $sql->AddValue('levNumber', $i + 1);
            $sql->AddValue('levName', $LevelList[$i], IBC1_DATATYPE_PURETEXT);

            $sql->Execute();
            $sql->ClearValues();
            $sql->CloseSTMT();
        }

        //add the user admin
        $sql->SetTable(IBC1_PREFIX . '_' . $ServiceName . '_user');
        $this->addUserAdmin($sql, $uid, $pwd, $c);
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
            throw new ManagerException('invalid service name', UserModelManager::E_INVALID_VALUE);
        }

        //connect to database server
        $conn = $this->GetDBConn($ServiceName);

        //drop tables
        $sql = $conn->CreateTableSTMT('drop');
        $tables = array(
            IBC1_PREFIX . '_' . $ServiceName . '_user',
            IBC1_PREFIX . '_' . $ServiceName . '_level',
            IBC1_PREFIX . '_' . $ServiceName . '_group',
            IBC1_PREFIX . '_' . $ServiceName . '_groupuser'
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
        if (!$this->Exists($ServiceName, 'user')) {
            throw new ManagerException("cannot find service '$ServiceName'", UserModelManager::E_NOT_FOUND);
        }
        $conn = $this->GetDBConn($ServiceName);
        $sql = $conn->CreateTableSTMT('optimize', IBC1_PREFIX . '_' . $ServiceName . '_user');
        $sql->Execute();
        $sql->CloseSTMT();
        $sql = $conn->CreateTableSTMT('optimize', IBC1_PREFIX . '_' . $ServiceName . '_level');
        $sql->Execute();
        $sql->CloseSTMT();
        $sql = $conn->CreateTableSTMT('optimize', IBC1_PREFIX . '_' . $ServiceName . '_groupuser');
        $sql->Execute();
        $sql->CloseSTMT();
        $sql = $conn->CreateTableSTMT('optimize', IBC1_PREFIX . '_' . $ServiceName . '_group');
        $sql->Execute();
        $sql->CloseSTMT();
    }

}
