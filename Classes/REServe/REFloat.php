<?php

class REFloat extends REServeBasicType {
	public function asSqlValueStringFor($aThing){
		return floatval($aThing);
	}
	public function fromSqlValueString($aString){
		return floatval($aString);
	}
	public static function reServeType(){
		return "float";
	}
}