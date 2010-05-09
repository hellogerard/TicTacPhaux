<?php

class WHYesNoDialog extends WHInformDialog {
	
	public function submitAnswer($stringValue,$boolValue){
		$this->answer($boolValue);
	}
	
	public function renderButtonsOn($html){
		return $html->form()->with(
			$html->submitButton()->value("Yes")->callback($this,"submitAnswer",array(TRUE)).
			$html->submitButton()->value("No")->callback($this,"submitAnswer",array(FALSE))
			);
	}
}