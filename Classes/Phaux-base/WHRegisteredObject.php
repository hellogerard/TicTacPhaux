<?php

class WHRegisteredObject extends Object {
	protected $onObject;
	protected $keyPath;
	protected $value;
	
	
	public function onObject(){
		$this->onObject;
	}
	public function setOnObject($anObject){
		$this->onObject = $anObject;
		return $this;
	}
	public function keyPath(){
		return $this->keyPath;
	}
	public function setKeyPath($aString){
		$this->keyPath = $aString;
		return $this;
	}
	public function value(){
		return $this->value;
	}
	public function setValue($aValue){
		$this->value = $aValue;
		return $this;
	}
	
	public function restoreState(){
		$this->onObject->setByKeyPath($this->keyPath,$this->value);
		return $this;
	}
	
	
	function __clone(){
		$this->value = $this->onObject->getByKeyPath($this->keyPath);
	}
	
	
}