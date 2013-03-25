<?php namespace Cool;

require_once(__DIR__.'/Loader.php');

class DedicatedDirectoriesLoader extends Loader {

	protected $classTypes = array('Services', 'Receivers');

	public function go() {
		$moduleBases = $this->getModuleBases();
		array_walk($moduleBases, array($this,'insideModuleBase'));
	}

	protected function insideModuleBase($moduleBase) {
		foreach(new \DirectoryIterator($moduleBase) as $dir) $this->insideModule($dir);
	}

	protected function insideModule($moduleDirectory) {
		array_walk($this->classTypes, array($this,'insideType'), $moduleDirectory);
	}

	protected function insideType($type, $key, $parentDirectory) {
		if(is_dir($dir = $parentDirectory->getPathname().'/'.$type)) {
			$dirIterator = new \RecursiveDirectoryIterator($dir);
			foreach(new \RecursiveIteratorIterator($dirIterator) as $fileInfo) 
				$this->handleFile($fileInfo, $type);
		}
	}

	protected function handleFile($fileInfo, $type) {
		if($fileInfo->isFile() && $fileInfo->getExtension() == 'php') {
			ob_start();
			require_once($fileInfo->getPathname());
			ob_end_clean();
		}
	}
}

?>

