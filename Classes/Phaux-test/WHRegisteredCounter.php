<?php

class WHRegisteredCounter extends WHCounter {
	function __construct(){
		parent::__construct();
		/*
		**Registers the iVar counter to follow 
		** the users back button
		*/
		$this->session()->registerObjectOnKeypath($this,"counter");
	}
}