<?php

abstract class WHCollectionTag extends WHTag {
	protected $items = array();
	protected $selectedItem;
	
	
	public function addItem($anItem){
		$this->items[] = $anItem;
		return $this;
	}

	public function items(){
		return $this->items;
	}
	
	public function setItems($anArray){
		$this->items = $anArray;
		return $this;		
	}


	public function selectedItem (){
		return $this->selectedItem;
	}
	
	public function setSelectedItem($anItem){
		$this->selectedItem = $anItem;
		return $this;
	}
	
}