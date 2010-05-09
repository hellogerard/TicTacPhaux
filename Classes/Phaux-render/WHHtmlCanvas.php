<?php

/*
** Modeled off of Seaside's (http://seaside.st/) WACanvas
*/

class WHHtmlCanvas extends WHCanvas {
	protected $baseTag;
	protected $docType;
	protected $mimeType;
	
	
	public function __construct(){
		$this->docType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '.
				'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$this->mimeType = "text/html";
				
	}
	
	public function setMimeType($aString){
		$this->mimeType = $aString;
	}
	public function mimeType(){
		return $this->mimeType;
	}
	
	public function docType(){
		return $this->docType;
	}
	public function setDocType($aString){
		$this->docType = $aString;
	}
	
	/*
	** Tags
	*/ 
	
	public function html(){
		$this->baseTag = $this->constructTagWithClass("WHGenericTag")->setTag("html");
		return $this->baseTag;
	}
	
	public function headingLevel($level){
		return $this->constructTagWithClass("WHGenericTag")->setTag("h".$level);
	}
	
	/*
	**Just return a generic tag for anything you don't 
	** understand
	*/
	function __call($method,$arguments){
		return $this->constructTagWithClass("WHGenericTag")->setTag($method);
	}
	
	public function anchor(){
		return $this->constructTagWithClass("WHAnchorTag");	
	}
	
	public function form(){
		return $this->constructTagWithClass("WHFormTag");
	}
	public function textInput(){
		return $this->constructTagWithClass("WHTextInputTag");
	}
	public function submitButton(){
		return $this->constructTagWithClass("WHSubmitButtonTag");
	}
	
	public function resetButton(){
		return $this->constructTagWithClass("WHResetButtonTag");
	}
	
	public function hiddenInput(){
		return $this->constructTagWithClass("WHHiddenInputTag");
	}

	public function table(){
		return $this->constructTagWithClass("WHTableTag");
	}

	public function tableData(){
		return $this->constructTagWithClass("WHTableDataTag");
	}
	public function tableRow(){
		return $this->constructTagWithClass("WHTableRowTag");
	}
	
	public function tableHeading (){
		return Object::construct("WHTableHeadingTag");
	}
	
	public function listItem (){
		return $this->constructTagWithClass("WHListItemTag");
	}
	
	public function orderedList(){
		return $this->constructTagWithClass("WHListTag")->beOrdered();
	}
	
	public function unorderedList(){
		return $this->constructTagWithClass("WHListTag")->beUnordered();
	}
	
	public function select(){
		return $this->constructTagWithClass("WHSelectTag");
	}
	
	public function option(){
		return $this->constructTagWithClass("WHOptionTag");
	}
	
	public function text($aString){
		return $this->constructTagWithClass("WHHtmlText")->with($aString);
	}
	
	public function div(){
		return $this->constructTagWithClass("WHDivTag");
	}
	
	public function content($content){
		return $this->constructTagWithClass("WHHtmlText")->with($content);
	}
	
	public function link(){
		return $this->constructTagWithClass("WHLinkTag");
	}
	
	public function space(){
		return $this->text("&nbsp;");
	}
	
	public function script(){
		return $this->constructTagWithClass("WHScriptTag");
	}
	
	public function render($component){
		$return = $this->constructTagWithClass("WHHtmlText")
				 		->with($component->renderOn($this));
		if(is_object($return)){
			return $return->__toString();
		}else{
			return $return;
		}
	}
	
	public function constructTagWithClass($aTagName){
		return Object::construct($aTagName)->setHtmlCanvas($this);
	}
	
	function __toString(){
		header("Content-type: ".$this->mimeType());
		return $this->docType.
				$this->baseTag->__toString();
	}
}
