<?php

class WHREServeRootModel extends REServe {
	protected $contacts = array();
	
	public function contacts(){
		return $this->contacts;
	}
	
	public function setContacts($anArray){
		$this->contacts = $anArray;
		return $this;
	}
	public function addContact(WHREServeContactModel $anObject){
		
		$this->contacts[] = $anObject;
	}
	public function removeContact($aContact){
		$this->contacts = Object::removeFromArray($aContact,$this->contacts);
	}
	
	
	public function tableDefinition(){
		return parent::tableDefinition()->
					column("contacts",REArray::of(WHREServeContactModel));
	}
}