<?php

class Object {
		/*
		**Allows a more Smalltalk like syntax
		** self::construct("Object")->whatever();
		** It is also a good idea to return $this
		** when you have nothing else to return 
		** like in getter and seter methods
		** this more easly allows you to chain 
		** method calls
		** $object->setFoo("foo")->setBar("bar")
		*/
		static function construct($class = "",$arg = ""){
			if(!is_string($class)){
				throw new WHException("Can only construct a class from a string");
			}
			if(!class_exists($class)){
				throw new WHException("Class $class does not exist");
			}
			if($arg != ""){
				$object = new $class($arg);
			}else{
				$object = new $class();
			}
			return $object;
		}
		
		/*
		**This does not work
		** __CLASS__ returns Object and there is no 
		** way to discover the class this is being called from
		*/
		static function init(){
			return self::construct(__CLASS__);
		}
		
		function __construct(){
			/*Do Nothing */
		}
		
		/*
		** Set an instance var
		*/
		public function setIvarNamed($name,$value){
			$this->$name = $value;
			return $this;
		}
		
		/*
		** Get an instance var
		*/
		public function getIvarNamed($name){
			return $this->$name;
		}
		
		public function hasIvar($name){
			return property_exists($this,$name);
		}
		
		public function hasMethod($name){
			return method_exists($this,$name);
		}
		
		public function subclassResponsibility ($methodName){
			throw new WHException("Subclass " . __CLASS__ . 
					" should Impliment $methodName");			
		}
		
		public function getByKeyPath($aStringKeyPath){
			return $this->$aStringKeyPath();
		}
		public function setByKeyPath($aStringKeyPath,$value){
			$methodName = "set".ucfirst($aStringKeyPath);
			$this->$methodName($value);
		}
		
		public function yourself(){
			return $this;
		}
		
		public function perform($aMethodName,$arguments){
		 	return call_user_func_array(array($this,$aMethodName),$arguments);
		}
		
		/**
		 * Returns an array of a string of subclass names 
		 * that are a subclass of this class
		*/
		public function subClasses(){
			$result = array();
			foreach(get_declared_classes() as $className){
				if(is_subclass_of(Object::construct($className),get_class($this))){
					$result[] = $className;
				}
			}
		}
		
		public function error($errorMessage = "", $errorNumber = 0){
			throw new WHException($errorMessage,$errorNumber);
		}
		
		/**
		 * Checks if this object is aClassName or a subClass
		 */
		public function kindOf($aClassName){
			return is_a($aClassName);
		}
		
		public function __toString(){
			return "An instance of ".get_class($this);
		}
		public function getClass(){
			return get_class($this);
		}
		static function unCamelCase($aString){
			$i++;
			$aString = ucfirst($aString);
			$newString[0] = $aString{0};
			while($aString{$i} != NULL){
				if(ctype_upper($aString{$i})){
					$newString[] = " ";
				}
				$newString[] = $aString{$i};
				$i++;
			}	
			
			return implode("",$newString);		
		}
		
		static public function removeFromArray($val, &$arr){
	          $array_remval = $arr;
	          for($x=0;$x<count($array_remval);$x++)
	          {
	              $i=array_search($val,$array_remval,true);
	              if (is_numeric($i)) {
	                  $array_temp  = array_slice($array_remval, 0, $i );
	                $array_temp2 = array_slice($array_remval, $i+1, count($array_remval)-1 );
	                $array_remval = array_merge($array_temp, $array_temp2);
	              }
	          }
	          return $array_remval;
	    }
	
		static public function charIsWhiteSpace($aChar){
			switch ($aChar){
				case ' ':
				case '\t':
				case '\n':
				case '\r':
					return TRUE;
				default:
					return FALSE;
				
			}
		}
		
		static public function arrayWithRange($start,$end){
			$array = array();
			while($start <= $end){
				$array[] = $start;
				++$start;
			}
			return $array;
		}
		
}
