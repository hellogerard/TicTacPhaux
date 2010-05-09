<?php

class REServe extends Object {
	protected $oid;
	protected $lastVersion;
	
	public function __construct(){
		$this->makeClean();
	}
	
	public function asSqlValueStringFor($aThing){
		return "\"".$aThing->oid()."\"";
	}
	
	public function asSqlValueStringWithConnectionFor ($value, $aReserveConnection){
		if($value->oid() == NULL){
			$value->reServeIn($aReserveConnection);
		}
		return $value->asSqlValueStringFor($value);
	}
	
	public function getValueForKeyPath ($aKeyPath){
		return $this->getByKeyPath($aKeyPath);
	}
	
	public function isCollectionModel(){
		return FALSE;
	}
	
	/**
	 * Check for changes 
	 */
	public function isDirty(){
		foreach($this->tableDefinition()->columns() as $row){
			if($row->type()->isCollectionModel()){
				if($this->getValueForKeyPath($row->keyPath())){
					return TRUE;
				}
			}else{
				if(!($this->getValueForKeyPath($row->keyPath()) 
						=== $this->lastVersion()->getValueForKeyPath($row->keyPath()))){
					
					return TRUE;
							
				}
			}
		}
		return FALSE;
	}
	
	
	public function makeClean(){
		$this->lastVersion = clone $this;
		return $this;
	}
	public function lastVersion(){
		return $this->lastVersion;
	}
	
	public function needsReServeConnection (){
		return TRUE;
	}
	
	public function oid(){
		return $this->oid;
	}
	
	public function setOid($anInteger){
		$this->oid = $anInteger;
		return $this;
	}
	
	public function putValueForKeyPath($aValue,$aKeyPath){
		$this->setByKeyPath($aKeyPath,$aValue);
		return $this;
	}
	
	public function reServeId (){
		return  $this->oid();
	}
	
	public function reServeIn($aReserveDriver){
		try{
			$aReserveDriver->reServeObject($this);
		}catch(Exception $e){
			/*If it fails the table might need to be updated
			** update the table and try again
			** If the error code is not 666
			** the table does not need to be updated
			** another execption was thrown */
			try{
				if($e->getCode() == 666){
					$aReserveDriver->addClass(get_class($this));
				}else{
					throw $e;
				}
				$aReserveDriver->reServeObject($this);
				
			}catch(Exception $e){
			
				/*
				**Well we might need to add
				*/
				try{
					if($e->getCode() == 666){
						$aReserveDriver->addClass(get_class($this));
					}else{
						throw $e;
					}
				}catch(Exception $e){
					//Woops something else is wrong throw it
					throw $e;
				}
				$aReserveDriver->reServeObject($this);
			}	
			
		}
		return $this;
	}
	
	public function reServeType(){
		return "unsignedInteger";
	}
	
	public function reServeValueStoredWithObject(){
		return TRUE;
	}
	
	public function rollback(){
		$this->copyFrom($lastVersion);
		return $this;
	}
	
	public function tableDefinition(){
		return Object::construct("REServeTableDefinition")->
				column("oid","REServeObjectId","objectId");
	}
	
	public function tableName(){
		return get_class($this);
	}
	
	public function fromSqlValueStringWithConnection($aString,$reServeConnection){
		if($aString == NULL){
			return NULL;
		}
		$toReturn = Object::construct("REServeProxyObject")->
						setDatabase($reServeConnection)->
						setOid((int)$aString)->
						setObjectClass(get_class($this));
		$reServeConnection->putInCache($toReturn);
		return $toReturn;
	}
	
	public function isReserveProxy(){
		return FALSE;
	}
	
	public function columnForKeyPath($aKeyPath){
		foreach($this->tableDefinition()->columns() as $column){
			if($column->keyPath() == $aKeyPath){
				return $column;
			}
		}
		throw new WHException("No such keyPath defined!");
	}
	
	/*
	**This is an object not a string,int,array,etc
	*/
	public function isBasic(){
		return FALSE;
	}
	
	public function __toString(){
		return "REServable:".get_class($this)."(".$this->oid.")";
	}
}