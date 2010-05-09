<?php
/*
** Impliments simple multiple Inheritance 
** Subclass this class and specify the parents 
** in $classes. 
** You must specify the classes before this classes
** constructor in called . 
** The best way to do that is in the classes
** varable definition
*/
class WHMultipleInheritance extends Object {
	/*
	** Might look like ...
	** protected $classes = array('Class1','Class2');
	*/
	protected $classes = array();
	
	
	protected $objects = array();
	
	public function __construct(){
		foreach($this->classes as $class){
			$objects[] = Object::construct($class);
		}
		return $this;
	}
	
	public function __call($methodName,$args){
		if($this->hasMethod($methodName)){
			return call_user_func_array(
									array($this->whoHasMethod($methodName),
									$methodName)
									,$args);
		}
		$this->error("No such method");
	}
	
	public function __get($name){
		return $this->getIvarNamed($name);
	}
	
	public function __set($name,$value){
		return $this->setIvarNamed($name,$value);
	}
	
	public function __isset($name){
		if($this->hasIvar($name)){
			return ($this->getIvarNamed($name) != NULL);
		}
		return FALSE;
	}
	public function __unset($name){
		if($this->hasIvar($name)){
			$this->setIvarNamed($name,NULL);
			return $this;
		}
		return FALSE;
	}
	/*
	
	
	** These need to be overwritten from the 
	** Object class to properly impliment 
	** multiple inheritance when a Subclass
	** of WHMultipleInheritance subclasses 
	** WHMultipleInheritance
	*/
	public function setIvarNamed($name,$value){
		if($this->hasIvar($name)){
			$object = $this->whoHasIvar($name);
			$object->setIvarNamed($name,$value);
			return $this;
		}
		$this->error("No such ivar");
	}

	public function getIvarNamed($name){
		if($this->hasIvar($name)){
			$object = $this->whoHasIvar($name);
			return $object->getIvarNamed($name);
		}
		$this->error("No such ivar");

	}
	
	
	public function whoHasIvar($name){
		if(parent::hasIvar($name)){
			return $this;
		}
		foreach($this->objects as $instance){
			if($instance->hasIvar($name)){
				return $instance;
			}
		}
	}
	
	public function whoHasMethod($name){
		if(parent::hasMethod($name)){
			return $this;
		}
		foreach($this->objects as $instance){
			if($instance->hasMethod($name)){
				return $instance;
			}
		}
	}
	
	public function hasIvar($name){
		if($this->whoHasIvar($name) === FALSE){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function hasMethod($name){
		if($this->whoHasMethod($name) === FALSE){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	/*
	** Gets the instance of the object 
	** that matches the className
	*/
	public function thisForClass($className){
		foreach($this->objects as $instance){
			if(get_class($instance) == $className){
				return $instance;
			}
		}
		$this->error(get_class($this)." does not subclass $className");
	}
}