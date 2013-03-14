<?php namespace Cool;

class Loader {

	private $directories;
		
	function addPath($directory) {
		$this->directories[] = $directory;
	}

	function getPathes() {
		return $this->directories;
	}

	function go() {
		foreach($this->directories as $directory) {
			$this->includePhpFiles($directory);
		}
	}
	
	private function includePhpFiles($directory) {
		$dirIter = new \RecursiveDirectoryIterator($directory);
		foreach(new \RecursiveIteratorIterator($dirIter) as $fileInfo) {
			if($fileInfo->isFile() && $fileInfo->getExtension() == 'php') {
				require_once($fileInfo->getPathname());
			}
		}
	}
}

?>

