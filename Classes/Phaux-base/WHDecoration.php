<?php

/*
**Not yet fleshed out.

** Would like to do something like Seaside Decorations
** I was thinking that this could simply be a model that
** renders it's parent. 

** Needs to handle answers as well.
*/

abstract class WHDecoration extends WHComponent {
	protected $child;
	
	
	public function setChild($aComponet){
		$this->child = $aComponent;
		return $this;
	}
	public function child(){
		return $this->child;
	}
	
	public function children(){
		return array($child);
	}
	
	public function renderChildOn($htmml){
		return $html->render($this->child);
	}
	

	
}