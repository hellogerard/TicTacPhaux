<?php

class WHCounter extends WHComponent {
	protected $counter;
	
	public function __construct(){
		$this->counter = 1;
	}
	
	public function counter(){
		return $this->counter;
	}
	public function setCounter($aNumber){
		$this->counter = $aNumber;
		return $this;
	}
	
	public function add(){
		$this->counter++;
	}
	
	public function subtract(){
		$this->counter--;
	}
	
	public function renderContentOn($html){

		return $html->div()->id("counter")->with(
			$html->headingLevel(1)->with(sprintf("%s",$this->counter)).
			$html->anchor()->callback($this,"add")->with("++").
			$html->text(" ").
			$html->anchor()->callback($this,"subtract")->with("--")
		);
	
	}
}
