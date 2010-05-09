<?php

class REDateAndTime extends WHDateAndTime {
	public function reServeType(){
		return "dateAndTime";
	}
	
	
	public function asSqlValueStringFor($aThing){
		if($aThing == NULL){
			return NULL;
		}
		return $aThing->asSqlValueString();
	}
	

	
	public function needsReServeConnection (){
		return FALSE;
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