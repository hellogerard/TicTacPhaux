<?php

class REServeBasicType extends Object {
	
	public function needsReServeConnection (){
		return FALSE;
	}
	
	public function asSqlValueStringFor($aThing){
		//$this->error("\"$aThing\"");
		return "\"$aThing\"";
	}
	
	public function fromSqlValueString($aString){
		return $aString;
	}
	
	public function isCollectionModel(){
		return FALSE;
	}
	
	public function isBasic(){
		return TRUE;
	}
	
	public function reServeValueStoredWithObject(){
		return TRUE;
	}
	
	public function shouldEdit(){
		return TRUE;
	}
	
}