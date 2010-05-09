<?php

class WHRadioButtonTag extends WHFormInputTag {
	public function type(){
		return "radio";
	}
	
	public function checked(){
		$this->setAttribute("checked","true");
	}
}