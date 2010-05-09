<?

class WHHtmlText extends WHTag {
	
	function __toString(){
		$return = sprintf("%s",$this->contents);
		return $return;
	}
}