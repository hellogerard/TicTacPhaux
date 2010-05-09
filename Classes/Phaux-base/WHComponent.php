<?php

class WHComponent extends Object {
	
	/**
	 * A dialog is a subinstance of WHComponent that replaces
	 * the view for this component. 
	 */
	private $dialog = NULL; 
	
	/**
	 * dialogCallback is the callback that a dialog component
	 * will answer on
	 */
	private $dialogCallback = NULL;
	
	/**
	 * 	parentComponent is the parent componet of this dialog 
	 *	if it is a dialog
	 */
	private $parentComponent = NULL;
	
	
	/*
	** this function should return an array
	** of instances of objects that this Component
	** needs to render
	*/
	public function children (){
		return array();
	}
	
	public function session (){
		global $app;
		return $_SESSION[$app]['session'];
	}
	
	final function renderOn($html){
		return $this->thisOrDialog()->renderContentOn($html);
	}
	
	public function renderContentOn($html){
		$this->subclassResponsibility("renderContentOn");
		return $this;
	}
	
	public function callDialog($aComponent){
		$this->dialog = $aComponent;
		$this->dialog->setParentComponent($this);
		return $this;
	}
	
	public function onAnswerCallback($object,$method,$arguments = ""){
	
		if($arguments == ""){
			$arguments = array();
		}
	

		$this->dialogCallback = Object::construct("WHCallback")->
										setObject($object)->
										setMethod($method)->
										setArguments($arguments);
										
		return $this;
	}
	
	public function answer($aValue){
		if($this->dialogCallback){
			
			$this->dialogCallback->runWithArgument($aValue);
		
		}
		$this->restoreParentComponent();
		return $this;
	}
	
	public function restoreParentComponent(){
		$this->parentComponent->restoreSelf();
	}
	
	public function restoreSelf(){
		$this->dialog = NULL;
		$this->dialogCallback = NULL;
	}
	
	public function thisOrDialog(){
		if($this->dialog){
			return $this->dialog;
		}
		return $this;
	}
	
	public function parentComponent(){
		return $this->parentComponet;
	}
	
	public function setParentComponent($aComponent){
		$this->parentComponent = $aComponent;
		return $this;
	}
	
	/*
	** Any CSS that you want to be included on the page
	** when this component is rendered
	*/
	public function style(){
		return '';
	}
	
	public function styleOfThisAndChildren(){
		$return = $this->thisOrDialog()->style();
		foreach($this->thisOrDialog()->children() as $child){
			$return .= $child->thisOrDialog()->styleOfThisAndChildren();
		}
		return $return;
	}
	
	public function updateRoot($anHtmlRoot){
		if($anHtmlRoot->title() == ""){
			$anHtmlRoot->setTitle("RECall");
		}
	}


	final function updateRootWithChildren($anHtmlRoot){
		$this->thisOrDialog()->updateRoot($anHtmlRoot);
		foreach($this->thisOrDialog()->children() as $child){
			$child->thisOrDialog()->updateRootWithChildren($anHtmlRoot);
		}
		return $this;
	}

	public function script(){
		return "";
	}
	
	public function styleLink(){
		if($this->style() != ""){
			return "<link type=\"text/css\" ".
				"href=\"".$this->session()->configuration()->scriptName().
				"?_sfc=".get_class($this)."&_type=style".
				"&app=".$this->session()->appName()."\" rel=\"stylesheet\" /> ";
		}
	}
	
	public function scriptLink(){
		if($this->script() != ""){
			return "<script type=\"text/javascript\" ".
				"src=\"".$this->session()->configuration()->scriptName().
				"?_sfc=".get_class($this)."&_type=script\"" .
				"&app=".$this->session()->appName()."\"/> ";
		}
	}
	
	public function scriptOfThisAndChildren(){
		$return = $this->thisOrDialog()->script();
		foreach($this->thisOrDialog()->children() as $child){
			$return .= $child->thisOrDialog()->scriptOfThisAndChildren();
		}
		
		return $return;
	}
}