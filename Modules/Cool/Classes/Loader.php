<?php namespace Cool;

abstract class Loader {

	protected $moduleBases = array();
		
	public function addModuleBase($base) {
		$this->moduleBases[] = $base;
	}

	public function getModuleBases() {
		return $this->moduleBases;
	}

	abstract function go();
}

?>
