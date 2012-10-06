<?php

/* ------------------------------------------------------------------
 * DigitalBox CMS 3.0
 * http://code.google.com/p/digitalbox/
 * 
 * Copyright 2010-2012, GuZhiji Studio <gu_zhiji@163.com>
 * This program is licensed under the GPL Version 3
 * ------------------------------------------------------------------
 */

class SQL_Content {

    var $_mode, $_typenames, $_order, $_classid, $_channelid, $_searchkey, $_contentid, $_extrafields;

    public function __construct() {
        $this->_mode = 0;
        $this->_order = 0;
        $this->_classid = 0;
        $this->_channelid = 0;
        $this->_searchkey = "";
        $this->_contentid = 0;
        $this->_extrafields = "";
        $this->_typenames[0] = "";
        $this->_typenames[1] = "article";
        $this->_typenames[2] = "picture";
        $this->_typenames[3] = "media";
        $this->_typenames[4] = "software";
    }

    /**
     * <ul>
     * <li>0 - all types</li>
     * <li>1 - article</li>
     * <li>2 - picture</li>
     * <li>3 - media</li>
     * <li>4 - software</li>
     * </ul>
     * @param int $m
     */
    public function SetMode($m) {
        if ($m > 0 && $m <= 4)
            $this->_mode = intval($m);
    }

    /**
     * <ul>
     * <li>1 - new first</li>
     * <li>2 - popular first</li>
     * <li>3 - by alphabet</li>
     * </ul>
     * @param int $order
     */
    public function SetOrder($order) {
        $this->_order = intval($order);
    }

    public function SetClassID($id) {
        if (is_numeric($id))
            $this->_classid = intval($id);
    }

    public function SetChannelID($id) {
        if (is_numeric($id))
            $this->_channelid = intval($id);
    }

    public function SetContentID($id) {
        if (is_numeric($id))
            $this->_contentid = intval($id);
    }

    public function SetSearchKey($key) {
        if ($key != "")
            $this->_searchkey = PrepareSearchKey($key);
    }

    public function AddField($fieldname) {
        $this->_extrafields = $this->_extrafields . "," . $fieldname;
    }

    private function _GetWhere($type) {
        $sql = " WHERE {$type}_info.parent_class=class_info.id";
        $sql .= " AND class_info.parent_channel=channel_info.id";
        if ($this->_classid > 0)
            $sql .= " AND {$type}_info.parent_class=" . $this->_classid;
        if ($this->_channelid > 0)
            $sql .= " AND class_info.parent_channel=" . $this->_channelid;
        //if ($this->_searchkey!="") $sql .= " AND content_name LIKE \"{$this->_searchkey}\"";
        if ($this->_searchkey != "")
            $sql .= " AND {$type}_info.{$type}_name LIKE \"{$this->_searchkey}\"";

        return $sql;
    }

    public function GetCountQuery() {
        /*
          SELECT sum( c )
          FROM (
          SELECT count( * ) AS c
          FROM article_info
          UNION ALL SELECT count( * ) AS c
          FROM media_info
          ) AS cc
         */
        $sql = "";
        if ($this->_mode > 0) {
            $sql .= "SELECT count(*) AS c";
            $sql .= " FROM {$this->_typenames[$this->_mode]}_info,channel_info,class_info";
            $sql .= $this->_GetWhere($this->_typenames[$this->_mode]);
            if ($this->_contentid > 0)
                $sql .= " AND {$this->_typenames[$this->_mode]}_info.id=" . $this->_contentid;
        }else {
            for ($a = 1; $a <= 4; $a++) {
                $sql .= "SELECT count(*) AS cc";
                $sql .= " FROM {$this->_typenames[$a]}_info,channel_info,class_info";
                $sql .= $this->_GetWhere($this->_typenames[$a]);
                $sql .= " UNION ALL ";
            }
            $sql = substr($sql, 0, strlen($sql) - strlen(" UNION ALL ")); //left()
            $sql = "SELECT SUM(cc) AS c FROM ($sql) AS tc";
        }
        return $sql;
    }

    public function GetSelect() {
        $sql = "";
        if ($this->_mode > 0) {
            $sql .= "SELECT channel_info.id AS channel_id";
            $sql .= ",channel_info.channel_name";
            $sql .= ",channel_info.channel_type";
            $sql .= ",class_info.class_name";
            $sql .= ",class_info.id AS class_id";
            $sql .= ",{$this->_typenames[$this->_mode]}_info.id AS content_id";
            $sql .= ",{$this->_typenames[$this->_mode]}_info.{$this->_typenames[$this->_mode]}_name AS content_name";
            $sql .= ",{$this->_typenames[$this->_mode]}_info.{$this->_typenames[$this->_mode]}_time AS content_time";
            $sql .= ",{$this->_typenames[$this->_mode]}_info.visitor_count";
            $sql .= $this->_extrafields;
            $sql .= " FROM {$this->_typenames[$this->_mode]}_info,channel_info,class_info";
            $sql .= $this->_GetWhere($this->_typenames[$this->_mode]);
            //$sql .= " WHERE {$this->_typenames[$this->_mode]}_info.parent_class=class_info.id";
            //$sql .= " AND class_info.parent_channel=channel_info.id";
            //if ($this->_classid>0 ) $sql .= " AND {$this->_typenames[$this->_mode]}_info.parent_class=" . $this->_classid;
            //if ($this->_channelid>0 ) $sql .= " AND class_info.parent_channel=" . $this->_channelid;
            //if ($this->_searchkey!="" ) $sql .= " AND content_name LIKE \"{$this->_searchkey}\"";
            if ($this->_contentid > 0)
                $sql .= " AND {$this->_typenames[$this->_mode]}_info.id=" . $this->_contentid;
        }else {
            for ($a = 1; $a <= 4; $a++) {
                $sql .= "SELECT channel_info.id AS channel_id";
                $sql .= ",channel_info.channel_name";
                $sql .= ",channel_info.channel_type";
                $sql .= ",class_info.class_name";
                $sql .= ",class_info.id AS class_id";
                $sql .= ",{$this->_typenames[$a]}_info.id AS content_id";
                $sql .= ",{$this->_typenames[$a]}_info.{$this->_typenames[$a]}_name AS content_name";
                $sql .= ",{$this->_typenames[$a]}_info.{$this->_typenames[$a]}_time AS content_time";
                $sql .= ",{$this->_typenames[$a]}_info.visitor_count";
                $sql .= " FROM {$this->_typenames[$a]}_info,channel_info,class_info";
                $sql .= $this->_GetWhere($this->_typenames[$a]);
                //$sql .= " WHERE {$this->_typenames[$a]}_info.parent_class=class_info.id";
                //$sql .= " AND class_info.parent_channel=channel_info.id";
                //if ($this->_classid>0) $sql .= " AND {$this->_typenames[$a]}_info.parent_class=" . $this->_classid;
                //if ($this->_channelid>0) $sql .= " AND class_info.parent_channel=" . $this->_channelid;
                //if ($this->_searchkey!="") $sql .= " AND content_name LIKE \"{$this->_searchkey}\"";
                $sql .= " UNION ALL ";
            }
            $sql = substr($sql, 0, strlen($sql) - strlen(" UNION ALL ")); //left()
        }

        switch ($this->_order) {
            case 1:
                if ($this->_mode > 0) {
                    $sql .= " ORDER BY {$this->_typenames[$this->_mode]}_info.{$this->_typenames[$this->_mode]}_time DESC";
                } else {
                    $sql .= " ORDER BY content_time DESC";
                }
                break;
            case 2:
                $sql .= " ORDER BY visitor_count DESC";
                break;
            case 3:
                $sql .= " ORDER BY content_name ASC";
                break;
        }
//echo "<p>$sql</p>";
        return $sql;
    }

}
