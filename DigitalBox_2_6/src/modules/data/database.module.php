<?php
/*
 ------------------------------------------------------------------
 Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
 modules/database.module.php
 ------------------------------------------------------------------
 */
function db_connect(){
	return mysql_connect(dbMySQLHostName,dbMySQLUserName,dbMySQLPassword);
}
function db_close($connid){
	return @mysql_close($connid);
}
function db_query($connid, $sql, $args=NULL){

	if($args===NULL || count($args)==0){

		//sql debug
		//echo $sql.";";

		return mysql_db_query(dbDatabaseName,$sql,$connid);
	}else{
		$args_[0]=&$sql;
		$c=count($args);
		for($a=0;$a<$c;$a++){
			$args_[$a+1]=&$args[$a];
			if(is_string($args_[$a+1])) $args_[$a+1]=mysql_real_escape_string($args_[$a+1]);
		}

		//sql debug
		//echo call_user_func_array("sprintf",$args_).";";

		return mysql_db_query(dbDatabaseName,call_user_func_array("sprintf",$args_),$connid);
	}

}
function &db_result($result){
	$list=array();
	while($row = mysql_fetch_array($result)) {
		$list[]=$row;
	}
	return $list;
}

function db_free($result){
	@mysql_result($result);
}
?>
