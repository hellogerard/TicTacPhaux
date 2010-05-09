<?php

class WHGenericTag extends WHTag {
	protected $tag;
	
	public function tag(){
		return $this->tag;
	} 
	
	public function setTag($aString){
		$this->tag = $aString;
		return $this;
	}
	
	
}