<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class Admin_ChannelList extends Box {

    function __construct() {
        parent::__construct("Left", "box3");
    }

    public function DataBind() {
        $cl = new ListModel(__CLASS__, "channellist_item");
        $cl->SetContainer("channellist");

        $rs = db_query("SELECT * FROM channel_info");
        if ($rs) {
            $list = db_result($rs);
            foreach ($list as $item) {
                if ($item["channel_type"] == 0) {
                    $open_page = "<a title=\"{$item["channel_add"]}\" href=\"{$item["channel_add"]}\" target=\"_blank\">" . Len_Control($item["channel_name"], 30) . "</a>";
                } else {
                    $open_page = "<a title=\"{$item["channel_name"]}\" href=\"admin_class.php?channel={$item["id"]}\">" . Len_Control($item["channel_name"], 30) . "</a>";
                }
                $cl->AddItem(array(
                    "ID" => $item["id"],
                    "Link" => $open_page,
                    "Type" => GetTypeName($item["channel_type"], 0)
                ));
            }
            db_free($rs);
        }

        $this->SetHeight("auto");
        $this->SetTitle(GetLangData("channeladmin"));
        $this->SetContent($cl->GetHTML(), "center", "middle", 30);
    }

}