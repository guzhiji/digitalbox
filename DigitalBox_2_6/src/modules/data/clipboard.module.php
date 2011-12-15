<?php
/*
 ------------------------------------------------------------------
 Copyright 2011 DigitalBox Ver 2.6 (by GuZhiji Studio)
 modules/data/clipboard.module.php
 ------------------------------------------------------------------
 */

class ClipBoard{

	public function isEmpty(){
		if(isset($_SESSION["clipboard"])){
			if(count($_SESSION["clipboard"])>0) return FALSE;
		}
		return TRUE;
	}

	public function clear(){
		unset($_SESSION["clipboard"]);
	}

	public function isCut($name){
		if(isset($_SESSION["clipboard"])){
			if(isset($_SESSION["clipboard"][$name])){
				return TRUE;
			}
		}
		return FALSE;
	}

	public function cancel($name){
		if($this->isCut($name)){
			unset($_SESSION["clipboard"][$name]);
		}
	}

	public function cut($name,$value){
		if(isset($_SESSION["clipboard"])){
			$_SESSION["clipboard"][$name]=$value;
		}else{
			$_SESSION["clipboard"]=array($name=>$value);
		}
	}

	public function getValue($name){
		if($this->isCut($name)){
			$value=$_SESSION["clipboard"][$name];
			return $value;
		}
		return NULL;
	}

	public function paste($name){
		if($this->isCut($name)){
			$value=$_SESSION["clipboard"][$name];
			unset($_SESSION["clipboard"][$name]);
			return $value;
		}
		return NULL;
	}

}
?>