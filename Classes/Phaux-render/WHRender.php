<?php

class WHRender extends Object {
	protected $currentBrush;
	protected $parentBrush;
	protected $painter;
	
	public function flush(){
		if($this->currentBrush){
			$this->currentBrush->close();
			unset($this->currentBrush);
			$this->currentBrush = null;
		}
	}
	
}