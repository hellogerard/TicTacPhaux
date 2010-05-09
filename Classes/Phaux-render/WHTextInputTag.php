<?php

class WHTextInputTag extends WHFormInputTag {
	public function type(){
		return 'text';
	}
	
	public function maxLengthAndSize($anInteger){
		$this->maxlength($anInteger)->
					size($anInteger);
		return $this;
	}
}