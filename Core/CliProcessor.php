<?php
namespace Matrix\Core;

/**
 *
 * @package	Cli Service Processor
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class CliProcessor {
	public static function init() { return (PHP_SAPI === 'cli');}

	public static function get_key() {
		if (!isset ($_SERVER["argv"])) {
			return ["h",0,0];
		}

		

		$ret = [self::prepare_keys ($_SERVER["argv"][1])];

		$param =  0;
		if (isset ($_SERVER["argv"][2])) {
			$param = self::prepare_keys ($_SERVER["argv"][2]);
		}

		array_push ($ret, $param);

		$additional =  0;
		if (isset ($_SERVER["argv"][3])) {
			$additional = self::prepare_keys ($_SERVER["argv"][3]);
		}

		array_push ($ret, $additional);

		return $ret;
	} 

	public static function prepare_keys ($key) {
		return preg_replace ("/^\-/i", "", $key);
	} 

	public static function show_help($error = '') {
    if ($error) {
    	die(<<<OUTPUT
In process to execute module problem occured.    		
Error: $error.


OUTPUT
    );
    }

    die(<<<OUTPUT

    _____                                             __      __ __      __   _____                                 _                 
  / ____|                                            \ \    / / \ \    / /  / ____|                               (_)                
 | (___     __ _   _ __     ___   _ __     ______     \ \  / /   \ \  / /  | (___     __   __   ___   _ __   ___   _    ___    _ __  
  \___ \   / _` | | '_ \   / _ \ | '__|   |______|     \ \/ /     \ \/ /    \___ \    \ \ / /  / _ \ | '__| / __| | |  / _ \  | '_ \ 
  ____) | | (_| | | |_) | |  __/ | |                    \  /       \  /     ____) |    \ V /  |  __/ | |    \__ \ | | | (_) | | | | |
 |_____/   \__,_| | .__/   \___| |_|                     \/         \/     |_____/      \_/    \___| |_|    |___/ |_|  \___/  |_| |_|
                  | |                                                                                                                
                  |_|                                                                                                                

Usage: VVS CLI game [operations]

Use and test Game.

\033[31mAuthor: \033[0m Svystunov Valeriy 

Operations is a list of the following options (--help by default):

    -h, --help              			 Shows Help page you are reading here

    -v              			 Shows current Grid statement.
    -i              			 Initiate to create new Grid for new game  ::
    	- second parameter can be used as number rows and column of the Grid (defailt equiv 7 and maximum 25);
    	- third parameter can be used as number of black halls (defailt equiv 7 and maximum second parameter ^ 2);
    -m              			 Make a move to switch Grid to the next statement  ::
    	- second parameter should be used as coordinate to the target cell of Grid (as example 1a or a1);
    -t              			 Emulates creation new Grid for new game and shows view  ::
    	- second parameter can be used as number rows and column of the Grid (defailt equiv 7 and maximum 25);
    	- third parameter can be used as number of black halls (defailt equiv 7 and maximum second parameter ^ 2);
    
    

Example:
    saper -h

    Dumps shows if any files did not add or modified Localy.


OUTPUT
    );
}
	
}
?>
