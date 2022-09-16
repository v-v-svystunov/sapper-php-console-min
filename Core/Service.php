<?php
namespace Matrix\Core;


use Matrix\Core\ExceptionProcessor;

/**
 *
 * @package	Services collection
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class Service {
	
	private $registry = [];
	

	static private $INSTANCE = null;

	
	public function __clone(){}
	
	static function getInstance(){
		if (self::$INSTANCE == NULL){self::$INSTANCE = new Service();}
		return self::$INSTANCE;
	}

	private function __construct() { $this->initialize(); }

	
	public function initialize(){
		$this->registry["CREATE_ALPHA_BET_COUNTER"] = "createAlphaBetCounter";
		$this->registry["DEFF_RECTANGULAR_INDEX"] = "defineRectangularCoord";
	}

	public function explore ($key, $parameters = []) {
		if (!array_key_exists($key, $this->registry)) {
			return null;
		}
		if (!method_exists ($this, $this->registry[$key])) {
			return null;
		}
		$method = $this->registry[$key];
		if (count ($parameters)) {
			return $this->$method($parameters);
		}
		return $this->$method();
	}

	private function createAlphaBetCounter ($parameters) {
		if (array_key_exists ("grid_num",$parameters)) {
			$_grid_num = $parameters["grid_num"];
		} else {
			$_grid_num = GRID_SIZE;
		}

		$_alphabet_counter = range('a','z');
		array_splice ($_alphabet_counter,$_grid_num);

		return $_alphabet_counter;
	}

	private function defineRectangularCoord ($parameters) {
		$ret = null;
		if (!isset($parameters["alpha"])) {
			return $ret;
		}
		if (!isset($parameters["decimal"])) {
			return $ret;
		}

		if (!isset($parameters["alpha_row"])) {
			return $ret;
		}

		if (!isset($parameters["grid_size"])) {
			return $ret;
		}

		$_alpha_to_dec = array_search ($parameters["alpha"], $parameters["alpha_row"]);
		
		$ret = (  $_alpha_to_dec * $parameters["grid_size"] ) + ( $parameters["decimal"] - 1 );

		return $ret;
	}

}

?>
