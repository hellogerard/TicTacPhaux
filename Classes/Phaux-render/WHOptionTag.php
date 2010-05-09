<?php

class WHOptionTag extends WHTag {

	public function tag(){
		return "option";
	}

	public function value($aString){
		$this->setAttribute("value",$aString);
		return $this;
	}
	
	public function selected(){
		$this->setAttribute("selected","");
	}
}