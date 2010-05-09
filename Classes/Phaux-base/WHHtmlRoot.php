<?php

class WHHtmlRoot extends Object {
	protected $title;
	protected $styles = array();
	protected $scripts = array();
	protected $pathExtra = "";
	protected $urlArgs = array();
	
	
	public function title(){
		return $this->title;
	}
	
	public function setTitle($aString){
		$this->title = $aString;
	}
	public function addToTitle($aString){
		$this->title .= $aString;
	}
	
	/**
	 * Pass a style sheet name like stannardPlacement.css
	 */
	public function needsStyle($styleSheetName){
		$this->styles[$styleSheetName] = TRUE;
	}
	
	public function needsScript($jsName){
		$this->scripts[$jsName] = TRUE;
	}
	
	public function addUrlArg($aKey,$aValue){
		$this->urlArgs[$aKey] = $aValue;
	}
	public function addToPath($aString){
		$this->pathExtra .= $aString;
	}
	
	public function getExtraUrl(){
		$return = $this->pathExtra."?";
		$d = FALSE;
		foreach($this->urlArgs as $var => $value){
			if($d){
				$return .= "&";
			}else{
				$d = TRUE;
			}
			$return .= $var."=".$value;
		}
		return $return;
	}
	
	
	/**
	 * REnders the contents of an html head on $html
	 */
	public function renderHeadContentsOn($html){
		global $app;
		$title = $html->title()->with($this->title());
		$baseUrl = $_SESSION[$app]['session']->configuration()->basePath();
		$styles = "";
		foreach($this->styles as $style => $t){
			if($t){
				$styles .= $html->link()->type("text/css")->href($baseUrl.$style)->rel("stylesheet");
			}
			
		}
		foreach($this->scripts as $script => $t){
			if($t){
				$styles .= $html->script()->type("text/javascript")->src($baseUrl.$script)->with("");
			}
		}
		return '<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />'.
					$title.$styles;			
				
	}
	
}