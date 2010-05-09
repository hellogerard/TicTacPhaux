<?php

class WHImageTag extends WHTag {
	public function tag(){
		return "img";
	}
	
	public function source($aValue){
		$this->src($aValue);
		return $this;
	}
}