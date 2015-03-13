<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2013, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

require_once("modules/uimodel/ListModel.class.php");

class CommentList extends ListModel {

    var $type = 0;
    private $formore = 0;
    private $containertplname = "";

    function __construct($itemTplName, $emptyTplName = "") {
        parent::__construct(__CLASS__, $itemTplName);
        $this->SetEmptyItem($emptyTplName);
    }

    public function AddItem($id, $name, $email, $title, $date, $ip = "") {
        parent::AddItem(array(
            "ID" => $id,
            "Name" => $name,
            "EMail" => $email,
            "Title" => $title,
            "Date" => $date,
            "IP" => $ip
        ));
    }

    public function SetContainer($tplname, $formore = 0) {
        $this->formore = $formore;
        $this->containertplname = $tplname;
    }

    public function Bind($key = "", $page_size = 0) {

        //prepare query
        $key = PrepareSearchKey($key);
        if ($key != "") {
            $where = " WHERE guest_title LIKE \"$key\"";
            $sql = "SELECT * FROM guest_info{$where} ORDER BY guest_date DESC";
            $this->type = 3;
        } else {
            $where = "";
            $sql = "SELECT * FROM guest_info ORDER BY guest_date DESC";
            $this->type = 1;
        }

        //initialize variables
        if ($page_size < 1)
            $page_size = GetSettingValue("general_list_maxlen");
        $total_page = 1;
        $page_number = 1;
        $total_record = 0;

        //count
        if ($this->formore == 2) {
            $rs = db_query("SELECT COUNT(*) FROM guest_info{$where}");
            if ($rs) {
                $list = db_result($rs);
                if (isset($list[0])) {
                    $total_record = $list[0][0];
                }
                db_free($rs);
            }
        }

        //pagination
        if ($this->containertplname != "") {
            switch ($this->formore) {
                case 1:
                    parent::SetContainer($this->containertplname, array(
                        "MoreButton" => GetThemeResPath("button/more.gif", "images", GetThemeID())
                    ));
                    break;
                case 2:
                    require_once("modules/PagingBar.class.php");
                    $pb = new PagingBar();
                    $pb->SetPageCount($total_record, $page_size);
                    $total_page = $pb->GetPageCount();
                    $page_number = $pb->GetPageNumber();
                    parent::SetContainer($this->containertplname, array(
                        "PagingBar" => $pb->GetHTML()
                    ));
                    break;
                default:
                    parent::SetContainer($this->containertplname, array());
            }
        }

        //fetch data
        $rs = db_query($sql . " LIMIT " . ($page_size * ($page_number - 1)) . ",$page_size");
        if ($rs) {
            $list = db_result($rs);
            if (count($list) > 0) {
                foreach ($list as $item) {
                    $this->AddItem($item["id"], $item["guest_name"], $item["guest_mail"], $item["guest_title"], $item["guest_date"], $item["guest_IP"]);
                }
            }
            db_free($rs);
        }
    }

}
