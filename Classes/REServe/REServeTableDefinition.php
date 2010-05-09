<?php

class REServeTableDefinition extends REServe {
	protected $columns = array();
	protected $typeClass;
	
	
	public function __construct(){
		parent::__construct();
	
	}
	
	public function columns(){
		return $this->columns;
	}
	public function setColumns($aCollection){
		$this->columns = $aCollection;
		return $this;
	}
	public function addColumn($aColumn){
		$this->columns[] = $aColumn;
	}
	
	/*PHP Traits ? */ 
	public function reServeIn($reServeDriver){
		/*We don't want automatic table creation for this class so override*/
		$reServeDriver->reServeObject($this);
		return $this;
	}
	
	public function typeClass(){
		return $this->typeClass;
	}
	public function setTypeClass($aClass){
		$this->typeClass = $aClass;
		return $this;
	}
	
	public function columnNamed($aName){
		foreach($this->columns as $column){
			if($column->name() == $aName){
				return $column;
			}
		}
		throw WHException("No such column named $aName");
	}
	
	public function column($aKeyPath,$aClass,$aRowName = "",$shouldUpdateValue = TRUE){
		if($aRowName == ""){
			$aRowName = $aKeyPath;
		}
		if(!is_object($aClass)){
			$aClass = Object::construct($aClass);
		}
		$this->columns[] = Object::construct('REServeColumnDefinition')->
								setName($aRowName)->
								setKeyPath($aKeyPath)->
								setType($aClass)->
								setShouldUpdateValue($shouldUpdateValue);
								
		return $this;
	}
	
	
	public function tableDefinition (){
		return parent::tableDefinition()->
					column("typeClass",'REString')->
					column('columns',REArray::of('REServeColumnDefinition'));
	}
}