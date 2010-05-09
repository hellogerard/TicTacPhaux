<?php

class WHDivTag extends WHTag {
	
	public function tag(){
		return "div";
	}
	
	public function clear(){
		return $this->style("clear:both")->
						with($this->htmlCanvas()->space());
		
	}
}