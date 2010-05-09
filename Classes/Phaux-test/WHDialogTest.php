<?php

class WHDialogTest extends WHComponent {
	protected $yesNoAnswer = FALSE;

	
	public function showDialog(){
		$this->callDialog(Object::construct("WHInformDialog")->
								setMessage("This is an inform dialog."));
	}
	
	public function setYesNoAnswer($aBool){
		$this->yesNoAnswer = $aBool;
		return $this;
	}
	
	public function yesNoAnswer(){
		return $this->yesNoAnswer;
	}
	
	public function yesNoDialog(){
		$this->callDialog(Object::construct("WHYesNoDialog")->
								setMessage("This is a yes no dialog.")->
								onAnswerCallback($this,"setYesNoAnswer"));
	}
	
	public function renderContentOn($html){
		return $html->anchor()->
						callback($this,"showDialog")->
						with("Show dialog").
				$html->space().
				$html->anchor()->
						callback($this,"yesNoDialog")->
						with("Yes no dialog - ".$this->yesNoAnswer);
	}
}