<?php

//--Database---------------------------------
define('DB3_DB_Host', 'localhost:3306');
define('DB3_DB_User', 'root');
define('DB3_DB_Pwd', '');
define('DB3_DB_Name', 'digitalbox3_dev');
define('DB3_DB_Driver', 'mysqli');
//--Prefix-----------------------------------
//如果在您的服务器上使用了多个DigitalBox 3.x，则应使用不同的dbPrefix
//一般可默认为“DB3”
define("DB3_Prefix", "DB3");

define('IBC1_DEFAULT_LANGUAGE', 'zh-cn');

define('IBC1_ENCODING', 'UTF-8');
define('IBC1_PREFIX', 'ibc1');

//define('IBC1_SYSTEM_ROOT', 'C:/Users/guzhiji/wamp/www/DigitalBox_3/src/'); //slash at the end
//define('IBC1_SYSTEM_ROOT', '/var/www/DigitalBox_3/src/'); //slash at the end
//$GLOBALS['IBC1_FRAMEWORK_CACHING'] = array('BoxCacheProvider', 'modules');

define('IBC1_TIME_ZONE', 'shanghai');
define('IBC1_TIME_P_TIME', 'H:i:s');
define('IBC1_TIME_P_DATE', 'Y-m-d');
define('IBC1_TIME_P_DATETIME', 'Y-m-d H:i:s');

$GLOBALS['IBC1_HTMLFILTER_CONFIG'] = array(
    array(
        'a' => array('href', 'target', 'title'),
        'img' => array('src', 'border', 'title', 'alt', 'width', 'height'),
        'table' => array('border', 'width', 'height'),
        'tr' => array(),
        'td' => array('width', 'height'),
        'th' => array('width', 'height'),
        'br' => array(),
        'p' => array(),
        'b' => array(),
        'strong' => array(),
        'i' => array(),
        'em' => array(),
        'font' => array('face', 'color', 'size'),
        'h1' => array(),
        'h2' => array(),
        'h3' => array(),
        'h4' => array(),
        'h5' => array(),
        'h6' => array()
    ),
    array(
        array(
            'src',
            'href'
        ),
        array(
            'http',
            'https',
            'ftp',
            'mailto'
        )
    )
);

define('DB3_SERVICE_CATALOG', 'catalogtest');
define('DB3_SERVICE_USER', 'usertest');
define('DB3_SERVICE_ARTICLE', 'articletest');
define('DB3_SERVICE_PHOTO', 'phototest');
define('DB3_SERVICE_COMMENT', 'commenttest');

$GLOBALS['IBC1_DATASERVICES'] = array(
    DB3_SERVICE_CATALOG => array(
        'type' => 'catalog',
        'host' => DB3_DB_Host,
        'user' => DB3_DB_User,
        'pwd' => DB3_DB_Pwd,
        'dbname' => DB3_DB_Name,
        'driver' => DB3_DB_Driver
    ),
    DB3_SERVICE_USER => array(
        'type' => 'user',
        'host' => DB3_DB_Host,
        'user' => DB3_DB_User,
        'pwd' => DB3_DB_Pwd,
        'dbname' => DB3_DB_Name,
        'driver' => DB3_DB_Driver,
        'extra' => array(
            'user_levels' => array(
                'level 1',
                'level 2',
                'level 3'
            ),
            'initial_user' => array(
                'uid' => 'guzhiji',
                'pwd' => 'guzhiji'
            )
        )
    ),
    DB3_SERVICE_ARTICLE => array(
        'type' => 'keyvalue',
        'host' => DB3_DB_Host,
        'user' => DB3_DB_User,
        'pwd' => DB3_DB_Pwd,
        'dbname' => DB3_DB_Name,
        'driver' => DB3_DB_Driver,
        'extra' => array(
            'binding_type' => 0, //optional,IBC1_DATATYPE_INTEGER
            'binding_length' => 10, //optional
            'value_type' => 3, //IBC1_DATATYPE_RICHTEXT
            'value_length' => 0,
            'time_included' => FALSE//optional, FALSE by default
        )
    ),
    DB3_SERVICE_PHOTO => array(
        'type' => 'keyvalue',
        'host' => DB3_DB_Host,
        'user' => DB3_DB_User,
        'pwd' => DB3_DB_Pwd,
        'dbname' => DB3_DB_Name,
        'driver' => DB3_DB_Driver,
        'extra' => array(
            'binding_type' => 0, //optional,IBC1_DATATYPE_INTEGER
            'binding_length' => 10, //optional
            'value_type' => 2, //IBC1_DATATYPE_PLAINTEXT
            'value_length' => 1023,
            'time_included' => FALSE//optional, FALSE by default
        )
    ),
    DB3_SERVICE_COMMENT => array(
        'type' => 'keyvalue',
        'host' => DB3_DB_Host,
        'user' => DB3_DB_User,
        'pwd' => DB3_DB_Pwd,
        'dbname' => DB3_DB_Name,
        'driver' => DB3_DB_Driver,
        'extra' => array(
            'binding_type' => 0, //optional,IBC1_DATATYPE_INTEGER
            'binding_length' => 10, //optional
            'value_type' => 2, //IBC1_DATATYPE_PLAINTEXT
            'value_length' => 1023,
            'time_included' => TRUE//optional, FALSE by default
        )
    )
);
