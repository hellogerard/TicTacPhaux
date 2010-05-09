<?php

class WHLiveNavigation extends WHNavigation {
	protected $id;
	
	public function __construct(){
		global $WHLiveNavigation_ID;
		++$WHLiveNavigation_ID;
		$this->id = $WHLiveNavigation_ID;
		
	}

	public function renderSelectionOn($html){
		$script = "";
		foreach($this->labels() as $label){
			/*
			** Make all the labels unselected
			*/
			$labelName = str_replace(" ","-",$label);
			$script .= 'document.getElementById("WHLiveNavigation-'.$this->id.'-'.$labelName.'")';
			if($label == $this->labelForComponent($this->selection())){
				$script .= ".className = 'option option-selected';";
			}else{
				$script .= ".className = 'option';";
			}
		}

		
		
		return $html->script()->with($script).
				$html->div()->id("WHLiveNavigation-".$this->id)->with(
					$html->render($this->selection()));
					
		
	}
	
	public function renderOptionOn($html,$component){
		$li = parent::renderOptionOn($html,$component);
		$labelName = str_replace(" ","-",$this->labelForComponent($component));
		$li->id("WHLiveNavigation-".$this->id."-".$labelName);
		return $li;
	}
	
	public function renderComponentLabelOn($html,$component){
		$link = $html->anchor()->
						liveUpdateWithCallbackOn("onClick",
							$this,"renderSelectionOn",array(),
							$this,"setSelection",array($component))->
						with($this->labelForComponent($component));
	
		
	
		return $link;
						
	}
	
	
}