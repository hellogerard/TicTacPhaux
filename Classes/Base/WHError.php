<?php
$WHERROR_TYPES = array (
                E_ERROR              => 'Error',
                E_WARNING            => 'Warning',
                E_PARSE              => 'Parsing Error',
                E_NOTICE             => 'Notice',
                E_CORE_ERROR         => 'Core Error',
                E_CORE_WARNING       => 'Core Warning',
                E_COMPILE_ERROR      => 'Compile Error',
                E_COMPILE_WARNING    => 'Compile Warning',
                E_USER_ERROR         => 'User Error',
                E_USER_WARNING       => 'User Warning',
                E_USER_NOTICE        => 'User Notice',
                E_STRICT             => 'Runtime Notice',
                E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
                );


$WHERROR_FATAL = array(
				E_ERROR,
				E_WARNING,
				E_PARSE,
				E_CORE_ERROR,
				E_COMPILE_ERROR,
				E_USER_ERROR,
				E_RECOVERABLE_ERROR	
				);
				
/*
** Can't handle  E_ERROR, E_PARSE, E_CORE_ERROR, 
** E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING
** But I am including them in case some time in 
** the future this changes
*/
$WHERROR_TRACEBACK = array(
					E_ERROR,
					E_USER_ERROR,
					E_USER_WARNING,
					E_RECOVERABLE_ERROR	
					);
$WHERROR_CATCH = E_ALL;

class WHError extends Object {
	protected $cleanExit = FALSE;
	protected $errorUrl = "/error.php";
	
	public function start(){
		$this->registerErrorHandler();
		ob_start(array($this, 'checkForErrorsAndOutput'));
		return $this;
	}
	
	static function registerErrorHandler(){
		global $WHERROR_CATCH;
		set_error_handler(array("WHError","errorHandler"));
		error_reporting($WHERROR_CATCH);
		return $this;
	}

	static function errorHandler($errno,$errstr,$errfile,$errline){
		global $WHERROR_FATAL;
		global $WHERROR_TYPES;
		global $DEBUG_ERRORS;
		global $WHERROR_TRACEBACK;
		//echo $errstr."\n";
		
	
		if(in_array($errno,$WHERROR_FATAL)){
			if(in_array($errno,$WHERROR_TRACEBACK)){
				throw new WHException($WHERROR_TYPES[$errno], $errno);
			}else{
				die("$errstr in $errfile on line $errline");
			}
		}else{
			$DEBUG_ERRORS .= "\n<!--\n$errstr in $errfile on $errline\n-->\n";
		}

	}
	
	public function pretyError($errno,$errstr,$errfile,$errline){
		$return = "<h2> Error: #$errno ".
				$errstr.
				" in ".
				$errfile.
				" on line " .
				$errline.
				"</h2>";
	
		foreach(debug_backtrace() as $point){
			$return .= WHException::niceFromTracePoint($point,TRUE);
		
		}
		return substr($return,0,1000000);
	}
	
	public function checkForErrorsAndOutput($buffer){
		global $app;
		global $app_configurations;
		if(!$this->cleanExit){
			
			if(!$app_configurations[$app]['general']['debug']){
				$message = " # ". WHException::writeErrorToTempFile($buffer);
				
			}else{
				$message = $buffer;
			}
			
			return WHException::errorPage($message,$app_configurations[$app]['general']['admin_email']);
		}
		return $buffer;
	}
	
	public function end(){
		
		$this->cleanExit = TRUE;
		return $this;
	}
	
	
}