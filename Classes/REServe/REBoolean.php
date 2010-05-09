<?php

class REBoolean extends REInteger {
	public function asSqlValueStringFor($aThing){
		return (boolean) $aThing;
	}
	public function fromSqlValueString($aString){
		return (boolean) $aString;
	}
	public function reServeType(){
		return "boolean";
	}
}