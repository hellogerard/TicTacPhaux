<?php

class WHDateAndTime extends WHMultipleInheritance {
	protected $classes = array("WHDate","WHTime");
	
	
	
	
	public function fromSqlValueString($aString){
		$parts = explode(' ',$aString);
		$fparts = $parts[0];
		$lparts = $parts[1];
		$fparts = explode("-",$fparts);
		$lparts = explode(":",$lparts);
		
		
		$date = $this->thisForClass("WHDate");
		$date->setYear($fparts[0]);
		$date->setMonth($fparts[1]);
		$date->setDay($fparts[2]);
		
		$time = $this->thisForClass("WHTime");
		$time->setHour($lparts[0]);
		$time->setMinte($lparts[1]);
		$time->setSecond($lparts[2]);
		return $this;
	}
	
	
	protected function asSqlValueString(){
		return $this->thisForClass("WHDate")->asSqlValueString() 
				." ".
				$this->thisForClass("WHTime")->asSqlValueString();
	}
	
	
	
	public function __toString(){
		return $this->asSqlValueString();
	}
}