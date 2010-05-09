<?php

/*
**This is the start of a generic way of
** editing reservable objects
** EVERYTHING is subject to RADICAL change
*/

class WHREServeModelEdit extends WHREServeDisplay {
	protected static $numberForDateSelector = 1;
	
	public function renderLabelOn($html,$keyPath){
		return $this->nameForKeyPath($keyPath); 
	}
	
	public function renderValueOn($html,$keyPath){
		if($this->shouldEditKeyPath($keyPath)){
			$checkMethod = "renderValue".ucfirst($keyPath)."On";
			if(method_exists($this,$checkMethod)){
				return $this->$methodName($html);
			}
			$column = $this->reserveable->columnForKeyPath($keyPath);
			$methodName = "renderValueType".$column->typeName()."On";
			if(!method_exists($this,$methodName)){
				$this->error(get_class($this)." does not yet handle ".$column->typeName().
							". You might want to subclass WHREServeModelEdit and 
							create a method named $checkMethod ");
			}else{
				return $this->$methodName($html,$column);
			}
		}
	}
	
	/*
	** This function could stand to be broken up a little bit
	** and made more reuseable
	** This function needs to check for valid dates.
	**  currently it does not
	*/
	public function renderValueTypeREDateOn($html,$column){
		$value = $this->reserveable->getValueForKeyPath($column->keyPath());
		if($value == NULL){
			$this->error("Your class must return a date on key path ".$column->keyPath());
		}
		self::$numberForDateSelector++;
		$month = $value->month();
		$day = $value->day();
		$year = $value->year();
		return $html->select()->
					id("WHREServeModelEdit-date-month".self::$numberForDateSelector)->
					itemsAndLabels(WHDate::months())->
					setSelectedItem($month)->
					callback($value,'setMonth').
				$html->text(" / ").
				$html->select()->
					id("WHREServeModelEdit-date-day".self::$numberForDateSelector)->
					setItems(Object::arrayWithRange(1,31))->
					setSelectedItem($day)->
					callback($value,'setDay').
				$html->space().
				$html->textInput()->
					id("WHREServeModelEdit-date-year".self::$numberForDateSelector)->
					value($year)->
					maxLengthAndSize(4)->
					callback($value,'setYear').
				$html->script()->with(
					"
					var cal".self::$numberForDateSelector." = new CalendarPopup();
					cal".self::$numberForDateSelector.".setReturnFunction('setMultipleValues".self::$numberForDateSelector."');
					cal".self::$numberForDateSelector.".showYearNavigation();
					//cal".self::$numberForDateSelector.".showNavigationDropdowns();
					cal".self::$numberForDateSelector.".showYearNavigationInput();
					function setMultipleValues".self::$numberForDateSelector."(y,m,d) {
						document.getElementById('WHREServeModelEdit-date-month".self::$numberForDateSelector."').selectedIndex=m-1;
						document.getElementById('WHREServeModelEdit-date-day".self::$numberForDateSelector."').selectedIndex=d-1;
						document.getElementById('WHREServeModelEdit-date-year".self::$numberForDateSelector."').value=y;
						
					}
					").
				$html->anchor()->
						disableHref()->
						id("WHREServeModelEdit-date-link".self::$numberForDateSelector)->
						onClick("cal".self::$numberForDateSelector.".showCalendar('WHREServeModelEdit-date-link".self::$numberForDateSelector."')")->
						with("select");
						
				
		
	}
	
	public function renderValueTypeRETimeOn($html,$column){
		$value = $this->reserveable->getValueForKeyPath($column->keyPath());
		return $html->textInput()->
					value($value->hour())->
					maxLengthAndSize(2)->
					callback($value,'setHour').
				$html->text(':').
				$html->textInput()->
					value($value->minute())->
					maxLengthAndSize(2)->
					callback($value,'setMinute').
				$html->text(':').
				$html->textInput()->
					value($value->second())->
					maxLengthAndSize(2)->
					callback($value,'setSecond');
				
	}
	
	public function renderValueTypeREStringOn($html,$column){
		return $html->
				textInput()->
				value(
					$this->reserveable->getValueForKeyPath($column->keyPath())
				)->callback(
					$this->reserveable,
					"putValueForKeyPath",
					array($column->keyPath())
				);
	}
	
	public function renderValueTypeREIntegerOn($html,$column){
		return $this->renderValueTypeREStringOn($html,$column);
	}
	

	
	public function renderRowOn($html,$keyPath){
		return 	$html->div()->class("row")->with(
					$html->span()->class("label")->with(
						$this->renderLabelOn($html,$keyPath)
					).
					$html->span()->class("value")->with(
						$this->renderValueOn($html,$keyPath)
					)
			);
	}
	
	public function renderButtonsOn($html){
	
		return $html->div()->class("buttons")->with(
					$html->span()->class("label")->with(
						$html->resetButton()->value("Cancel")
					).
					$html->span()->class("value")->with(
						$html->submitButton()->value("Update")
					)
			);
	}
	
	public function renderContentOn($html){
		foreach($this->keyPathsToRender() as $keyPath){
			$return .= $this->renderRowOn($html,$keyPath);
		}
		
		return $html->form()->with(
					$return.
					$this->renderButtonsOn($html)
				);
		
	}
	
	public function updateRoot($htmlRoot){
		parent::updateRoot($htmlRoot);
		$htmlRoot->addUrlArg($this->reserveable->tableName()."Id",$this->reserveable->oid());
		
		/*
		** The following need to be included for Date popups to work
		** I think these scripts are overkill for what I am doing
		** but it was one of the better JavaScript cal popups I found
		*/
		$htmlRoot->needsScript("date.js");
		$htmlRoot->needsScript("anchorPosition.js");
		$htmlRoot->needsScript("popupWindow.js");
		$htmlRoot->needsScript("calendarPopup.js");
		return $this;
	}
}