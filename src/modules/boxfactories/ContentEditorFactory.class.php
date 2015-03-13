<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 2.7
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2011-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class ContentEditorFactory extends BoxFactory {

    function __construct() {
        parent::__construct("Left");
    }

    public function DataBind() {
        global $_classID;
        $rs = db_query("SELECT class_info.id AS class_id,class_info.class_name,channel_info.channel_type FROM class_info,channel_info WHERE class_info.parent_channel=channel_info.id AND class_info.id=%d", array($_classID));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                global $_channelType;
                global $_className;
                $_className = $list[0]["class_name"];
                $_channelType = $list[0]["channel_type"];
                if (intval(strGet("id")) > 0) {
                    global $_contentID;
                    $_contentID = intval(strGet("id"));
                }
                switch ($_channelType) {
                    case 1:
                        require("modules/boxes/Admin_ArticleEditor.class.php");
                        $this->AddBox(new Admin_ArticleEditor());
                        break;
                    case 2:
                        require("modules/boxes/Admin_PictureEditor.class.php");
                        $this->AddBox(new Admin_PictureEditor());
                        break;
                    case 3:
                        require("modules/boxes/Admin_MediaEditor.class.php");
                        $this->AddBox(new Admin_MediaEditor());
                        break;
                    case 4:
                        require("modules/boxes/Admin_SoftwareEditor.class.php");
                        $this->AddBox(new Admin_SoftwareEditor());
                        break;
                }
            }
            db_free($rs);
        }
    }

}
