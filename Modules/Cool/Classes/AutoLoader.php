<?php namespace Cool;

class AutoLoader {

	private $bases = array();
		
	public function addBase($base) {
		$this->bases[] = $base;
	}

	public function getBases() {
		return $this->bases;
	}

	public function go() {
		spl_autoload_register(array($this, 'loadClasses'));
		spl_autoload_register(array($this, 'loadInterfaces'));
	}

	private function loadClasses($className) {
		$this->loadByModuleDir('Classes/', $className);
	}

	private function loadInterfaces($className) {
		$this->loadByModuleDir('Classes/Interfaces/', $className);
	}

	private function loadByModuleDir($moduleDirectory, $className) {
		$parts = explode('\\', $className);
		foreach($this->bases as $base) {
			$path = $base;
			$path .= '/' . array_shift($parts);
			$path .= '/'.$moduleDirectory.'/';
			$path .= join('/', $parts) . '.php';
			if(file_exists($path)) 
				require_once($path);
		}
	}
}

?>
