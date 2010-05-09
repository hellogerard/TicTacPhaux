<?php

/*
**Realy simple query stuff
** This needs to be rethought to do more 
** complex queries as well as joins 
** maybe a SQL like parser. 
** I would perfer to do it all with php and not
** introduce the programmer to yet another query 
** lauguage but it might be more simplistic. 
*/

class REQuery extends Object {
	protected $dbConnection;
	protected $buffer;
	protected $object;
	
	
	public function dbConntection(){
		return $this->dbConnection;
	}
	public function setDbConnection($aReserveConnection){
		$this->dbConnection = $aReserveConnection;
		return $this;
	}
	
	public function setObject($anObject){
		$this->object = $anObject;
		return $this;
	}
	
	public function setBuffer($aString){
		$this->buffer = $aString;
		return $this;
	}
	public function buffer(){
		return $this->buffer;
	}
	
	public function tables(){
		return $this->object->tableName();
	}
	
	public function newInstance(){
		return new get_class($this);
	}
	
	public function where(){
		$this->buffer = " WHERE  ";
		return $this;
	}
	
	public function orWhere(){
		$this->buffer .= " OR ";
		return $this;
	}
	
	public function andWhere(){
		$this->buffer .= "  AND  ";
		return $this;
	}
	
	public function orderBy($keyPath){
		$this->buffer .= " ORDER BY ".$this->escapedValueForKeyNamed($keyPath);
		return $this;
	}
	
	public function escapedValueForKeyNamed($aKeyName){
		
		foreach($this->object->tableDefinition()->columns() as $column){
			if($column->keyPath() == $aKeyName){
				return $this->escapedValueForColumn($column);
			}
		}
		throw new WHException("No column with that key name");
	}
	
	public function escapedValueForColumn($aColumn){
		return $this->dbConnection->escapedColumnName($aColumn->name());
	}
	
	public function columnFromKeyNamed($aKeyName){
		//var_dump($this->object->tableDefinition()->columns());
		foreach($this->object->tableDefinition()->columns() as $column){
			if($column->keyPath() == $aKeyName){
				return $column;
			}
		}
		throw new WHException("No column with that key name");		
	}
	
	public function keyNameOperatorValue($keyName,$operator,$value){
		$column = $this->columnFromKeyNamed($keyName);
		if($column->type()->needsReServeConnection()){
			$value = $column->
						type()->
						asSqlValueStringWithConnectionFor($value,$this);
		}else{
				$value= $column->type()->asSqlValueStringFor($value);
		}
		$this->buffer .= $this->escapedValueForKeyNamed($keyName).
							" $operator ".$value;
		return $this;
	}
	
	public function keyNameIs($aKeyName,$value){
		$this->keyNameOperatorValue($aKeyName,"=",$value);
		return $this;
	}
	
	public function keyNameIsNot($aKeyName,$value){
		$this->keyNameOperatorValue($aKeyName,"<>",$value);
		return $this;
	}
	
	public function keyNameLesser($aKeyName,$value){
		$this->keyNameOperatorValue($aKeyName,"<",$value);
		return $this;
	}
	
	public function keyNameGreater($aKeyName,$value){
		$this->keyNameOperatorValue($aKeyName,">",$value);
		return $this;
	}
	
	public function keyNameIsNull($aKeyName,$value){
		$this->buffer .= $this->escapedValueForColumn($aKeyName)." IS NULL ";
		return $this;
	}
	public function keyNameIsNotNull($aKeyName,$value){
		$this->buffer .= $this->escapedValueForColumn($aKeyName)." IS NOT NULL ";
		return $this;
	}
	
	public function keyNameStartsWith($aKeyName,$value){
		$this->keyNameOperatorValue($aKeyName," LIKE ",$value."%");
		return $this;
	}
	
	public function results(){
		return $this->dbConnection->resultsFromQueryWithClass(
				$this->__toString(),get_class($this->object)
			);
	}
	
	
	public function __toString(){
		return $this->dbConnection->queryFromQuery($this);
	}
	
	

}