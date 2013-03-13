<?php namespace Cool;

class Finder {

	private $classRegister = array();

	public function __construct() {
		$this->fillClassRegister();
	}

	public function getClassRegister() {
		return $this->classRegister;
	}

	public function fillClassRegister() {
		$this->classRegister = array();
		$classes = get_declared_classes();
		foreach($classes as $class) {
			foreach(class_implements($class) as $interface) {
				$this->classRegister[$interface][] = $class;
			}
			foreach(class_parents($class) as $interface) {
				$this->classRegister[$interface][] = $class;
			}
			$this->classRegister[$class][] = $class;
		}
	}

}

?>
