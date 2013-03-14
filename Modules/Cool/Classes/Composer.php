<?php namespace Cool;

class Composer {

	private $finder;

	public function __construct(Finder $finder = NULL) {
		if($finder === NULL) {
			$this->finder = new Finder();
		} else {
			$this->finder = $finder;
		}
	}
	
	public function getFinder() {
		return $this->finder;
	}

	public function instantiate($wantedInterface) {
		$class =  $this->finder->getClass($wantedInterface);
		$ref = new \ReflectionClass($class);
		$argumentNames= $this->getArgumentNames($ref);
		$arguments = $this->instantiateArguments($argumentNames);
		return $ref->newInstanceArgs($arguments);
	}

	public function getArgumentNames($ref) {
		$array = $ref->getConstructor()->getParameters();
		$names = array();
		foreach($array as $par) $names[] = $par->getClass()->getName();
		return $names;
	}

	public function instantiateArguments($argumentNames) {
		$arguments = array();
		foreach($argumentNames as $name) $arguments[] = $this->instantiate($name);
		return $arguments;
	}

}

?>


