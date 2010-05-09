<?php

class WHScriptTag extends WHTag{
	public function tag(){
		return 'script';
	}
	
	public function with($contents){
		$this->setAttribute("type","text/javascript");
		$this->contents = '/*<![CDATA[/* */'.$contents.'// ]]> ';
		return $this;
	}
}