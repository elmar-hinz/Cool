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
			$ref = new \ReflectionClass($class);
			if($ref->isAbstract()) continue;
			foreach(class_implements($class) as $interface) {
				$this->classRegister[$interface][] = $class;
			}
			foreach(class_parents($class) as $interface) {
				$this->classRegister[$interface][] = $class;
			}
			$this->classRegister[$class][] = $class;
		}
	}

	public function getClass($wantedInterface) {
		if(isset($this->classRegister[$wantedInterface][0])) {
			return $this->classRegister[$wantedInterface][0];
		} else {
			throw new \Exception('No class for interface: ' . $wantedInterface);
		}
	}

}

?>
