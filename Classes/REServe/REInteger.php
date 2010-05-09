<?php

class REInteger extends REServeBasicType {
	public function asSqlValueStringFor($aThing){
		return (string)intval($aThing);
	}
	public function fromSqlValueString($aString){
		return intval($aString);
	}
	public function reServeType(){
		return "integer";
	}
}