<?php 

class REArray extends REServeBasicType {
	protected $valueType;
	protected $tableName;
	protected $dbConnection;
	protected $parentObject;
	protected $currentKey;
	protected $currentValue;
	
	
	public static function of($aType){
		return self::construct(__CLASS__)->setValueType($aType);
	}
	
	public function valueType(){
		return $this->valueType;
	}
	
	public function setValueType($aType){
		$this->valueType = $aType;
		return $this;
	}
	
	public function createTableWithDbConnection ($dbConnection){

		if(!is_object($dbConnection)){
			throw new WHException("dbConnection needs to be a reserve connection");
		}
		
		$this->setupActionFromConnection($dbConnection);
		$dbConnection->createTableForObject($this);
		$dbConnection->currentObject($this->parentObject);
		return $this;
	}
	
	public function setTableNameFromConnection($dbConnection){
		if(!is_object($dbConnection->currentObject()) || 
				!is_object($dbConnection->currentColumn())){
			throw new WHException("Unable to aquire something from the driver");
		}
		$this->tableName = $dbConnection->currentObject()->tableName().
								"_".$dbConnection->currentColumn()->name();
		return $this;
	}
	
	public function tableName(){
		return $this->tableName;
	}
	
	public function reServeValueStoredWithObject(){
		return FALSE;
	}
	
	public function setupActionFromConnection($dbConnection){
		if(!is_object($dbConnection)){
			throw new WHException("dbConnection needs to be a reserve connection");
		}
	
		$this->parentObject = $dbConnection->currentObject();
		
		$this->setTableNameFromConnection($dbConnection);
		$this->dbConnection = $dbConnection;
		
		return $this;
	}
	
	/*
	** We return nothing but save the array
	*/
	public function asSqlValueStringWithConnectionFor ($newArray,$dbConnection){

		$this->setupActionFromConnection($dbConnection);
		$dbConnection->setCurrentObject($this);
		$dbConnection->currentColumn($this->tableDefinition()->columnNamed("value"));
		
		$oldArray = $dbConnection->getFromCache($this->tableName().$this->parentObject->oid());
		if($oldArray == NULL){
			$oldArray = array();
		}
		$toAdd = array_diff_assoc($newArray,$oldArray);
		$toRemove = array_diff_assoc($oldArray,$newArray);
		foreach($toAdd as $var => $value){
			$this->currentKey = $var;
			$this->currentValue = $value;
			$this->insertWithDb($dbConnection);
		}
		foreach($toRemove as $var => $value){
			$this->currentKey = $var;
			$this->currentValue = $value;
			$this->deleteWithDb($dbConnection);
		}
		$dbConnection->putInCacheAtKey($this->tableName().$this->parentObject->oid(),$newArray);
		$oldArray = $dbConnection->getFromCache($this->tableName().$this->parentObject->oid());
		
		$dbConnection->setCurrentObject($this->parentObject);	
		return $this;
		
	}
	
	public function insertWithDb ($dbConnection){
		$dbConnection->insertObject($this);
		return $this;
	}
	public function deleteWithDb($dbConnection){
		$dbConnection->executeQuery(
						$dbConnection->queryForDeleteObjectWithWhereClause(
							$this,$this->whereClause($dbConnection)
						)
					);
		return $this;
	}
	
	/*
	** This is a little bit of sql outside the reserve driver
	** most likely not the best of ideas
	*/
	public function whereClause($dbConnection){
		return $dbConnection->escapedColumnName('parentId').'='.$this->parentObject->oid().
				' AND '.
				$dbConnection->escapedColumnName('key'). '='."'".$this->key()."'";
	}
	
	
	public function fromSqlValueStringWithConnection($aString,$dbConnection){
		$this->setupActionFromConnection($dbConnection);
		$dbConnection->setCurrentObject($this);
		$dbConnection->setCurrentColumn($this->tableDefinition()->columnNamed("value"));
		$newArray = array();
		foreach($dbConnection->collectionWithOid($this,$this->parentObject->oid()) as $i){
			$newArray[$i['key']] = 
						$this->valueFromType($this->valueType,
											"value",
											$i,
											$dbConnection);
		}
		
		$dbConnection->putInCacheAtKey($this->tableName().$this->parentObject->oid(),$newArray);
		return $newArray;
	}
	
	public function valueFromType($aType,$aKeyPath,$flatObject,$dbConnection){
		$aType = Object::construct($aType);
		if($aType->needsReserveConnection()){
			$value = $this->newFrom($aKeyPath)->
							fromSqlValueStringWithConnection($flatObject[$aKeyPath],$dbConnection);
		}else{
			$value = $this->newFrom($aKeyPath)->
							fromSqlValueString($flatObject[$aKeyPath]);
			
		}
		return $value;
	}
	
	public function newFrom($aKeyPath){
		return Object::construct($this->valueType());
	}
	
	public static function reServeType(){
		return "boolean";
	}

	public function needsReServeConnection(){
		return TRUE;
	}
	public function isCollectionModel(){
		return TRUE;
	}
	
	public function tableDefinition(){
		return Object::construct("REServeTableDefinition")->
				column("oid","REServeCollectionId",'objectId',FALSE)->
				column('parentId',REInteger)->
				column('key',REInteger)->
				column('value',$this->valueType());
	}
	
	public function key(){
		return $this->currentKey;
	}
	public function value(){
		return $this->currentValue;
	}
	public function parentId(){
		return $this->parentObject->oid();
	}
	
	/*
	** We may have nothing to save but
	** we need to tell the reserve driver that
	** we are dirty because we need to be given
	** a change to change our selves
	*/	
	public function isDirty(){
		return TRUE;
	}
	
	
	public function parentObject(){
		return $this->parentObject;
	}
	public function currentKey(){
		return $this->currentKey;
	}
	public function currentValue(){
		return $this->currentValue;
	}
	public function oid(){
		return $this->oid;
	}
	
	public function getValueForKeyPath($aKeyPath){
		switch($aKeyPath){
			case 'key':
				return $this->currentKey();
			case 'value':
				return $this->currentValue();
			case 'oid':
				return $this->oid();
			case 'parentId':
				return $this->parentObject()->oid();
			default:
				return FALSE;			
			
		}
	}
	
	public function isDirtyFromValue($newArray){
		/*The old version of the array is stored in cache*/
		$this->oldArray = $this->dbConnection->getFromCache($this->tableName().$
												$this->parentObject->oid());
												
		$toAdd = array_diff_assoc($newArray,$oldArray);
		$toRemove = array_diff_assoc($oldArray,$newArray);
		
		return $this;
	}
	
}