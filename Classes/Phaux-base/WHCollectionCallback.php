<?php

class WHCollectionCallback extends WHCallback {
	protected $items = array(); /*a group of items that can be 
								** Passed as an arg to the callback */
	
	public function runWithArgument($anItemKey){
		parent::runWithArgument($this->itemForKey($anItemKey));
		return $this;
	}
								
	public function items(){
		return $this->items;
	}
	
	public function setItems($anArray){
		$this->items = $anArray;
		return $this;
	}
	
	public function itemForKey($aKey){
		return $this->items[$aKey];
	}
	

}

