<?php

class WHLinkTag extends WHTag {
	protected $doesNotNeedClose = TRUE;
	
	public function tag(){
		return "link";
	}
}