<?php
/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

//-----------------------charset--------------------------------------
define("dbCharset", "utf8");
define("dbCollate", "utf8_general_ci");
//-----------------------data--------------------------------------
$sysfiles = array(
    "admin_account.php",
    "admin_upload.php",
    "lang.php",
    "admin_ads.php",
    "login.php",
    "admin_channel.php",
    "article.php",
    "media.php",
    "admin_class.php",
    "admin_content.php",
    "channel.php",
    "picture.php",
    "admin_editor_uploader.php",
    "checkcode.php",
    "rss.php",
    "admin_editor_uploadlist.php",
    "admin_event.php",
    "class.php",
    "search.php",
    "admin_friendsite.php",
    "software.php",
    "admin.php",
    "friendsite.php",
    "admin_recyclebin.php",
    "guestbook.php",
    "admin_script.php",
    "theme.php",
    "admin_setting.php",
    "index.php",
    "admin_theme.php",
    "vote.php",
    "lang/en/main.lang.php",
    "lang/zh-cn/main.lang.php",
    "ckeditor/ckeditor.js",
    "modules/ContentList.class.php",
    "modules/PagingBar.class.php",
    "modules/Passport.class.php",
    "modules/filters.lib.php",
    "modules/Stopwatch.class.php",
    "modules/common.module.php",
    "modules/HTMLSelect.class.php",
    "modules/config.php",
    "modules/uploadlist.module.php",
    "modules/ContentCtlBar.module.php",
    "modules/VoteList.module.php",
    "modules/cache/PHPCacheEditor.class.php",
    "modules/cache/PHPCacheReader.class.php",
    "modules/uimodel/Box.class.php",
    "modules/uimodel/ListModel.class.php",
    "modules/uimodel/TableModel.class.php",
    "modules/uimodel/BoxFactory.class.php",
    "modules/uimodel/PageModel.class.php",
    "modules/data/article_admin.module.php",
    "modules/data/content_admin.module.php",
    "modules/data/sql_content.module.php",
    "modules/data/channel_admin.module.php",
    "modules/data/friendsite_admin.module.php",
    "modules/data/style_admin.module.php",
    "modules/data/class_admin.module.php",
    "modules/data/media_admin.module.php",
    "modules/data/Uploader.class.php",
    "modules/data/clipboard.module.php",
    "modules/data/picture_admin.module.php",
    "modules/data/user_admin.module.php",
    "modules/data/comment_admin.module.php",
    "modules/data/setting.module.php",
    "modules/data/vote_admin.module.php",
    "modules/data/comment.module.php",
    "modules/data/software_admin.module.php",
    "modules/lists/CommentList.class.php",
    "modules/lists/Navigator.class.php",
    "modules/lists/TitleList.class.php",
    "modules/lists/ImageList.class.php",
    "modules/lists/SiteList.class.php",
    "modules/pages/AdminPage.class.php",
    "modules/pages/PopupPage.class.php",
    "modules/pages/UserAdminPage.class.php",
    "modules/pages/ContentAdminPage.class.php",
    "modules/pages/PortalPage.class.php",
    "modules/pages/EventAdminPage.class.php",
    "modules/pages/SettingAdminPage.class.php",
    "modules/boxfactories/ArticleBoxFactory.class.php",
    "modules/boxfactories/GuestbookMsgList.class.php",
    "modules/boxfactories/ChannelBoxList.class.php",
    "modules/boxfactories/MediaBoxFactory.class.php",
    "modules/boxfactories/ClassBoxList.class.php",
    "modules/boxfactories/PictureBoxFactory.class.php",
    "modules/boxfactories/ContentEditorFactory.class.php",
    "modules/boxfactories/SoftwareBoxFactory.class.php",
    "modules/boxes/Admin_AddUser.class.php",
    "modules/boxes/Admin_UserList.class.php",
    "modules/boxes/Admin_AdsList.class.php",
    "modules/boxes/Admin_VoteDescEditor.class.php",
    "modules/boxes/Admin_ArticleEditor.class.php",
    "modules/boxes/Admin_VoteList.class.php",
    "modules/boxes/Admin_BasicSettings.class.php",
    "modules/boxes/AdsBox.class.php",
    "modules/boxes/Admin_BNaviBox.class.php",
    "modules/boxes/BannerBox.class.php",
    "modules/boxes/Admin_ChannelEditor.class.php",
    "modules/boxes/BottomNaviBox.class.php",
    "modules/boxes/Admin_ChannelList.class.php",
    "modules/boxes/CalendarBox.class.php",
    "modules/boxes/Admin_ChPWD.class.php",
    "modules/boxes/ChannelBox.class.php",
    "modules/boxes/Admin_ClassEditor.class.php",
    "modules/boxes/ChannelNaviBox.class.php",
    "modules/boxes/Admin_ClassList.class.php",
    "modules/boxes/ClassBox.class.php",
    "modules/boxes/Admin_ClearRecyclebin.class.php",
    "modules/boxes/ClassNaviBox.class.php",
    "modules/boxes/Admin_ContentList.class.php",
    "modules/boxes/ContentListBox.class.php",
    "modules/boxes/Admin_DetailSettings.class.php",
    "modules/boxes/FriendSiteBox.class.php",
    "modules/boxes/Admin_FriendSiteEditor.class.php",
    "modules/boxes/GuestBookBox.class.php",
    "modules/boxes/Admin_FriendSiteList.class.php",
    "modules/boxes/GuestbookCtlBox.class.php",
    "modules/boxes/Admin_Guestbook.class.php",
    "modules/boxes/GuestbookEditor.class.php",
    "modules/boxes/Admin_MediaEditor.class.php",
    "modules/boxes/GuestbookMessage.class.php",
    "modules/boxes/Admin_NaviBox.class.php",
    "modules/boxes/LangBox.class.php",
    "modules/boxes/Admin_Notice.class.php",
    "modules/boxes/MsgBox.class.php",
    "modules/boxes/Admin_PictureEditor.class.php",
    "modules/boxes/NoticeBoardBox.class.php",
    "modules/boxes/Admin_RecycledList.class.php",
    "modules/boxes/SearchBox.class.php",
    "modules/boxes/Admin_ScriptList.class.php",
    "modules/boxes/SearchListBox.class.php",
    "modules/boxes/Admin_SoftwareEditor.class.php",
    "modules/boxes/StyleListBox.class.php",
    "modules/boxes/Admin_StyleList.class.php",
    "modules/boxes/TopBox.class.php",
    "modules/boxes/Admin_UploadForm.class.php",
    "modules/boxes/VoteBox.class.php",
    "modules/boxes/Admin_UploadList.class.php",
    "templates/Admin_AdsList/zh-cn/setting_ads.tpl",
    "templates/Admin_AdsList/en/setting_ads.tpl",
    "templates/ArticleBoxFactory/zh-cn/article.tpl",
    "templates/ArticleBoxFactory/en/article.tpl",
    "templates/Admin_UserList/zh-cn/userlist.tpl",
    "templates/Admin_UserList/zh-cn/userlist_item.tpl",
    "templates/Admin_UserList/en/userlist.tpl",
    "templates/Admin_UserList/en/userlist_item.tpl",
    "templates/Admin_ClassEditor/zh-cn/class_editor.tpl",
    "templates/Admin_ClassEditor/en/class_editor.tpl",
    "templates/Admin_DetailSettings/zh-cn/setting_detail.tpl",
    "templates/Admin_DetailSettings/en/setting_detail.tpl",
    "templates/Admin_VoteList/zh-cn/voteform_editor.tpl",
    "templates/Admin_VoteList/en/voteform_editor.tpl",
    "templates/Admin_UploadForm/zh-cn/upload.tpl",
    "templates/Admin_UploadForm/en/upload.tpl",
    "templates/Admin_FriendSiteEditor/zh-cn/setting_friendsite.tpl",
    "templates/Admin_FriendSiteEditor/en/setting_friendsite.tpl",
    "templates/StyleListBox/zh-cn/stylelist_select_item.tpl",
    "templates/StyleListBox/zh-cn/stylelist_select.tpl",
    "templates/StyleListBox/en/stylelist_select_item.tpl",
    "templates/StyleListBox/en/stylelist_select.tpl",
    "templates/ContentList/zh-cn/contentlist_pagination.tpl",
    "templates/ContentList/zh-cn/contentlist_morebtn.tpl",
    "templates/ContentList/en/contentlist_pagination.tpl",
    "templates/ContentList/en/contentlist_morebtn.tpl",
    "templates/MediaBoxFactory/zh-cn/media.tpl",
    "templates/MediaBoxFactory/zh-cn/media_fp.tpl",
    "templates/MediaBoxFactory/zh-cn/media_rp.tpl",
    "templates/MediaBoxFactory/zh-cn/media_info.tpl",
    "templates/MediaBoxFactory/zh-cn/media_if.tpl",
    "templates/MediaBoxFactory/zh-cn/media_wp.tpl",
    "templates/MediaBoxFactory/en/media.tpl",
    "templates/MediaBoxFactory/en/media_fp.tpl",
    "templates/MediaBoxFactory/en/media_rp.tpl",
    "templates/MediaBoxFactory/en/media_info.tpl",
    "templates/MediaBoxFactory/en/media_if.tpl",
    "templates/MediaBoxFactory/en/media_wp.tpl",
    "templates/Admin_AddUser/zh-cn/account_add.tpl",
    "templates/Admin_AddUser/en/account_add.tpl",
    "templates/Admin_MediaEditor/zh-cn/media_editor.tpl",
    "templates/Admin_MediaEditor/en/media_editor.tpl",
    "templates/Admin_ChannelEditor/zh-cn/channel_editor.tpl",
    "templates/Admin_ChannelEditor/en/channel_editor.tpl",
    "templates/Admin_StyleList/zh-cn/stylelist_edit_item.tpl",
    "templates/Admin_StyleList/zh-cn/stylelist_edit.tpl",
    "templates/Admin_StyleList/en/stylelist_edit_item.tpl",
    "templates/Admin_StyleList/en/stylelist_edit.tpl",
    "templates/Admin_RecycledList/zh-cn/recyclebin_list.tpl",
    "templates/Admin_RecycledList/zh-cn/recyclebin_item.tpl",
    "templates/Admin_RecycledList/en/recyclebin_list.tpl",
    "templates/Admin_RecycledList/en/recyclebin_item.tpl",
    "templates/GuestbookEditor/zh-cn/guestbook_editor.tpl",
    "templates/GuestbookEditor/en/guestbook_editor.tpl",
    "templates/PageModel/zh-cn/popuppage.tpl",
    "templates/PageModel/zh-cn/portalpage.tpl",
    "templates/PageModel/zh-cn/adminpage.tpl",
    "templates/PageModel/en/popuppage.tpl",
    "templates/PageModel/en/portalpage.tpl",
    "templates/PageModel/en/adminpage.tpl",
    "templates/BannerBox/logobanner.tpl",
    "templates/SiteList/zh-cn/sitelist_container.tpl",
    "templates/SiteList/zh-cn/sitelist_empty_editor.tpl",
    "templates/SiteList/zh-cn/sitelist_container_editor.tpl",
    "templates/SiteList/zh-cn/sitelist_container_small.tpl",
    "templates/SiteList/zh-cn/sitelist_empty.tpl",
    "templates/SiteList/zh-cn/sitelist_item.tpl",
    "templates/SiteList/zh-cn/sitelist_item_small.tpl",
    "templates/SiteList/zh-cn/sitelist_item_editor.tpl",
    "templates/SiteList/en/sitelist_container.tpl",
    "templates/SiteList/en/sitelist_empty_editor.tpl",
    "templates/SiteList/en/sitelist_container_editor.tpl",
    "templates/SiteList/en/sitelist_container_small.tpl",
    "templates/SiteList/en/sitelist_empty.tpl",
    "templates/SiteList/en/sitelist_item.tpl",
    "templates/SiteList/en/sitelist_item_small.tpl",
    "templates/SiteList/en/sitelist_item_editor.tpl",
    "templates/MsgBox/zh-cn/msg.tpl",
    "templates/MsgBox/en/msg.tpl",
    "templates/zh-cn/login.tpl",
    "templates/zh-cn/event_addvoteitem.tpl",
    "templates/zh-cn/uploadlist_js.tpl",
    "templates/zh-cn/content_controlbar.tpl",
    "templates/zh-cn/uploadlist_js_ckeditor.tpl",
    "templates/zh-cn/code_editor.tpl",
    "templates/zh-cn/event_home.tpl",
    "templates/zh-cn/setting_home.tpl",
    "templates/zh-cn/content_controlbar_item.tpl",
    "templates/zh-cn/account_delete.tpl",
    "templates/zh-cn/uploadlist.tpl",
    "templates/zh-cn/uploadlist_js_editor.tpl",
    "templates/zh-cn/uploadlist_item.tpl",
    "templates/Admin_BasicSettings/zh-cn/setting_basic.tpl",
    "templates/Admin_BasicSettings/en/setting_basic.tpl",
    "templates/PictureBoxFactory/picture_info.tpl",
    "templates/PictureBoxFactory/picture.tpl",
    "templates/Admin_ContentList/zh-cn/contentlist_admin_btn.tpl",
    "templates/Admin_ContentList/zh-cn/contentlist_admin_btn_move.tpl",
    "templates/Admin_ContentList/zh-cn/contentlist_admin.tpl",
    "templates/Admin_ContentList/en/contentlist_admin_btn.tpl",
    "templates/Admin_ContentList/en/contentlist_admin_btn_move.tpl",
    "templates/Admin_ContentList/en/contentlist_admin.tpl",
    "templates/GuestbookMessage/zh-cn/guestbook_message.tpl",
    "templates/GuestbookMessage/en/guestbook_message.tpl",
    "templates/VoteBox/zh-cn/voteform_small.tpl",
    "templates/VoteBox/zh-cn/voteform.tpl",
    "templates/VoteBox/en/voteform_small.tpl",
    "templates/VoteBox/en/voteform.tpl",
    "templates/Admin_ChannelList/zh-cn/channellist.tpl",
    "templates/Admin_ChannelList/zh-cn/channellist_item.tpl",
    "templates/Admin_ChannelList/en/channellist.tpl",
    "templates/Admin_ChannelList/en/channellist_item.tpl",
    "templates/Box/banner.tpl",
    "templates/Box/box1.tpl",
    "templates/Box/box2.tpl",
    "templates/Box/box3.tpl",
    "templates/Admin_ClassList/zh-cn/classlist_btn_move.tpl",
    "templates/Admin_ClassList/zh-cn/classlist_btn.tpl",
    "templates/Admin_ClassList/zh-cn/classlist_item.tpl",
    "templates/Admin_ClassList/zh-cn/classlist.tpl",
    "templates/Admin_ClassList/en/classlist_btn_move.tpl",
    "templates/Admin_ClassList/en/classlist_btn.tpl",
    "templates/Admin_ClassList/en/classlist_item.tpl",
    "templates/Admin_ClassList/en/classlist.tpl",
    "templates/Admin_PictureEditor/zh-cn/picture_editor.tpl",
    "templates/Admin_PictureEditor/en/picture_editor.tpl",
    "templates/Admin_ClearRecyclebin/zh-cn/recyclebin_clearconfirm.tpl",
    "templates/Admin_ClearRecyclebin/en/recyclebin_clearconfirm.tpl",
    "templates/SearchListBox/zh-cn/search_container.tpl",
    "templates/SearchListBox/zh-cn/search_item.tpl",
    "templates/SearchListBox/en/search_container.tpl",
    "templates/SearchListBox/en/search_item.tpl",
    "templates/LangBox/zh-cn/item.tpl",
    "templates/LangBox/zh-cn/container.tpl",
    "templates/LangBox/en/item.tpl",
    "templates/LangBox/en/container.tpl",
    "templates/CommentList/zh-cn/commentlist_item.tpl",
    "templates/CommentList/zh-cn/commentlist_container.tpl",
    "templates/CommentList/zh-cn/commentlist_admin_item.tpl",
    "templates/CommentList/zh-cn/commentlist_admin.tpl",
    "templates/CommentList/en/commentlist_item.tpl",
    "templates/CommentList/en/commentlist_container.tpl",
    "templates/CommentList/en/commentlist_admin_item.tpl",
    "templates/CommentList/en/commentlist_admin.tpl",
    "templates/SearchBox/searchform.tpl",
    "templates/SoftwareBoxFactory/software.tpl",
    "templates/TitleList/zh-cn/titlelist_item_checkbox.tpl",
    "templates/TitleList/zh-cn/titlelist_container.tpl",
    "templates/TitleList/zh-cn/titlelist_item_empty.tpl",
    "templates/TitleList/zh-cn/titlelist_item.tpl",
    "templates/TitleList/en/titlelist_item_checkbox.tpl",
    "templates/TitleList/en/titlelist_container.tpl",
    "templates/TitleList/en/titlelist_item_empty.tpl",
    "templates/TitleList/en/titlelist_item.tpl",
    "templates/Admin_SoftwareEditor/zh-cn/software_editor.tpl",
    "templates/Admin_SoftwareEditor/en/software_editor.tpl",
    "templates/Admin_VoteDescEditor/zh-cn/event_setvotedesc.tpl",
    "templates/Admin_VoteDescEditor/en/event_setvotedesc.tpl",
    "templates/Admin_ScriptList/zh-cn/scriptlist_container.tpl",
    "templates/Admin_ScriptList/zh-cn/scriptlist_item.tpl",
    "templates/Admin_ScriptList/en/scriptlist_container.tpl",
    "templates/Admin_ScriptList/en/scriptlist_item.tpl",
    "templates/GuestbookCtlBox/zh-cn/guestbook_controlbox.tpl",
    "templates/GuestbookCtlBox/en/guestbook_controlbox.tpl",
    "templates/Admin_ArticleEditor/zh-cn/article_editor.tpl",
    "templates/Admin_ArticleEditor/en/article_editor.tpl",
    "templates/en/login.tpl",
    "templates/en/event_addvoteitem.tpl",
    "templates/en/uploadlist_js.tpl",
    "templates/en/content_controlbar.tpl",
    "templates/en/uploadlist_js_ckeditor.tpl",
    "templates/en/code_editor.tpl",
    "templates/en/event_home.tpl",
    "templates/en/setting_home.tpl",
    "templates/en/content_controlbar_item.tpl",
    "templates/en/account_delete.tpl",
    "templates/en/uploadlist.tpl",
    "templates/en/uploadlist_js_editor.tpl",
    "templates/en/uploadlist_item.tpl",
    "templates/Navigator/navilink.tpl",
    "templates/Navigator/navibutton.tpl",
    "templates/Admin_ChPWD/zh-cn/account_changepwd.tpl",
    "templates/Admin_ChPWD/en/account_changepwd.tpl",
    "templates/ImageList/imagelist_item_empty.tpl",
    "templates/ImageList/imagelist_item_rest.tpl",
    "templates/ImageList/imagelist_row.tpl",
    "templates/ImageList/imagelist_item.tpl",
    "templates/ImageList/imagelist_item_checkbox.tpl",
    "templates/ImageList/imagelist_table.tpl",
    "templates/Admin_Notice/zh-cn/event_notice.tpl",
    "templates/Admin_Notice/en/event_notice.tpl"
);

$systables = array();
$systables[0] = "CREATE TABLE IF NOT EXISTS setting_info (";
$systables[0] .= "setting_name char(50) NOT NULL,";
$systables[0] .= "setting_type enum('0','1','2') NOT NULL default '0',";
$systables[0] .= "setting_value char(255) NULL,";
$systables[0] .= "PRIMARY KEY  (setting_name)";
$systables[0] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[1] = "CREATE TABLE IF NOT EXISTS article_info (";
$systables[1] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[1] .= "article_name CHAR(255) NULL,";
$systables[1] .= "parent_class INT(10) NOT NULL,";
$systables[1] .= "article_author CHAR(255) NULL,";
$systables[1] .= "article_time DATETIME NOT NULL,";
$systables[1] .= "article_text TEXT NULL,";
$systables[1] .= "article_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[1] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[1] .= "PRIMARY KEY (id),";
$systables[1] .= "KEY parent_class (parent_class)";
$systables[1] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[2] = "CREATE TABLE IF NOT EXISTS software_info (";
$systables[2] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[2] .= "software_name CHAR(50) NULL,";
$systables[2] .= "parent_class INT(10),";
$systables[2] .= "software_add CHAR(255) NULL,";
$systables[2] .= "software_producer CHAR(50) NULL,";
$systables[2] .= "software_type CHAR(50) NULL,";
$systables[2] .= "software_language CHAR(50) NULL,";
$systables[2] .= "software_size INT(10) NOT NULL DEFAULT 0,";
$systables[2] .= "software_environment CHAR(50) NULL,";
$systables[2] .= "software_grade INT(1) NOT NULL DEFAULT 2,";
$systables[2] .= "software_text TEXT NULL,";
$systables[2] .= "software_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[2] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[2] .= "software_time DATETIME,";
$systables[2] .= "PRIMARY KEY (id),";
$systables[2] .= "KEY parent_class (parent_class)";
$systables[2] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[3] = "CREATE TABLE IF NOT EXISTS picture_info (";
$systables[3] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[3] .= "picture_name CHAR(50) NULL,";
$systables[3] .= "parent_class INT(10),";
$systables[3] .= "picture_add CHAR(255) NULL,";
$systables[3] .= "picture_text TEXT NULL,";
$systables[3] .= "picture_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[3] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[3] .= "picture_time DATETIME,";
$systables[3] .= "PRIMARY KEY (id),";
$systables[3] .= "KEY parent_class (parent_class)";
$systables[3] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[4] = "CREATE TABLE IF NOT EXISTS media_info (";
$systables[4] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[4] .= "media_name CHAR(255) NULL,";
$systables[4] .= "parent_class INT(10),";
$systables[4] .= "media_add CHAR(255) NULL,";
$systables[4] .= "media_text TEXT NULL,";
$systables[4] .= "media_HTML ENUM('true','false') NOT NULL DEFAULT 'false',";
$systables[4] .= "visitor_count INT(10) NOT NULL DEFAULT 0,";
$systables[4] .= "media_time DATETIME,";
$systables[4] .= "PRIMARY KEY (id),";
$systables[4] .= "KEY parent_class (parent_class)";
$systables[4] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[5] = "CREATE TABLE IF NOT EXISTS guest_info (";
$systables[5] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[5] .= "guest_IP CHAR(15),";
$systables[5] .= "guest_date DATETIME,";
$systables[5] .= "guest_name CHAR(50) NULL,";
$systables[5] .= "guest_head INT(2) NOT NULL DEFAULT 1,";
$systables[5] .= "guest_mail CHAR(50) NULL,";
$systables[5] .= "guest_title CHAR(50) NULL,";
$systables[5] .= "guest_homepage CHAR(255) NULL,";
$systables[5] .= "guest_text TEXT,";
$systables[5] .= "PRIMARY KEY (id)";
$systables[5] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[6] = "CREATE TABLE IF NOT EXISTS class_info (";
$systables[6] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[6] .= "class_name CHAR(50) NULL,";
$systables[6] .= "parent_channel INT(10),";
$systables[6] .= "PRIMARY KEY (id),";
$systables[6] .= "KEY parent_channel (parent_channel)";
$systables[6] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[7] = "CREATE TABLE IF NOT EXISTS channel_info (";
$systables[7] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[7] .= "channel_name CHAR(50) NULL,";
$systables[7] .= "channel_add CHAR(255) NULL,";
$systables[7] .= "channel_type INT(1),";
$systables[7] .= "channel_image CHAR(255) NULL,";
$systables[7] .= "PRIMARY KEY (id),";
$systables[7] .= "KEY channel_type (channel_type)";
$systables[7] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[8] = "CREATE TABLE IF NOT EXISTS friendsite_info (";
$systables[8] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[8] .= "site_name CHAR(50) NULL,";
$systables[8] .= "site_add CHAR(255) NULL,";
$systables[8] .= "site_logo CHAR(255) NULL,";
$systables[8] .= "site_text CHAR(100) NULL,";
$systables[8] .= "PRIMARY KEY (id)";
$systables[8] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[9] = "CREATE TABLE IF NOT EXISTS admin_info (";
$systables[9] .= "admin_UID CHAR(50) NOT NULL,";
$systables[9] .= "admin_PWD CHAR(100) NOT NULL,";
$systables[9] .= "PRIMARY KEY (admin_UID)";
$systables[9] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[10] = "CREATE TABLE IF NOT EXISTS style_info (";
$systables[10] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[10] .= "style_name CHAR(50),";
$systables[10] .= "style_imagefolder CHAR(50),";
$systables[10] .= "style_cssfile CHAR(50),";
$systables[10] .= "PRIMARY KEY (id)";
$systables[10] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[11] = "CREATE TABLE IF NOT EXISTS vote_info  (";
$systables[11] .= "id INT(10) NOT NULL AUTO_INCREMENT,";
$systables[11] .= "vote_name CHAR(50) NOT NULL,";
$systables[11] .= "vote_value INT(10),";
$systables[11] .= "PRIMARY KEY (id)";
$systables[11] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

$systables[12] = "CREATE TABLE IF NOT EXISTS upload_info  (";
$systables[12] .= "upload_filename CHAR(255) NOT NULL,";
$systables[12] .= "upload_filecaption CHAR(255) NOT NULL,";
$systables[12] .= "PRIMARY KEY (upload_filename)";
$systables[12] .= ") ENGINE=MyISAM DEFAULT CHARSET=" . dbCharset . ";";

//-----------------------utilities--------------------------------------
function _GetSystemPath() {
    $a = str_replace("\\", "/", $_SERVER["SCRIPT_FILENAME"]); //for windows
    //$a = str_replace("\\", "/",$_SERVER["PATH_TRANSLATED"]);
    if (substr($a, -1) != "/") {
        $b = strrpos($a, "/");
        if ($b > 0)
            $a = substr($a, 0, $b);
    }
    return $a;
}

function _strPost($strname) {
    if (isset($_POST[$strname])) {
        if (get_magic_quotes_gpc()) {
            return stripslashes($_POST[$strname]);
        } else {
            return $_POST[$strname];
        }
    }
    return "";
}

function _ErrorList(&$errortext) {
    $et = "";
    if ($errortext != "") {
        $et .= "<ul>";
        if (strpos($errortext, ";")) {
            $errorset = explode(";", $errortext);
            foreach ($errorset as $erroritem)
                if (strlen($erroritem) > 0)
                    $et .= "<li>$erroritem</li>";
        }else
            $et .= "<li>$errortext</li>";
        $et .= "</ul>";
    }
    return $et;
}

function _toScriptString($str) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace("\"", "\\\"", $str);
    return "\"$str\"";
}

function PrintResult($result) {
    if ($result) {
        echo "……<span style=\"color: #80A1D6\">成功</span><br />";
    } else {
        echo "……<span style=\"color: #FF0000\">失败</span><br />";
    }
}

//-----------------------prepare--------------------------------------
function FuncNotSupported() {
    $funclist = array(
        array("fopen", "文件（档案）系统模块"),
        array("session_start", "Session模块"),
        array("mysql_connect", "MySQL数据库模块")
    );
    $s = array();
    foreach ($funclist as $func) {
        if (!function_exists($func[0]))
            $s[] = $func[1];
    }
    return $s;
}

function IsComplete(&$db2files) {
    $e = TRUE;
    $syspath = _GetSystemPath() . "/";
    foreach ($db2files as $db2file) {
        if (!file_exists($syspath . $db2file)) {
            $e = FALSE;
            break;
        }
    }
    return $e;
}

function PrepareInstallation(&$files) {
    if (IsComplete($files)) {
        $NotSupported = FuncNotSupported();
        if (count($NotSupported) == 0) {
            ShowWelcome();
        } else {
            echo "<div style=\"text-align: left;\">您的PHP不支持：<ul>";
            foreach ($NotSupported as $nsItem) {
                echo "<li>$nsItem</li>";
            }
            echo "</ul></div>";
        }
    } else {
        echo "DigitalBox系统不完整，可能已经被破坏";
    }
}

function IsDBConfigured() {
    if (!defined("dbMySQLHostName")) {
        require_once("modules/config.php");
    }
    return TestDBConn(dbMySQLHostName, dbMySQLUserName, dbMySQLPassword);
}

function ShowWelcome() {
    echo "<table>";
    echo "<tr><td rowspan=\"2\"><img src=\"images/logo2.gif\"></td><th valign=\"middle\" align=\"center\">欢迎使用 DigitalBox 2.7</th></tr>";
    echo "<tr><td align=\"center\">Copyright &copy 2010-2012 (by GuZhiji Studio)</td></tr>";
    echo "<tr><td align=\"center\" colspan=2>";
    if (IsDBConfigured()) {
        echo "<input type=\"button\" value=\"使用当前数据库设置\" class=\"button1\" onclick=\"window.location.href='?step=3'\">";
        echo " <input type=\"button\" value=\"重设数据库\" class=\"button1\" onclick=\"window.location.href='?step=1'\">";
    } else {
        echo "<input type=\"button\" value=\"下一步\" class=\"button1\" onclick=\"window.location.href='?step=1'\">";
    }
    echo " <input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"window.location.href='toolchecker.php'\"></td></tr>";
    echo "</table>";
}

//-----------------------step1--------------------------------------
function ShowDBConnForm() {
    echo "<form method=\"post\" action=\"?step=2\"><table>";
    echo "<tr><td align=\"right\">数据库主机：</td><td><input type=\"text\" name=\"HostName\" value=\"localhost\"></td></tr>";
    echo "<tr><td align=\"right\">数据库用户：</td><td><input type=\"text\" name=\"UserName\" value=\"root\"></td></tr>";
    echo "<tr><td align=\"right\">数据库密码：</td><td><input type=\"password\" name=\"Password\"></td></tr>";
    echo "<tr><td align=\"right\">数据库名称：</td><td><input type=\"text\" name=\"DBName\" value=\"DigitalBoxV2\"></td></tr>";
    echo "<tr><td align=\"right\">系统前缀：</td><td><input type=\"text\" name=\"Prefix\" value=\"DB2\"></td></tr>";
    echo "<tr><td></td><td>（多个DB2系统不互相干扰时需要改变）</td></tr>";
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"下一步\" class=\"button1\"></td></tr>";
    echo "</table></form>";
}

//-----------------------step2--------------------------------------
function SaveConfig($HostName, $UserName, $Password, $DBName, $Prefix) {
    $HostName = str_replace("\"", "\\\"", $HostName);
    $UserName = str_replace("\"", "\\\"", $UserName);
    $Password = str_replace("\"", "\\\"", $Password);
    $DBName = str_replace("\"", "\\\"", $DBName);
    $Prefix = str_replace("\"", "\\\"", $Prefix);
    $cc = "<?php\n";
    $cc .= "//--Database---------------------------------\n";
    $cc .= "define(\"dbMySQLHostName\",\"$HostName\");\n";
    $cc .= "define(\"dbMySQLUserName\",\"$UserName\");\n";
    $cc .= "define(\"dbMySQLPassword\",\"$Password\");\n";
    $cc .= "define(\"dbDatabaseName\",\"$DBName\");\n";
    $cc .= "//--Prefix-----------------------------------\n";
    $cc .= "//如果在您的服务器上使用了多个DigitalBox 2.x，则应使用不同的dbPrefix\n";
    $cc .= "//一般可默认为“DB2”\n";
    $cc .= "define(\"dbPrefix\",\"$Prefix\");\n";
    $cc .= "?>";
    $fp = @fopen(_GetSystemPath() . "/modules/config.php", "w");
    if ($fp) {
        fputs($fp, $cc);
        fclose($fp);
        return TRUE;
    }
    return FALSE;
}

function TestDBConn($HostName, $UserName, $Password) {
    $cid = @mysql_connect($HostName, $UserName, $Password);
    if ($cid) {
        mysql_close($cid);
        return TRUE;
    } else {
        return FALSE;
    }
}

function ConfigDatabase() {

    $cuHostName = _strPost("HostName");
    $cuUserName = _strPost("UserName");
    $cuPassword = _strPost("Password");
    $cuDBName = _strPost("DBName");
    $cuPrefix = _strPost("Prefix");

    $err_tip = "";
    if ($cuHostName == "")
        $err_tip .= "数据库主机不能为空;";
    if ($cuUserName == "")
        $err_tip .= "数据库用户不能为空;";
    if ($cuDBName == "")
        $err_tip .= "数据库名称不能为空;";
    if ($cuPrefix == "")
        $err_tip .= "系统前缀不能为空;";
    if (strlen($cuPrefix) > 10)
        $err_tip .= "系统前缀过长;";

    if (!TestDBConn($cuHostName, $cuUserName, $cuPassword)) {
        $err_tip .= "无法接上此数据库;";
    }

    if ($err_tip != "") {
        echo "<table><tr><td>" . _ErrorList($err_tip) . "</td></tr><tr><td align=\"center\"><input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"history.back(1)\"></td></tr></table>";
    } else {
        if (!SaveConfig($cuHostName, $cuUserName, $cuPassword, $cuDBName, $cuPrefix)) {
            echo "<table><tr><td>可能是权限问题，设置信息无法保存，请到 modules/config.php 文件中手工填写，然后重新开始安装，并选择<b>使用当前数据库设置</b></td></tr><tr><td align=\"center\"><input type=\"button\" value=\"确定\" class=\"button1\" onclick=\"window.location.href='?step=0'\"></td></tr></table>";
        } else {
            echo "<table><tr><td align=\"center\">设置信息已经保存在本系统根目录下的 modules/config.php 文件中了。下一步是输入站长信息。</td></tr><tr><td align=\"center\"><input type=\"button\" class=\"button1\" value=\"下一步\" onclick=\"window.location.href='?step=3'\"></td></tr></table>";
        }
    }
}

//-----------------------step3--------------------------------------
function ShowMasterForm() {
    echo "<form method=\"post\" action=\"?step=4\"><table>";
    echo "<tr><td align=\"right\">用户名称：</td><td><input type=\"text\" name=\"Master_UID\"></td></tr>";
    echo "<tr><td align=\"right\">用户密码：</td><td><input type=\"password\" name=\"Master_PWD\"></td></tr>";
    echo "<tr><td align=\"right\">密码确认：</td><td><input type=\"password\" name=\"Master_CheckPWD\"></td></tr>";
    echo "<tr><td align=\"right\">站长邮箱：</td><td><input type=\"text\" name=\"Master_Mail\"></td></tr>";
    echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"下一步\" class=\"button1\"></td></tr>";
    echo "</table></form>";
}

//-----------------------step4--------------------------------------
function GetTableName($sqltext) {
    return trim(substr($sqltext, strlen("CREATE TABLE IF NOT EXISTS "), strpos($sqltext, "(") - strlen("CREATE TABLE IF NOT EXISTS ")));
}

function MakeTables(&$tables) {
    $r = TRUE;
    foreach ($tables as $sql) {
        echo "构建数据表：" . GetTableName($sql);
        $r = !!db_query($sql);
        PrintResult($r);
    }
    return $r;
}

function ImportSettings() {
    $reader = new PHPCacheReader("cache", "settings");
    $settingkeys = $reader->GetKeys();
    foreach ($settingkeys as $skey) {
        $svalue = $reader->GetValue($skey);
        if (is_array($svalue))
            continue;

        echo "注入设置信息：" . $skey;
        $notfound = TRUE;
        $rs = db_query("SELECT setting_name FROM setting_info WHERE setting_name=\"%s\"", array($skey));
        if ($rs) {
            $list = db_result($rs);
            if (isset($list[0])) {
                $notfound = FALSE;
            }
            db_free($rs);
        }

        if ($notfound) {
            $type = 0;
            if (!is_string($svalue)) {
                if (is_bool($svalue)) {
                    $type = 2;
                    $svalue = $svalue ? "true" : "false";
                } else {
                    $type = 1;
                    if (is_int($svalue))
                        $svalue = strval($svalue);
                    else
                        $svalue = strval(doubleval($svalue));
                }
            }
            PrintResult(!!db_query("INSERT INTO setting_info (setting_name,setting_type,setting_value) VALUES (\"%s\",\"%s\",\"%s\")", array($skey, $type, $svalue)));
        } else if ($skey == "master_name" || $skey == "master_mail") {
            PrintResult(!!db_query("UPDATE setting_info SET setting_value=\"%s\" WHERE setting_name=\"%s\"", array($svalue, $skey)));
        } else {
            PrintResult(TRUE);
        }
    }
}

function SaveMaster($Master_UID, $Master_PWD) {
    require_once("modules/Passport.class.php");
    $found = FALSE;
    $rs = db_query("SELECT admin_UID FROM admin_info WHERE admin_UID=\"%s\"", array($Master_UID));
    if ($rs) {
        $list = db_result($rs);
        if (isset($list[0])) {
            $found = TRUE;
        }
        db_free($rs);
    }
    $r = FALSE;
    if ($found) {
        echo "修改站长密码";
        $r = !!db_query("UPDATE admin_info SET admin_PWD=\"%s\" WHERE admin_UID=\"%s\"", array(Passport::PWDEncrypt($Master_PWD), $Master_UID));
    } else {
        echo "加入站长管理员";
        $r = !!db_query("INSERT INTO admin_info (admin_UID,admin_PWD) VALUES (\"%s\",\"%s\")", array($Master_UID, Passport::PWDEncrypt($Master_PWD)));
    }
    PrintResult($r);
}

function validateEMail($email) {
    return !!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/i", $email);
}

function SetupDatabase(&$tables) {

    require_once("modules/common.module.php");
    require_once("modules/data/user_admin.module.php");
    $Master_UID = _strPost("Master_UID");
    $Master_PWD = _strPost("Master_PWD");
    $Master_CheckPWD = _strPost("Master_CheckPWD");
    $Master_Mail = _strPost("Master_Mail");
    $err_tip = "";
    $err_tip .= User_Admin::UIDCheck($Master_UID);
    $err_tip .= User_Admin::PWDCheck($Master_PWD, $Master_CheckPWD, $Master_UID);

    if (!validateEMail($Master_Mail))
        $err_tip .= "站长邮箱地址错误;";

    echo "<table><tr><td align=\"left\">";
    if ($err_tip == "") {
        require_once("modules/config.php");
        $connid = db_connect();
        if ($connid) {
            if (!mysql_query("CREATE DATABASE IF NOT EXISTS " . dbDatabaseName . " DEFAULT CHARACTER SET " . dbCharset . " COLLATE " . dbCollate . ";", $connid)) {
                echo "数据库建立失败";
            } else {
                echo "准备开始……<br />";

                MakeTables($tables);

                echo "刷新设置信息脚本文件：cache/settings.php";

                require_once("modules/cache/PHPCacheEditor.class.php");
                $cm = new PHPCacheEditor("cache", "settings");
                $cm->SetValue("master_name", $Master_UID);
                $cm->SetValue("master_mail", $Master_Mail);
                try {
                    $cm->Save();
                    PrintResult(TRUE);
                } catch (Exception $ex) {
                    PrintResult(FALSE);
                }
                ImportSettings();

                SaveMaster($Master_UID, $Master_PWD);

                echo "与styles目录同步风格信息";
                require_once("modules/data/style_admin.module.php");
                PrintResult(SyncStyles());

                echo "完成！";
            }
            db_close();
        } else {
            echo "数据库连接失败";
        }
    } else {
        echo _ErrorList($err_tip);
    }
    echo "</td></tr><tr><td align=\"center\">";
    if (strlen($err_tip) > 0) {
        echo "<input type=\"button\" value=\"返回\" class=\"button1\" onclick=\"history.back(1)\">";
    } else {
        echo "<input type=\"button\" value=\"下一步\" class=\"button1\" onclick=\"window.location.href='toolchecker.php'\">";
    }
    echo "</td></tr></table>";
}

//-----------------------presentation--------------------------------------
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>系统安装 - 附加工具 - DigitalBox 2.7</title>
        <link rel="stylesheet" href="stylesheets/main.css" />
        <link rel="Shortcut Icon" href="DigitalBoxIcon.ico" />
    </head>
    <body>
    <center>
        <table border="0" cellspacing="0" cellpadding="0" width="580">
            <tr>
                <td class="bg_top3" align="left">&nbsp;&nbsp;系统安装</td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" width="100%"
                           height="100%">
                        <tr>
                            <td class="bg_border"></td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="20" width="100%"
                                       height="100%">
                                    <tr>
                                        <td align="center" valign="middle" class="bg_middle"><?php
if (!isset($_GET["step"])) {
    PrepareInstallation($sysfiles);
} else {
    switch (intval($_GET["step"])) {
        case 1:
            ShowDBConnForm();
            break;
        case 2:
            ConfigDatabase();
            break;
        case 3:
            ShowMasterForm();
            break;
        case 4:
            SetupDatabase($systables);
            break;
        default:
            PrepareInstallation($sysfiles);
    }
}
?></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="bg_border"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_bottom3"></td>
            </tr>
        </table>
    </center>
</body>
</html>
