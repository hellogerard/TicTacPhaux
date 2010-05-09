<?php

class WHCallback extends Object {
	protected $key;
	protected $object; /*The object to call the method on*/
	protected $method; /*the method name */
	protected $arguments = array();

	public function run(){
		return $this->runWithArgs($this->arguments);
	}
	
	/*test
	/*
	** Inserts an argument at the beginning of the argument list
	*/
	public function runWithArgument($anArgument){
		$newArray = $this->arguments;
		array_unshift($newArray,$anArgument);
		return $this->runWithArgs($newArray);
	}
	
	public function runWithArgs($args){
		return call_user_func_array(array($this->object,$this->method),$args);
	}
	
	public function key(){
		return $this->key;
	}
	
	public function setKey($aString){
		$this->key = $aString;
		return $this;
	}
	
	public function object(){
		return $this->object;
	}
	
	public function setObject($anObject){
		$this->object = $anObject;
		return $this;
	}
	
	public function method(){
		return $this->method;
	}
	
	public function setMethod($aString){
		$this->method = $aString;
		return $this;
	}
	
	public function arguments(){
		return $this->arguments;
	}
	
	public function setArguments($anArray){
		$this->arguments = $anArray;
		return $this;
	}

}