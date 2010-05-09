<?php

class WHInformDialog extends WHComponent {
	protected $message;
	
	public function message(){
		return $this->message;
	}
	
	public function setMessage($aString){
		$this->message = $aString;
		return $this;
	}
	
	public function divClass(){
		return "inform-dialog";
	}
	
	public function renderMessageOn($html){
		return $html->headingLevel("1")->with($this->message());
	}
	
	public function renderButtonsOn($html){
		
		return $html->form()->with(
			$html->submitButton()->callback($this,"answer",TRUE)->value("Okay")
			);
	}
	
	
	public function renderContentOn($html){
		return $html->div()->class($this->divClass)->with(
			$this->renderMessageOn($html).
			$this->renderButtonsOn($html)
		);
	}
}