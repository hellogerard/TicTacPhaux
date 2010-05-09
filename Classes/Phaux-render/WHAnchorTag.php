<?php

class WHAnchorTag extends WHTag {
	protected $positionKey;/*the #postion append to the end of a link */
	protected $liveUpdaterKey = NULL;
	protected $runCallbackWithLiveUpdate = FALSE;
	
	public function tag(){
		return 'a';
	}
	
	public function position(){
		return $this->positionKey;
	}
	public function setPosition($aString){
		$this->positionKey = $aString;
		return $this;
	}
	
	public function disableHref(){
		$this->setAttribute("href","javascript: void(0);");
		return $this;
	}
	
	/*
	** liveUpdateOn and liveUpdateWithCallbackOn
	** Links that call one of these two things 
	** should have an href but I don't know if I care
	** for they way I am doing it. It just feels hacky
	*/ 
	public function liveUpdateOn($jsEvent,$object,$function,$arguments = ""){
		$this->disableHref();
		return parent::liveUpdateOn($jsEvent,$object,$function,$arguments);
	}
	
	
	public function liveUpdateWithCallbackOn(	$jsEvent,
												$updateObject,
												$updateFunction,
												$updateArguments,
												$callbackObject,
												$callbackFunction,
												$callbackArguments){
		$this->disableHref();
		return parent::liveUpdateWithCallbackOn(	$jsEvent,
													$updateObject,
													$updateFunction,
													$updateArguments,
													$callbackObject,
													$callbackFunction,
													$callbackArguments);
	}
		
	
	
	public function callback($object,$function,$arguments = ""){
		global $app;
		$this->registerCallback($object,$function,$arguments);
		$url = $_SESSION[$app]['session']->configuration()->baseUrl().
			"/$app?SID=".$_SESSION[$app]['session']->sessionId()."&_k=".
			$this->callbackKey.
			"&_r=".$_SESSION[$app]['session']->currentRegistryKey();
		
		$this->setAttribute("href",$url);
		return $this;		
	}

	/*
	** Creats a live update on clicking of the anchor
	** Also allowing the callback to be run as well
	*/
	public function liveUpdate($object,$function,$arguments = ""){
		$this->registerLiveUpdateOn("onClick",$object,$function,$arguments);
		return $this;
	}

	
}