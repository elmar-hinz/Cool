<?php namespace Cool;

require_once(__DIR__.'/Loader.php');

class AutoLoader extends Loader {

	public function go() {
		spl_autoload_register(array($this, 'loadClasses'));
		spl_autoload_register(array($this, 'loadInterfaces'));
		spl_autoload_register(array($this, 'loadServices'));
		spl_autoload_register(array($this, 'loadSignals'));
		spl_autoload_register(array($this, 'loadReceivers'));
	}

	protected function loadClasses($className) {
		$this->loadByModuleDir('Classes/', $className);
	}

	protected function loadInterfaces($className) {
		$this->loadByModuleDir('Interfaces/', $className);
	}

	protected function loadServices($className) {
		$this->loadByModuleDir('Services/', $className);
	}

	protected function loadSignals($className) {
		$this->loadByModuleDir('Signals/', $className);
	}

	protected function loadReceivers($className) {
		$this->loadByModuleDir('Receivers/', $className);
	}

	protected function loadByModuleDir($moduleDirectory, $className) {
		$parts = explode('\\', $className);
		foreach($this->getModuleBases() as $base) {
			$path = $base;
			$path .= '/' . array_shift($parts);
			$path .= '/'.$moduleDirectory.'/';
			$path .= join('/', $parts) . '.php';
			if(file_exists($path)) {
				ob_start();
				require_once($path);
				ob_end_clean();
			}
		}
	}
}

?>
