<?php

class WHNavigationTest extends WHComponent {
	protected $navigation;
	
	public function __construct(){
		$this->navigation = Object::construct("WHNavigation");
		$this->navigation->
				addWithLabel(Object::construct("WHCounter"),"Counter")->
				addWithLabel(Object::construct("WHFormTest"),"Form Test")->
				addWithLabel(Object::construct("WHMultiCounter"),"Multiple Counter")->
				addWithLabel(Object::construct("WHRegisteredCounter"),"Registered Counter")->
				addWithLabel(Object::construct("WHDialogTest"),"Dialog test")->
				addWithLabel(Object::construct("WHLiveTest"),"AJAX Test")->
				addWithLabel(Object::construct("WHExceptionTest"),"Exception Text");
				
		return $this;
	}
	
	public function renderContentOn($html){
		return $html->render($this->navigation);
	}
	
	public function children(){
		return array($this->navigation);
	}
	
	
	public function updateRoot($anHtmlRoot){
		$anHtmlRoot->setTitle("Navigation Test");
	}
}