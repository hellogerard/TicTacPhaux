<?php

class WHListTag extends WHCollectionTag {
	protected $tag = "ul";
	
	
	public function tag(){
		return $this->tag;
	}

	public function beOrdered (){
		$this->tag = "ol";
		return $this;
	}
	
	public function beUnordered(){
		$this->tag = "ul";
		return $this;
	}

	public function contents(){
		if($this->contents == ""){
			return $this->collectionContents();
		}
		return parent::contents();
	}

	public function collectionContents(){
		
		$return = "";
		foreach($this->items as $position => $value){
			$listItem = $this->htmlCanvas()->listItem();
			if($value == $this->selectedItem()){
				$listItem->class("option-selected");
			}else{
				$listItem->class("option");
			}
			$return .= $listItem->with($value);
		}
	
		return $return;
	}

}