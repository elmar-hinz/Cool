<?php namespace Cool;

class ActiveLoader {

	private $bases;
		
	function addBase($directory) {
		$this->bases[] = $directory;
	}

	function getBases() {
		return $this->bases;
	}

	function go() {
		foreach($this->bases as $directory) {
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

