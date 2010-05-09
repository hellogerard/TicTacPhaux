<?php

class WHREServeList extends WHREServeDisplay{

	protected $reObjects; // An array of objects
	protected $currentObject;
	
	protected $selectCallback = NULL;
	
	protected $removeCallback = NULL;
	
	
	public function setCurrentObject($anObject){
		$this->currentObject = $anObject;
		if($this->selectCallback != NULL ){
			$this->selectCallback->runWithArgument($anObject);
		}
		return $this;
	}
	
	public function removeObject($anObject){
		if($this->removeCallback != NULL){
			$this->callDialog(Object::construct("WHYesNoDialog")->
									setMessage("Are you sure you want to delete
											$anObject. This operation is unreversable!")->
									onAnswerCallback($this,"removeObjectCheck",array($anObject)));
		}
	}
	
	public function removeObjectCheck($aBool,$anObject){
		if($aBool){
			$this->removeCallback->runWithArgument($anObject);
		}
	}
	public function currentObject(){
		return $this->currentObject;
	}
	
	public function setReObjects($array){
		
		$this->reserveable = $array[0];
		$this->reObjects = $array;
		return $this;
	}
	
	public function callbackForSelect($object,$method){
		$this->selectCallback = Object::construct("WHCallback")->
								setObject($object)->
								setMethod($method);
	}
	public function callbackForRemove($object,$method){
		$this->removeCallback = Object::construct("WHCallback")->
								setObject($object)->
								setMethod($method);
	}
	
	
	public function renderContentOn ($html){
		return $html->table()->with(
				$html->tableRow()->with(
						$this->renderTableHeadOn($html).
						$this->renderTableBodyOn($html)
					)
			);
	}
	
	public function renderTableHeadOn($html){
		foreach($this->keyPathsToRender() as $keyPath){
			$return .= $html->tableHeading()->with($this->nameForKeyPath($keyPath));
		}
		$return .= $html->tableHeading()->with(" ");
		return $return;
	}
	
	public function renderTableBodyOn($html){
		foreach($this->objectsToRender() as $object){
			$return .= $this->renderTableRowOn($html,$object);
		}
		return $return;
	}
	
	public function renderTableRowOn($html,$object){
		if($this->currentObject === $object){
			$css = "highlight";
		}else{
			$css = "normal";
		}
		
		foreach($this->keyPathsToRender() as $keyPath){
			$rowContents .= $html->tableData()->with($object->getValueForKeyPath($keyPath));
		}		
		if($this->removeCallback != NULL){
			$removeLink = " | ".$html->anchor()->
							callback($this,"removeObject",array($object))->
								with($this->removeLabel());
		}
		
		
		$rowContents .= $html->tableData()->with(
			$html->anchor()->
					callback($this,"setCurrentObject",array($object))->
					with($this->selectLabel()).$removeLink);
	
		return $html->tableRow()->class($css)->with(
						$rowContents
						);
		
	}
	
	public function selectLabel(){
		return "select";
	}
	public function removeLabel(){
		return "remove";
	}
	
	public function objectsToRender(){
		return $this->reObjects;
	}
	
}