<?php

class WHMultiCounter extends WHComponent {
	protected $counter1;
	protected $counter2;
	protected $counter3;
	protected $counter4;
	
	function __construct(){
		$this->counter1 = Object::construct("WHCounter");
		$this->counter2 = Object::construct("WHCounter");
		$this->counter3 = Object::construct("WHCounter");
		$this->counter4 = Object::construct("WHCounter");
	}
	
	public function children(){
		return array($this->counter1,$this->counter2,$this->counter3,$this->counter4);
	}
	
	public function renderContentOn($html){
		return $html->content(
			$html->div()->class("counter")->with(
				$html->render($this->counter1)
			).
			$html->div()->class("counter")->with(
				$html->render($this->counter2)
			).
			$html->div()->class("counter")->with(
				$html->render($this->counter3)
			).
			$html->div()->class("counter")->with(
				$html->render($this->counter4)
			).
			$html->div()->clear());
	
	}
	
	public function style (){
		return "
			.counter {
				float:left;
				border:1px dashed black;
				width:100px;
				text-align:center;
				padding:3px;
				margin:3px;
			}
			";
	}
	
}