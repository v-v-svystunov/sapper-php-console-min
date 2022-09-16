<?php

namespace Matrix\Core;

use Matrix\Core\ExceptionProcessor;

/**
 *
 * @package	Shared commonly useful methods collection
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class Common {

	public static function isNumeric($string){
		if (preg_match('/^[1-9][0-9]*$/', $string)){
			return 1;
		}
		return 0;
	}
}
?>
