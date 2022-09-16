<?php
namespace Matrix\Core;

/**
 *
 * @package	Autoloader for Common usage
 * @author  Svistunov Valery (VVS)
 * @version	1.1
 *
 */
class Autoloader{

	private $path;

	function __construct($path){
		$this->path = $path;
		spl_autoload_register(null, false);
		spl_autoload_extensions('.php');
		spl_autoload_register(array($this, 'classLoader'));
	}
	
	private function classLoader($classPath){
		$namespacePath = str_replace('\\','/',strstr($classPath, '\\'));
		$class = str_replace('/', '', strrchr($namespacePath, '/'));

		if ($file=$this->smartClassLoader($classPath)){
			include_once $file;
		} else {
			$file = $this->findFile($class.'.php');
			if (!file_exists($file)){ return false; }
			include_once $file;
		}
		
		return false;
	}
	
	private function findFile($fileName){
		foreach ($this->path as $path){
			$file = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
			while ($file->valid()){
				if (!$file->isDot()){
					if ($file->getFileName() == $fileName) {  
						return $file->getPath() .'/'. $file->getFileName();
					}
				}
				$file->next();
			}
		}
	}

	public static function smartClassLoader($className){
		$thisClass = str_replace(__NAMESPACE__.'\\', '', __CLASS__);

		$baseDir = MAIN_APP_ROOT;

		if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
			$baseDir = substr($baseDir, 0, -strlen($thisClass));
		}

		$className = ltrim($className, '\\'.MAIN_NAMESPACE);
		$fileName  = $baseDir;
		$namespace = '';
		if ($lastNsPos = strripos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		if (file_exists($fileName)) {
			return $fileName;
		}
	}
}
?>
