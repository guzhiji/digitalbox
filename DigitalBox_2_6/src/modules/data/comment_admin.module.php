<?php
/*
 ------------------------------------------------------------------
 Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
 modules/data/comment_admin.module.php
 ------------------------------------------------------------------
 */
class Comment_Admin{

	var $_connid,$_passport;

	function __construct($connid,Passport &$passport){
		$this->_connid=$connid;
		$this->_passport=&$passport;
	}

	public function check($function=NULL){
		$this->error = "";
		if(!$this->_passport->check()){
			$this->error.="权限不够;";
			return FALSE;
		}
		if($function===NULL) $function=strGet("function");
		switch (strtolower($function)){
			case "clear":
				$this->task="clear";
				break;
			case "delete":
				$this->task="delete";
				break;
			default:
				$this->task="";
				$this->error .= "任务调度错误;";
		}
		return ($this->error=="");
	}

	public function clear(){

		if($this->task=="clear" && $this->_passport->isMaster()){
			if(db_query($this->_connid,"DELETE FROM guest_info") &&
			db_query($this->_connid,"OPTIMIZE TABLE guest_info")){
				return TRUE;
			}else{
				$this->error.="清空失败;";
			}
		}else{
			$this->error.="权限不够;";
		}
		return FALSE;
	}

	public function delete(){
		$e=FALSE;
		if($this->task!="delete"){
			$e=TRUE;
		}else{
			$message_id = strPost("id");
			if($message_id!=""){
				if(!db_query($this->_connid,"delete from guest_info where id=%d",array($message_id))){
					$e=TRUE;
				}
			}else{
				$e=TRUE;
			}
		}
		if($e) $this->error .= "删除失败;";
		return !$e;
	}
}
?>