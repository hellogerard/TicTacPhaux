<?php

class WHFormTag extends WHTag {
	
	function __construct(){
		global $app;
		$this->setAttribute("action",
						$_SESSION[$app]['session']->configuration()->baseUrl().
							"?app=".$app.
							"&SID=".session_id().
							"&_r=".$_SESSION[$app]['session']->currentRegistryKey());
		$this->setAttribute("method","POST");
	}
	
	public function tag(){
		return "form";
	}
	
	
}