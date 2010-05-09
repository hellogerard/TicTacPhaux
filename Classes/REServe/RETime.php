<?php

class RETime extends WHTime {
	public function reServeType(){
		return "time";
	}
	
	public function asSqlValueStringFor($aThing){
		if($aThing == NULL){
			return NULL;
		}
		return '"'.$aThing->hour().':'.$aThing->minute().':'.$aThing->second().'"';
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
	
	public function needsReServeConnection (){
		return FALSE;
	}
	
}