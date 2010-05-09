<?php

abstract class REServeDriver extends Object {
	protected $localCache;
	protected $currentObject;
	protected $currentColumn;
	protected $automaticTableCreation;
	protected $TableCache;
	public static $ERROR_NO_SUCH_TABLE = 6660;
	
	public function __construct(){
		$this->localCache = array();
		$this->automaticTableCreation = FALSE;
	}
	
	public function addClass($aClassString){
		$nc = Object::construct($aClassString);
		$this->createTableForObject($nc);
		$nc->tableDefinition()->setTypeClass($nc->tableName())->reServeIn($this);
		return $this;
	}
	
	public function automaticTableCreation(){
		return $this->automaticTableCreation;
	}
	public function setAutomaticTableCreation($aBool){
		$this->automaticTableCreation = $aBool;
		return $this;
	}
	
	public function fieldsAndValues ($anObject){
		$this->setCurrentObject($anObject);
		foreach($anObject->tableDefinition()->columns() as $column){
			$this->setCurrentColumn($column);
			if($column->shouldUpdateValue()){

				$rawValue = $anObject->getValueForKeyPath($column->keyPath());
				
				$field = $this->escapedColumnName($column->name());
				if($column->type()->needsReServeConnection()){
					$value = $column->
										type()->
										asSqlValueStringWithConnectionFor($rawValue,$this);
			
				}else{
				
					$value= $column->type()->asSqlValueStringFor($rawValue);
				}
				if($column->type()->reServeValueStoredWithObject()){
					$values[$field]=$value;
				}
			}
		}
		return $values;
	}
	
	public function bodyOfInsertFor($anObject){
		$d = FALSE;
		$fieldsAndValues = $this->fieldsAndValues($anObject);
		foreach($fieldsAndValues as $field => $value){
			if($d){
				$fields .= " , ";
				$values .= " , ";
			}else{
				$d = TRUE;
			}
			
			$fields .= $field;
			$values .= $value;
		}
		
		return " ($fields) VALUES ($values)";
	}
	
	public function bodyOfUpdateFor($anObject){
		$d = FALSE;
		$fieldsAndValues = $this->fieldsAndValues($anObject);
		foreach($fieldsAndValues as $field => $value){
			if($d){
				$sql .= " , ";
			}else{
				$sql = "";
				$d = TRUE;
			}
			$sql .= "$field = $value";
		}
		return $sql;
	}
	
	
	public function boolean(){
		return "BOOLEAN";
	}
	
	public function clearAllCaches (){
		$this->clearCache();
		$this->setTableCache(NULL);
	}
	public function clearCache(){
		$this->localCache = array();
		return $this;
	}
	
	public function commit(){
		
		foreach($this->localCache as $object){
			/*We store arrays in here too */
			if(is_object($object)){
				
				if($object->isDirty()){
					
					$this->updateObject($object);
					
					$object->makeClean();
				}
			}
			
		}
		$this->commitDbTransaction();
		return $this;
	}
	
	public function commitAndStart(){
		$this->commit();
		$this->startTransaction();
		return $this;
	}
	
	public function commitDbTransaction(){
		$this->executeQuery($this->queryForCommit());
		return $this;
	}
	
	public function createObjectLookupTable(){
		$this->executeQuery($this->queryToCreateObjectIdTable());
		return $this;
	}
	public function createTableForClass($aClassString){
		$this->createTableForObject(Object::construct($aClassString));
		return $this;
	}
	public function createTableForObject($anObject){
		$sql = $this->queryToCreateTableWithObject($anObject);
		$this->executeQuery($sql);
			
		return $this;
	}
	
	public function currentColumn (){
		return $this->currentColumn;
	}
	public function setCurrentColumn($aColumn){
		$this->currentColumn = $aColumn;
		return $this;
	}
	
	public function currentObject(){
		return $this->currentObject;
	}
	public function setCurrentObject($anObject){
		$this->currentObject = $anObject;
	}
	
	public function date(){
		return "DATE";
	}
	
	public function dateAndTime(){
		return "DATETIME";
	}
	
	public function decimalIntegerSizeAndScals($m,$d){
		return "DECIMAL($m,$d)";
	}
	
	public function double(){
		return "DOUBLE";
	}
	
	public function escapedColumnName($aString){
		return "`$aString`";
	}
	
	public function float(){
		return "FLOAT";
	}
	
	public function flush(){
		foreach($this->localCache as $object){
			if(is_object($object)){
				$object->flush();
			}
		}
		return $this;
	}
	
	public function getFromCache ($anOid){
		$o = $this->localCache[$anOid];
		//For some strange reason PHP
		// treats Arrays like they are NULL in 
		// comparisons This is a BUG as 
		// far as I am concerned
		if(is_array($o)){
			return $o;
		}
		if(is_null($o)){
			return NULL;
		}
		if(is_object($o)){
			if($o->isInMemory()){
				return $o;
			}
		}
	}
	
	public function insertObject($anObject){
		$this->executeQuery($this->queryForInsertObject($anObject));
		return $this;
	}
	
	public function integer(){
		return "INTEGER";
	}
	
	public function largeInteger(){
		return "BIGINT";
	}
	
	public function localCache(){
		return $this->localCache;
	}
	
	public function mediumInteger(){
		return "MEDIUMINT";
	}
	
	public function objectForOid($anOid){
		if($this->getFromCache($anOid) != NULL){
			return $this->getFromCache($anOid);
		}else{
			$coid = $this->classForOid($anOid);
			if($coid != NULL){
				return $this->objectForOidWithClass($anOid,$coid);
			}else{
				throw new WHException("No such object stored");
			}
		}
	}
	

	public function objectForOidWithClassFromArray($anOid,$aClass,$flatObject){
		if($this->getFromCache($anOid) != NULL){
			return $this->getFromCache($anOid);
		}else{
			$newObject = Object::construct($aClass);
			$newObject->setOid($anOid);
			$this->putInCache($newObject);
			foreach($newObject->tableDefinition()->columns() as $column){
				$this->setCurrentObject($newObject);
				$this->setCurrentColumn($column);
				$rawValue = $flatObject[$column->keyPath()];
			
				if($column->type()->needsReServeConnection()){
					$value = $column->type()->
							fromSqlValueStringWithConnection($rawValue,$this);	
				}else{
					$value = $column->type()->fromSqlValueString($rawValue);
				}
				
				$newObject->putValueForKeyPath($value,$column->keyPath());
			}
			$newObject->makeClean();
			return $newObject;
		}
	}
	
	public function oid(){
		return $this->unsignedInteger(). ' PRIMARY KEY ';
	}
	
	public function oidColumn(){
		/*I believe this is the SQL standard but I don't think any SQL databases 
				follow it */
		return "INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENITY";
	}
	public function putInCacheAtKey($aKey,$anObject){
		$this->localCache[$aKey] = $anObject;
		return $this;
	}
	public function putInCache($anObject){
		if(!is_object($anObject)){
			throw new WHException("Only REServeable objects belong in the cache");
		}
		$g = $this->localCache[$anObject->oid()];
		if($g == NULL){
			if($anObject->isReserveProxy()){
				$o = $anObject;
			}else{
				$o = Object::construct("REServeProxyObject")->
								setDatabase($this)-> 
								setOid($anObject->oid())->
								setObjectClass($anObject->getClass())->
								setObject($anObject);
			}
		}else{
			$g->setObject($anObject);
			$o = $g;
		}
		$this->putInCacheAtKey($anObject->oid(),$o);
		return $o;
	}
	
	/*
	** Use the care
	** Does not check that this object is pointed to elsewhere
	** Will simply delete the object no questions asked
	*/
	
	public function deleteObject($anObject){
		$this->executeQuery($this->queryForDeleteObject($anObject));
	}
	
	public function queryForClassForOid($anOid){
		return "SELECT * FROM pxxObjectLookup WHERE ".$this->escapedColumnName('objectId').
					" = ".(string)$anOid;
	}
	
	public function queryForCommit(){
		return "COMMIT";
	}
	
	
	
	public function queryForDeleteObject($anObject){
		return $this->queryForDeleteObjectWithWhereClause($anObject,
				$this->escapedColumnName('objectId'). ' = '.$anObject->oid());
	}
	
	public function queryForDeleteObjectWithWhereClause($anObject,$whereClause){
		return "DELETE FROM ".$anObject->tableName()." WHERE ".$whereClause;
		
	}
	
	public function queryForInsertObject($anObject){
		return "INSERT INTO ".$anObject->tableName()." ".$this->bodyOfInsertFor($anObject);
	}
	
	public function queryForLookupClassWithOid($aClass,$anOid){
		return "SELECT * FROM ".Object::construct($aClass)->tableName()." WHERE ".
					$this->escapedColumnName('objectId'). ' = ' . (string)$anOid;
	}
	
	public function queryFromQuery($aREServeQuery){
		return "SELECT * FROM ".$aREServeQuery->tables(). 
							$aREServeQuery->buffer();
	}
	
	public function queryForLookupCollectionWithOid($reCollection,$anOid){
		return "SELECT * FROM ".$reCollection->tableName(). " WHERE ".
					$this->escapedColumnName('parentId'). ' = ' . (int)$anOid;
	}
	
	public function queryForNewObjectOfClass($aClass){
		return 'INSERT INTO pxxObjectLookup (type) VALUES (\''.
					Object::construct($aClass)->tableName().
					'\')';
	}
	
	public function oidForNewObject($anObject){
		$this->executeQuery($this->queryForNewObjectOfClass($anObject->getClass()));
		return $this->lastOidFromTable($anObject->tableName());
	}
	
	public function queryForRollback(){
		return 'ROLLBACK';
	}
	
	public function queryForRootLookup(){
		return 'SELECT * FROM pxxObjectLookup WHERE root = 1';
	}
	
	public function queryForSetFieldTo($aField,$aValue){
		return '`'.$aField.'`='.$aValue;
	}
	
	public function queryForStartTransaction(){
		return "START TRANSACTION";
	}
	
	public function queryForUpdateObject($anObject){
		return $this->queryForUpdateObjectWithWhereClause($anObject,
					$this->escapedColumnName('objectId').'='.$anObject->oid());
					
	}
	
	public function queryForUpdateObjectWithWhereClause($anObject,$whereClause){

		return 'UPDATE '.$anObject->tableName().' SET '.
					$this->bodyOfUpdateFor($anObject).
					' WHERE '.$whereClause;
	}
	
	public function queryToAddColumnToTableOfType($aColumn,$aTable,$aType){
		return 'ALTER TABLE '.$aTable.' ADD COLUMN '.$this->escapedColumnName($aColumn).
					' ' . $this->$aType();
	}
	
	
	public function queryToCreateTableWithObject($anObject){
		$d = FALSE;
		
		$this->setCurrentObject($anObject);
		
		$sql = "CREATE TABLE ".$anObject->tableName(). ' ( ';
		foreach($anObject->tableDefinition()->columns() as $column){
			$this->setCurrentColumn($column);
			if(!is_object($column->type())){
				//var_dump($column);
			}			
			if($column->type()->reServeValueStoredWithObject()){
				if($d){
					$sql .= ' , ';
				}else{
					$d = TRUE;
				}
				$type = $column->type()->reServeType();
				$sql .= $this->escapedColumnName($column->name()). ' '. 
						$this->$type(). ' ';
			}else{
				$column->type()->createTableWithDbConnection($this);
			}
		}
		$sql .= ' ) ';
		return $sql;
	}
	
	
	public function queryToDropColumnFromTable($aColumn,$aTable){
		return "ALTER TABLE ".$aTable." DROP COLUMN ".$this->escapedColumnName($aColumn);
	}
	
	public function queryToRemoveRootFlag(){
		return 'UPDATE pxxObjectLookup SET root = 0';
	}
	
	public function queryToSetRootFlagForOid($anOid){
		return 'UPDATE pxxObjectLookup SET root = 1 WHERE '.
					$this->escapedColumnName('objectId').
					' = \''.(string)$anOid.'\'';
	}
	
	public function queryFor($aClass){
		return Object::construct($this->queryClass())->
							setDbConnection($this)->
							setObject(Object::construct($aClass));
	}

	public function reServeObject($anObject){
		$this->setCurrentObject($anObject);
		$anObject->setOid($this->oidForNewObject($anObject));
		$this->insertObject($anObject);
		$anObject->makeClean();
		$this->putInCache(Object::construct("REServeProxyObject")->
							setDatabase($this)->
							setOid($anObject->oid())->
							setObjectClass($anObject->getClass())->
							setObject($anObject));
							
		return $this;
	}
	
	public function rollback(){
		foreach($this->localCache as $object){
			$object->rollback();
		}
		$this->rollbackDbTransaction();
		return $this;
	}
	
	public function rollbackDbTransaction(){
		$this->executeQuery($this->queryForRollback());
		return $this;
	}
	
	public function root(){
		$oid = $this->oidForRoot();
		if($oid == NULL){
			return NULL;
		}
		return $this->objectForOid($oid);
	}
	
	public function setRoot($anObject){
		if($anObject->oid() == NULL){
			$anObject->reServeIn($this);
		}
		$this->executeQuery($this->queryToRemoveRootFlag());
		$this->executeQuery($this->queryToSetRootFlagForOid($anObject->oid()));
		return $this;
	}
	
	public function setupDatabase(){
	
		$this->createObjectLookupTable();
		$this->createTableForClass("REServeTableDefinition");
		$this->createTableForClass("REServeColumnDefinition");
		return $this;
	}
	
	public function smallInteger(){
		return "SMALLINT";
	}
	
	public function startDbTransaction(){
		$this->executeQuery($this->queryForStartTransaction());
		return $this;
	}
	
	public function startTransaction(){
		$this->startDbTransaction();
		return $this;
	}
	
	public function string(){
		return $this->stringWithLength(254);
	}
	
	public function stringWithLength($anInteger){
		return "VARCHAR($anInteger)";
	}
	
	public function text(){
		return "TEXT";
	}
	
	public function time(){
		return "TIME";
	}
	
	public function unsignedInteger(){
		/*The SQL standard does not support unsigned Integer 
				imagine my suprise */
		return 'INTEGER ';
	}
	
	public function updateObject($anObject){
		$this->currentObject($anObject);
		try{
			$this->executeQuery($this->queryForUpdateObject($anObject));
		}catch (WHException $e){
			/*
			**WE might need up updat the table
			*/
			try{
				if($e->getCode() == 666){
					$this->updateTableForObject($anObject);
				}else{
					throw $e;
				}
			}catch(Exception $e){
				//Woops something else is wrong throw it
				throw $e;
			}
		}
		return $this;
	}
	
	public function updateTableForObject($anObject){
		
		//die(get_class($anObject));
		$ar = $this->queryFor("REServeTableDefinition")->where()->
				keyNameIs("typeClass",$anObject->getClass())->
				results();
		$s = $ar[0];
		if(count($s) == 0 ){
			throw new WHException("Can not update and object that is not managed");
		}
		//return FALSE;
		foreach($anObject->tableDefinition()->columns() as $column){
			foreach($s->columns() as $c){
				if($column->name() == $c->name()){
					
					continue 2;
				}
			}
			/*If we did not find this row add it*/
			if($column->type()->reServeValueStoredWithObject()){
				$this->executeQuery(
					$this->queryToAddColumnToTableOfType(
								$column->name(),$anObject->tableName(),
									$column->type()->reServeType()
							)
					);
				
			}else{
				$this->currentObject($anObject);
				$column->type()->createTableWithDbConnection($this);
			}
			//Add it to the database as well
			$s->addColumn($column);
			
		}
		
		foreach($s->columns() as $column){
			foreach($anObject->tableDefinition()->columns() as $c){
				if($c->name() == $column->name()){
					continue 2;
				}
			}
			$this->executeQuery(
				$this->queryToDropColumnFromTable($column->name(),$anObject->tableName()));
			$colArray = $s->columns();
			Object::removeFromArray($column,$colArray);
			$s->setColumns($colArray);
		}
		$this->commitAndStart();
	}
	
	public function tableCache(){
		return $this->tableCache;
	}
	public function setTableCache($aCache){
		$this->tableCache = $aCache;
		return $this;
	}
	
	public function isConnected(){
		if($this->connection == NULL){
			return FALSE;
		}else{
			return TRUE;
		}
	}
}