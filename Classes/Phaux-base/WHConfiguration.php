<?php

class WHConfiguration extends Object {
	protected $configValues; /*Stored as a multi deminsional array 
							 ** 1st deminsion is the category
							 ** 2nd deminsion is the key name
							  */
	protected $applicationName;
							
	public function serverName(){
		global $SERVER_NAME;
		return $SERVER_NAME;
	}
	
	public function scriptName(){
		return "recall.php";
	}
	
	public function setScriptName($aString){
		$this->scriptName = $aString;
		return $this;
	}
	
	public function appUrl(){
		global $app;
		 return $_SESSION[$app]['session']->configuration()->baseUrl().
				"/$app?SID=".$_SESSION[$app]['session']->sessionId();
	}
	
	public function baseUrl(){
		//die(var_dump($url));
		return $_SERVER['SCRIPT_NAME'];
		
	}
	public function basePath(){
	
		return "/".substr($this->baseUrl(),0,strpos($this->baseUrl(),$this->scriptName()));
	}
	
	public function debugMode(){
		return $this->configValueBySubjectAndKey('general','debug');
	}
	public function isDeployed(){
		return !$this->debugMode();
	}
	
	public function adminEmail(){
		return $this->configValueBySubjectAndKey('general','admin_email');
	}
	
	public function applicationName(){
		return $this->applicationName;
	}
	public function setApplicationName($aString){
		$this->applicationName = $aString;
		return $this;
	}
	
	public function setConfigValues($aMDArray){
		$this->configValues = $aMDArray;
		return $this;
	}
	
	public function configValueBySubjectAndKey($subject,$key){
		return $this->configValues[$subject][$key];
	}
	
	static function startUpOnAppWithIni($app,$app_configuration){
	

		ini_set("session.use_cookies",$app_configuration['general']['use_cookie']);
		ini_set("session.name","SID");

		$configuration_class = $app_configuration['general']['configuration_class'];
		$configuration = Object::construct($configuration_class);
		$configuration->setApplicationName($app)->
											setConfigValues($app_configuration);

		return $configuration;	
	}
}