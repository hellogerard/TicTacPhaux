<?php

class WHExceptionTest extends WHComponent {
	
	public function throwException(){
		throw new WHException("Generic excption");
	}
	
	public function triggerError(){
		trigger_error("Generic error",E_USER_ERROR);
	}
	public function parseError(){
		include("BogusFile.php");
	}
	public function methodOnNonObject(){
		$foo->foobar();
	}
	public function includeError(){
		include("thisFileDoesNotExist.php");
	}
	public function renderContentOn($html){
		return $html->anchor()->callback($this,"throwException")->with("Throw Exception").
				$html->br().
				$html->anchor()->callback($this,"triggerError")->with("Trigger Error").
				$html->br().
				$html->anchor()->callback($this,"parseError")
					->with("Trigger Parse Error (I can't catch this)").
				$html->br().
				$html->anchor()->callback($this,"methodOnNonObject")
						->with("Method on non-object").
				$html->br().
				$html->anchor()->callback($this,"includeError")
								->with("Include Error");
	}
	
	
}