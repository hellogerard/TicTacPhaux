<?php

class WHSelectTag extends WHCollectionTag {
	
	protected $labels = array();
	
	public function tag(){
		return "select";
	}
	
	public function labelOnKeyPath($aStringKeyPath){
		foreach($this->items as $position => $object){
			$value = $object->getValueForKeyPath($aStringKeyPath);
			if(is_object($value)){
				$value = $value->__toString();
			}			
			$this->labels[$object] = $value;
		}
		return $this;
	}
	
	/*
	**This must be run after all items have been added to the select
	** and before you call with()
	** You can not set the callback for a select before adding the items
	*/
	public function callback($object,$function,$args = ""){
		if(!is_array($args)){
			$args = array();
		}
		$this->registerCollectionCallback($object,$function,$args,$this->items);
		$this->setAttribute("name","_i[".$this->callbackKey."]");
		return $this;
	}
	
	/*
	** anArray should look like
	** "label"=>$item
	*/
	public function labelsAndItems($anArray){
		foreach($anArray as $label => $item){
			$this->addItem($item)->itemLabel($item,$label);			
		}
		return $this;
	}
	
	/*
	** anArray should look like
	** $item=>"label"
	*/
	public function itemsAndLabels($anArray){
		foreach($anArray as $item => $label){
			$this->addItem($item)->itemLabel($item,$label);			
		}
		return $this;		
	}
	
	
	public function labelForItem ($anItem){
		$label = $this->labels[$this->indexForItem($anItem)];
		if($label == NULL){
			return $anItem;
		}
		return $label;
	}
	
	public function indexForItem($anItem){
		foreach($this->items as $var => $value){
			if($anItem == $value){
				return $var;
			}
		}
		throw new WHException("No such item", 1);
	}
	
	public function itemLabel($anItem,$aString){
		$this->labels[$this->indexForItem($anItem)] = $aString;
		return $this;
	}
	
	public function labels(){
		return $this->labels;
	}
	
	public function setLabels($anArray){
		$this->labels = $anArray;
		return this;
	}
	
	
	public function contents(){
		$return = "";
		
		foreach($this->items as $position => $value){
			$option = $this->
						htmlCanvas()->
						option()->
						value($position)->
						with($this->labelForItem($value));
									
			if($value == $this->selectedItem()){
				$option->selected();
			}
			$return .= $option->__toString();
		}
		return $return;
	}
	
	
	
}