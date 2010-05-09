<?php

/**
 * We don't want to extend any class here
 * This is a proxy class it will foward most of the
 * methods on to the intended recever
 */

class REServeProxyObject {
	protected $oid;
	protected $objectClass;
	protected $database;
	protected $object;
	
	public function flush(){
		$this->object = NULL;
		return $this;
	}
	
	public function isInMemory(){
		if($this->object == NULL){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function isDirty(){
		if($this->isInMemory()){
			return $this->object->isDirty();
		}else{
			return FALSE;
		}
	}
	
	public function isReserveProxy (){
		return TRUE;
	}
	
	public function setOid($anInteger){
		$this->oid = $anInteger;
		return $this;
	}
	public function oid(){
		return $this->oid;
	}
	
	public function setObjectClass($aClassString){
		$this->objectClass = $aClassString;
		return $this; 
	}
	
	public function objectClass(){
		return $this->objectClass;
	}
	
	public function database(){
		return $this->database;
	}
	public function setDatabase($db){
		$this->database = $db;
		return $this;
	}
	
	public function object(){
		return $this->object;
	}
	public function setObject($anObject){
		$this->object = $anObject;
		return $this;
	}
	
	public function __call($method,$args){
		if($this->database() == NULL){
			throw new WHException("Woope $method");
		}
		if($this->object == NULL){
			if($this->objectClass == NULL){
				
				$this->object = $this->database()->objectForOid($this->oid());
				$this->objectClass = get_class($this->object());
			}else{
				$this->object = $this->database()->
									objectForOidWithClass($this->oid(),$this->objectClass());
			}
		}
		$result = $this->object()->perform($method,$args);
		if($result === $this->object){
			return $this;
		}else{
			return $result;
		}
	}
	
	
	public function __toString(){
		return "REServable:".get_class($this->object)."(".$this->oid.")";
	}
}