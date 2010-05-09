<?php

class REServeObjectId extends REInteger {
	public function reServeType(){
		return "oid";
	}
	public function shouldEdit(){
		return FALSE;
	}
}