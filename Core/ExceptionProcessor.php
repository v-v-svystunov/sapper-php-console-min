<?php
namespace Matrix\Core;

/**
 *
 * @package	Error Engine
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class ExceptionProcessor {

	public function __construct ( $e = null ) {
		if (!$e||!is_object($e)) {
			die("Unexpected error occured and can't be processed by Exception processor");
		}
		$exception = $this->prepare_exception_obj_based($e);
		$this->get_e_view($exception);
		exit;
	}

	public static function onFatalError(){
		if(!is_null($e = error_get_last())) {
			switch ( $e["type"] ) {
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
				case E_RECOVERABLE_ERROR:
					$exception = self::verify_exception($e);
					self::get_e_view($exception);
					break;
				default:
					break;
			}
		} 
		
		return true;
	}
	
	private function get_e_view($exception){
		print "Message: " . $exception["message"] . "\n";
		print "Type: " .  ((isset($exception["type"])) ? $exception["type"] : "") . "\n";
		print "File: " . $exception["file"] . "\n";
		print "Line: " . $exception["line"] . "\n";
		print "Stack Trace: " . ((isset($exception["trace_string"])) ? $exception["trace_string"] : "") . "\n";
		print "Class: " . ((isset($exception["trace_string"])) ? $exception["class"] : "") . "\n";
		print "Func: " . ((isset($exception["trace_string"])) ? $exception["function"] : "") . "\n";
		die("\n\n");
	}
	
	private function verify_exception($exception){
		$ret = array();
		$ret["message"] = (isset($exception["message"])) ? $exception["message"] : "No Message";
		$ret["type"] = (isset($exception["type"])) ? self::get_e_type_by_code($exception["type"]) : "Undeffined Type";
		$ret["file"] = (isset($exception["file"])) ? $exception["file"] : "Undeffined File";
		$ret["line"] = (isset($exception["line"])) ? $exception["line"] : "Undeffined Line";
		
		return $ret; 
	}
	
	private function get_e_type_by_code($code){
		$retVal = ""; 
		switch ($code){
			case "1":
				$retVal = "Type of E_ERROR";
				break;
			case "4":
				$retVal = "Type of E_PARSE";
				break;
			case "16":
				$retVal = "Type of E_CORE_ERROR";
				break;
			case "64":
				$retVal = "Type of E_COMPILE_ERROR ";
				break;
			case "256":
				$retVal = "Type of E_USER_ERROR";
				break;
			case "4096":
				$retVal = "Type of E_RECOVERABLE_ERROR";
				break;
			default:
				break;
		}
		
		return $retVal;
	}
	
	private function prepare_exception_obj_based($exception){
		$retStructure = array();
		if(isset($exception->e)){
			$retStructure["code"] = $exception->e->getCode();
		} else {
			$retStructure["code"] = $exception->getCode();
		}
		$retStructure["class"] = "";
		$retStructure["function"] = "";
		if(isset($exception->e)){
			$retStructure["message"] = $exception->e->getMessage();
		} else {
			$retStructure["message"] = $exception->getMessage();
		}
		if (isset($exception->sql)) {
			$retStructure["message"] .= "<br>Occured for SQL::".$exception->sql."<br>";
		}
		if (isset($exception->e_no_mysqli)&&isset($exception->e_mysqli)) {
			$retStructure["message"] .= "<br>DB says::".$exception->e_no_mysqli." - ".$exception->e_mysqli."<br>";	
		}
		if(isset($exception->e)){
			$retStructure["file"] = $exception->e->getFile();
		} else {
			$retStructure["file"] = $exception->getFile();
		}
		if(isset($exception->e)){
			$retStructure["line"] = $exception->e->getLine();
		} else {
			$retStructure["line"] = $exception->getLine();
		}
		if(isset($exception->e)){
			$retStructure["trace_string"] = $exception->e->getTraceAsString();
		} else {
			$retStructure["trace_string"] = $exception->getTraceAsString();
		}
		$traceStructure = explode ( "#", $retStructure["trace_string"] );
		$retStructure["trace_string"] = "";
		for ( $i = 0; $i < count( $traceStructure ); $i++ ) {
			$retStructure["trace_string"] .= $traceStructure[$i] . "\n";
		}  
		unset ( $traceStructure );
		if(isset($exception->e)){
			$retStructure["trace"] = $exception->e->getTrace();
		} else {
			$retStructure["trace"] = $exception->getTrace();
		}
		if (is_array($retStructure["trace"])&&count($retStructure["trace"])){
			if (isset($retStructure["trace"][(count($retStructure["trace"]))-1]["file"])&&isset($retStructure["trace"][(count($retStructure["trace"]))-1]["line"])){
				$retStructure["file"] = $retStructure["trace"][(count($retStructure["trace"]))-1]["file"];
				$retStructure["line"] = $retStructure["trace"][(count($retStructure["trace"]))-1]["line"];
			}
			if (isset($retStructure["trace"][(count($retStructure["trace"]))-1]["class"])) $retStructure["class"] = $retStructure["trace"][(count($retStructure["trace"]))-1]["class"];
			if (isset($retStructure["trace"][(count($retStructure["trace"]))-1]["function"])) $retStructure["function"] = $retStructure["trace"][(count($retStructure["trace"]))-1]["function"];
		}
		
		return $retStructure; 
	}
	
}
?>