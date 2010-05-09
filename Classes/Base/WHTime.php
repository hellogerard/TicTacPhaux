<?php

class WHTime extends Object {
	protected $hour;
	protected $minute;
	protected $second;
	
	public function __construct(){
		$now = time();
		$this->hour = (int)date("G",$now);
		$this->minute = (int)date("i",$now);
		$this->second = (int)date("s",$now);
		return $this;
	}
	
	public function hour(){
		return $this->hour;
	}
	public function setHour($anInteger){
		if($anInteger < 0 || $anInteger > 24){
			throw new WHException("$anHour is not a valid hour");
		}
		$this->hour = $anInteger;
		return $this;
	}
	
	public function minute(){
		return $this->minute;
	}
	public function setMinute($anInteger){
		if($anInteger > 59 || $anInteger < 0){
			throw new WHException("$anIntger is not valid minute");
		}
		$this->minute = $anInteger;
		return $this;
	}
	
	public function second(){
		return $this->second;
	}
	public function setSecond($anInteger){
		if($anInteger > 59 || $anInteger < 0){
			throw new WHException("$anInteger is not a valid second");
		}
		$this->second = $anInteger;
		return $this;
	}
	
	public function fromSqlValueString($aString){
		$parts = explode(':',$aString);
		$this->setHour($parts[0]);
		$this->setMinute($parts[1]);
		$this->setSecond($parts[2]);
		return $this;
	}
	
	
	public function asSqlValueString(){
		return sprintf("%02d:%02d:%02d",$this->hour,$this->minute,$this->second);
	}
	
	public function __toString(){
		return $this->asSqlValueString();
	}
	
}