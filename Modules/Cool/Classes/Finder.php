<?php namespace Cool;

class Finder implements Singleton {

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

	public function getClass($interface) {
		if(isset($this->classRegister[$interface][0])) {
			return $this->classRegister[$interface][0];
		} else {
			throw new \Exception('No class for interface: ' . $interface);
		}
	}

	public function getService($interface, $mixedCriteria = NULL) {
		$candidates = array();
		if(isset($this->classRegister[$interface]))
			$candidates = $this->classRegister[$interface];
		foreach($candidates as $candidate) 
			if($candidate::canServe($mixedCriteria)) return $candidate;
		throw new \Exception('No service found for: ' . $interface);
	}

	public function getHooks($interface, $mixedCriteria = NULL) {
		return array();
	}

}

?>
