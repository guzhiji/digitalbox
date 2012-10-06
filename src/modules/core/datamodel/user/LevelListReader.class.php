<?php

LoadIBC1Class('DataList', 'datamodel');

/**
 *
 * @version 0.6
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.datamodel.user
 */
class LevelListReader extends DataList {

    function __construct($ServiceName) {
        parent::__construct();
        $this->OpenService($ServiceName);
    }

    public function OpenService($ServiceName) {
        parent::OpenService($ServiceName, 'user');
    }

    public function LoadList() {

        $conn = $this->GetDBConn();
        $sql = $conn->CreateSelectSTMT($this->GetDataTableName('level'));
        $sql->AddField('COUNT(levNumber)', 'c');
        $this->GetCounts1($sql);
        $sql->ClearFields();
        $sql->AddField('*');
        $sql->SetLimit($this->GetPageSize(), $this->GetPageNumber());
        $sql->Execute();
        $this->Clear();
        while ($r = $sql->Fetch(1)) {
            $this->AddItem($r);
        }
        $this->GetCounts2();
        $sql->CloseSTMT();
    }

}
