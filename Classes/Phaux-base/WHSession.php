<?php

class WHSession extends Object {
	protected $mainComponent;
	protected $callbacks = array();
	protected $lastCallbackKey = 0;
	protected $appName;
	protected $registries = array(); 
	protected $currentRegistry;
	protected $currentKey;
	
	
	public function start(){
		$this->currentRegistry = Object::construct("WHStateRegistry");
		$this->registries[] = $this->currentRegistry;
		$this->currentKey = 0;
	}
	
	public function mainComponent($aComponent){
		$this->mainComponent = $aComponent;
		return $this;
	}
	
	
	public function registerCallback($object,$method,$arguments){

		$newKey = $this->newCallbackKey();
		$this->callbacks[$newKey] = Object::construct("WHCallback")->
										setKey($newKey)->
										setObject($object)->
										setMethod($method)->
										setArguments($arguments);
		return $this->callbacks[$newKey];
		
	}
	
	public function registerCollectionCallback($object,$method,$arguments,$collection){
		$newKey = $this->newCallbackKey();
		$this->callbacks[$newKey] = Object::construct("WHCollectionCallback")->
										setKey($newKey)->
										setObject($object)->
										setMethod($method)->
										setArguments($arguments)->
										setItems($collection);
										
		return $this->callbacks[$newKey];
		
	}

	
	/*
	**Register a value of an object to follow the users back button and
	** any new windows they create
	*/
	public function registerObjectOnKeyPath($anObject,$aStringKeyPath){
		$this->currentRegistry()->registerObjectOnKeyPath($anObject,$aStringKeyPath);
	}
	

	public function nextRegistryKey (){
		return count($this->registries);
	}
	
	public function currentRegistryKey(){
		return $this->currentKey;
	}
	public function currentRegistry(){
		return $this->currentRegistry;
	}
	
	public function maxRegistries (){
		return 200;
	}
	public function maxCallbacks(){
		return 1000;
	}
	
	public function appName(){
		return $this->appName;
	}
	public function setAppName($aString){
		return $this->appName = $aString;
	}

	public function newCallbackKey(){
		/*
		** Try to keep the callback size down
		*/
		if($this->maxCallbacks() <= count($this->callbacks)){
			unset($this->callbacks[count($this->callbacks)- $this->maxCallbacks()]);
		}
		
		return ++$this->lastCallbackKey;
	}
	
	public function callbackByKey($key){
		return $this->callbacks[$key];
	}
	
	public function configuration(){
		return $_SESSION[$this->appName]['configuration'];
	}
	
	public function sessionId(){
		return htmlspecialchars(session_id());
	}
	
	public function startSessionOnAppWithConfiguration($appName,$configuration){
		session_start();
		if($_SESSION[$appName]['configuration'] == NULL){
			$_SESSION[$appName]['configuration'] = $configuration;
		}
		if($_SESSION[$appName]["session"] == NULL){
			$_SESSION[$appName]["session"] = $this;
			$_SESSION[$appName]["session"]->setAppName($appName);
			$this->start();
		}
		return $this;
	}
	
	public function restoreRegistry($registryKey){
		if(is_object($this->registries[$registryKey])){
			$this->registries[$registryKey]->restoreState();
			$this->currentRegistry = $this->registries[$registryKey];
			$this->currentKey = $registryKey;
		}
		
	}
	
	public function save(){
		$this->saveCurrentRegistry();
		//var_dump($this->registries);
	}
	
	public function saveCurrentRegistry (){
		if($this->maxRegistries() <= count($this->registries)){
			unset($this->registeries[count($this->registries) - $this->maxRegistries()]);
		}
		$newReg = clone $this->currentRegistry();
		$this->registries[] = $newReg;
		$this->currentKey = count($this->registries) -1;
		$this->currentRegistry = $newReg;
		
		
	}
	
}