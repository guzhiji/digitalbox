<?php

/**
 * provider of all database connections
 * @version 0.8.20111205
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.sql
 */
class DBConnProvider {

    /**
     * find the required database connector module,
     * and then open a connection to the database
     * 
     * @param string $host
     * If database is running on a special port,
     * add to the end of this string with a colon separated.
     * e.g. $obj->OpenDB("localhost:10000","root","","mydb");
     * @param string $user
     * @param string $pwd
     * @param string $db
     * @param string $dbdriver
     * Currently, only "mysqli" is fully supported and it is the default value.
     * @return DBConn 
     */
    public function OpenConn($host, $user, $pwd, $db, $dbdriver = IBC1_DEFAULT_DBDRIVER) {
        //establish a connection according to database software and connectors
        LoadIBC1Class("CacheProvider", "cache");
        $cp = new CacheProvider(dirname(__FILE__));
        $r = $cp->GetReader("plugin_db");
        $conn = $r->GetValue($dbdriver);
        if (is_string($conn)) {
            LoadIBC1Class("DBConn", 'sql');
            call_user_func("LoadIBC1Class", $conn, "sql.{$dbdriver}");
            return new $conn($host, $user, $pwd, $db);
        } else {
            throw new Exception("cannot find the db connector module: {$dbdriver}");
        }
    }

}
