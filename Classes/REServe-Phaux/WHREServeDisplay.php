<?php

class WHREServeDisplay extends WHComponent {
	protected $reservable;
	
	public function setReserveable($anObject){
		$this->reserveable = $anObject;
		return $this;
	}
	public function reserveable(){
		return $this->reserveable;
	}
	
	/*
	** REServe fields that you would like to render
	** Returns an array of keyPaths
	*/
	public function keyPathsToRender(){
		$ra = array();
		if($this->reserveable != NULL){
			foreach($this->reserveable->tableDefinition()->columns() as $column){
				if($column->keyPath() != 'oid'){
					$ra[] = $column->keyPath();
				}
			}
			return $ra;
		}else{
			return array();
		}
		
	}
	
	/*
	** Key paths that you don't want the user to edit
	*/
	public function keyPathsReadOnly(){
		return array("oid");
	}
	
	/*
	**Should edit
	*/
	public function shouldEditKeyPath($keyPath){
		$ar = $this->keyPathsReadOnly();
		return (!in_array($kayPath,$ar));
	}
	
	/*
	** An array of names for the keyPath
	** This would most likely be used for labels
	** returns an array that is index by the keyPaths
	*/	
	public function namesByKeyPath(){
		$ra = array();
		foreach($this->reserveable->tableDefinition()->columns() as $column){
			$ra[$column->keyPath()] = Object::unCamelCase($column->keyPath());
		}
		return $ra;
	}
	
	public function nameForKeyPath($aKeyPath){
		$array = $this->namesByKeyPath();
		return $array[$aKeyPath];
		
	}
	
	
}