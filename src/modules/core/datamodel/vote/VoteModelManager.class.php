<?php

/**
 *
 * @version 0.1
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.vote
 */
LoadIBC1Class("DataModelManager", "datamodel");

class VoteModelManager extends DataModelManager {

    public function Create($ServiceName) {
        $this->GetError()->Clear();
        if (!$this->IsInstalled())
            return FALSE;
        if ($this->Exists($ServiceName)) {
            throw new ManagerException(4, "服务 '$ServiceName' 早已建立|service '$ServiceName' has already been there");
            return FALSE;
        }
        if ($this->GetError()->HasError())
            return FALSE;
        $conn = $this->GetDBConn();
        $sqlset[0][0] = $conn->CreateTableSTMT("create");
        $sqlset[0][1] = "ibc1_vot" . $ServiceName . "_vote";
        $sql = &$sqlset[0][0];
        $sql->SetTable($sqlset[0][1]);
        $sql->AddField("votID", IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, "", TRUE);
        $sql->AddField("votCaption", IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField("votStartTime", IBC1_DATATYPE_DATETIME, 0, FALSE);
        $sql->AddField("votEndTime", IBC1_DATATYPE_DATETIME, 0, TRUE);
        $sql->AddField("votMaxNumber", IBC1_DATATYPE_INTEGER, 10, FALSE, -1);
        $sql->AddField("votMinNumber", IBC1_DATATYPE_INTEGER, 10, FALSE, 1);
        $sql->AddField("votContentID", IBC1_DATATYPE_INTEGER, 10, TRUE);
        $sql->AddField("votCatalogService", IBC1_DATATYPE_PURETEXT, 256, TRUE);
        /*
          $sql[0]="CREATE TABLE IBC1_vot".$ServiceName."_Vote(";
          $sql[0].=" votID INT(10) NOT NULL AUTO_INCREMENT,";
          $sql[0].=" votCaption VARCHAR(255) NOT NULL,";
          $sql[0].=" votStartTime TIMESTAMP(14) NOT NULL,";
          $sql[0].=" votEndTime TIMESTAMP(14) NOT NULL,";
          $sql[0].=" votMaxNumber INT(10) NOT NULL,";
          $sql[0].=" votMinNumber INT(10) NOT NULL,";
          $sql[0].=" votContentID INT(10) NOT NULL DEFAULT 0,";
          $sql[0].=" votCatalogService VARCHAR(255) NULL,";
          $sql[0].=" PRIMARY KEY (votID)";
          $sql[0].=") TYPE=MyISAM DEFAULT CHARSET=utf8;";
         */
        $sqlset[1][0] = $conn->CreateTableSTMT("create");
        $sqlset[1][1] = "ibc1_vot" . $ServiceName . "_entry";
        $sql = &$sqlset[1][0];
        $sql->SetTable($sqlset[1][1]);
        $sql->AddField("entID", IBC1_DATATYPE_INTEGER, 10, FALSE, NULL, TRUE, "", TRUE);
        $sql->AddField("entVoteID", IBC1_DATATYPE_INTEGER, 10, FALSE);
        $sql->AddField("entText", IBC1_DATATYPE_PURETEXT, 256, FALSE);
        $sql->AddField("entValue", IBC1_DATATYPE_INTEGER, 10, FALSE, 0);

        /*
          $sql[1]="CREATE TABLE IBC1_vot".$ServiceName."_Entry(";
          $sql[1].=" entID INT(10) NOT NULL AUTO_INCREMENT,";
          $sql[1].=" entVoteID INT(10) NOT NULL,";
          $sql[1].=" entText VARCHAR(255) NOT NULL,";
          $sql[1].=" entValue INT(10) NOT NULL DEFAULT 0,";
          $sql[1].=" PRIMARY KEY (entID)";
          $sql[1].=") TYPE=MyISAM DEFAULT CHARSET=utf8;";
         */
        $r = $this->CreateTables($sqlset, $conn);
        if ($r == FALSE) {
            throw new ManagerException(3, "Vote服务建立失败|fail to create Vote service");
            return FALSE;
        }
        $sql = $conn->CreateInsertSTMT("ibc1_datamodel");
        $sql->AddValue("ServiceName", $ServiceName, IBC1_DATATYPE_PURETEXT);
        $sql->AddValue("ServiceType", "vot", IBC1_DATATYPE_PURETEXT);
        $sql->Execute();
        $sql->CloseSTMT();
        if ($conn->GetError()->HasError()) {
            throw new ManagerException(7, "'" . $conn->GetError()->GetSource() . "' 存在未知错误|unknown error from '" . $conn->GetError()->GetSource() . "'");
            return FALSE;
        }
        return TRUE;
    }

    public function Delete($ServiceName) {
        $this->GetError()->Clear();
        if (!$this->Exists($ServiceName)) {
            throw new ManagerException(6, "服务'$ServiceName'不存在|cannot find service '$ServiceName'");
            return FALSE;
        }
        $conn = $this->GetDBConn();
        $sql = $conn->CreateTableSTMT("drop");
        $sql->SetTable("ibc1_vot" . $ServiceName . "_vote");
        $sql->Execute();
        $sql->CloseSTMT();
        $sql->SetTable("ibc1_vot" . $ServiceName . "_entry");
        $sql->Execute();
        $sql->CloseSTMT();

        $sql = $conn->CreateDeleteSTMT("ibc1_datamodel");
        $sql->AddEqual("ServiceName", $ServiceName, IBC1_DATATYPE_PURETEXT);
        $sql->Execute();
        $sql->CloseSTMT();

        if ($conn->GetError()->HasError()) {
            throw new ManagerException(7, "'" . $conn->GetError()->GetSource() . "' 存在未知错误|unknown error from '" . $conn->GetError()->GetSource() . "'");
            return FALSE;
        }
        return TRUE;
    }

}

?>
