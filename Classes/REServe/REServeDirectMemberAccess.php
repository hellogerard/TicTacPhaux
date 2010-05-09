<?php

class REServeDirectMemberAccess extends REServe {
	
	public function getValueForKeyPath($aKeyPath){
		return $this->getIvarNamed($aKeyPath);
	}
	
	public function putValueForKeyPath($aValue,$aKeyPath){
		$this->setIvarNamed($aKeyPath,$aValue);
	}
}