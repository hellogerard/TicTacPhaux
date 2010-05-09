<?php

class REServeColumnDefinition extends REServe {
	protected $name;
	protected $keyPath;
	protected $type;
	protected $updateValue;
	
	public function __construct(){
		$this->updateValue = TRUE;
	}
	
	public function name(){
		return $this->name;
	}
	
	public function setName($aString){
		$this->name = $aString;
		return $this;
	}
	
	public function reServeIn($reServeDriver){
		/*We don't want automatic table creation for this class so override*/
		$reServeDriver->reServeObject($this);
		return $this;
	}
	
	public function shouldUpdateValue(){
		return $this->updateValue;
	}
	
	public function setShouldUpdateValue($aBool){
		$this->updateValue = $aBool;
		return $this;
	}
	
	public function keyPath(){
		return $this->keyPath;
	}
	public function setKeyPath($aStringKeyPath){
		$this->keyPath = $aStringKeyPath;
		return $this;
	}
	
	public function type(){
		return $this->type;
	}
	public function setType($aType){
		$this->type = $aType;
		return $this;
	}
	
	public function typeName(){
		return get_class($this->type);
	}
	
	public function valueStored(){
		return $this->type()->reServeValueStoredWithObject();
	}
	
	public function tableDefinition(){
		return parent::tableDefinition()->
				column('name',"REString")->
				column('keyPath','REString')->
				column('shouldUpdateValue','REBoolean');
	}
}
