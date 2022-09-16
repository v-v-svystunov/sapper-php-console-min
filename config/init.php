<?php
namespace Matrix\Config;

use Matrix\Core\Common;

/**
 *
 * @package	Sapper config
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class init {
	public static function main() {
		
		date_default_timezone_set("Europe/Kiev");
		
		define('HOME_DIRECTORY', $_SERVER["DOCUMENT_ROOT"].'/');

		define("GRID_SIZE", 7);

		define("BLACK_HALL_NUM", 7);
	}
}

?>
