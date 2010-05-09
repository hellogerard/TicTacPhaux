<?php

class REServeAnySubclass extends REServe {
	public function fromSqlValueStringWithConnection($aString,$reServeConnection){
		if($aString == NULL){
			return NULL;
		}
		return Object::construct("REServeProxyObject")->
						setDatabase($reServeConnection)->
						setOid((int)$aString);
	}
}