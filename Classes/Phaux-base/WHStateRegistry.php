<?php

class WHStateRegistry extends Object {
	protected $registeredObjects = array();
	
	public function registerObjectOnKeyPath($anObject,$aStringKeyPath){
		$this->registeredObjects[] = Object::construct("WHRegisteredObject")->
					setOnObject($anObject)->setKeyPath($aStringKeyPath)->
					setValue($anObject->getByKeyPath($aStringKeyPath));
		return $this;
	}
	
	public function restoreState(){
	
		foreach($this->registeredObjects as $var => $object){
			$object->restoreState();
		}
	}
	
	public function saveState (){
		foreach($this->registeredObjects as $var => $object){
			$this->registeredObjects[$var] = clone $object;
		}
	}
	
	function __clone(){
		$this->saveState();
	}
	
}