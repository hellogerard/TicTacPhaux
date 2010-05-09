<?php

class REServeTest extends REServe {
	protected $string;
	protected $integer;
	protected $boolean;
	protected $float;
	
	protected $date;
	protected $time;
	protected $dateAndTime;
	protected $array;
	
	public function string(){
		return $this->string;
	}
	public function setString($aString){
		$this->string = $aString;
		return $this;
	}
	
	public function integer(){
		return $this->integer;
	}
	public function setIteger($anInteger){
		$this->integer = $anInteger;
	}
	
	public function boolean(){
		return $this->boolean;
	}
	public function setBoolean($aBoolean){
		$this->boolean = $aBoolean;
		return $this;
	}
	
	public function tableDefinition (){
		return parent::tableDefinition()->
				column("string","REString")->
				column("integer","REInteger")->
				column("boolean","REBoolean")->
				column("float","REFloat");
	}
	
}