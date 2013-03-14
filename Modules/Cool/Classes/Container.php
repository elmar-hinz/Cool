<?php namespace Cool;

class Container implements Singleton {

	private $singletons;
	private $finder;

	public function __construct(Finder $finder = NULL) {
		if($finder === NULL) $finder = new Finder();
		assert($finder instanceOf Singleton);
		$this->singletons[get_class($finder)] = $finder;
		$this->finder = $finder;
		assert($this instanceOf Singleton);
		$this->singletons[get_class($this)] = $this;
	}
	
	public function instantiate($wantedInterface) {
		$class =  $this->finder->getClass($wantedInterface);
		$rc = new \ReflectionClass($class);
		if(isset($this->singletons[$class])) {
			$obj = $this->singletons[$class];
		} else {
			$obj = $this->makeInstance($rc);
			if($rc->isSubclassOf('\Cool\Singleton'))
				$this->singletons[$class] = $obj;
		}
		return $obj;
	}

	private function makeInstance($reflectionClass) {
		$argumentNames= $this->getArgumentNames($reflectionClass);
		$arguments = $this->instantiateArguments($argumentNames);
		return $reflectionClass->newInstanceArgs($arguments);
	}

	private function getArgumentNames($rc) {
		$const = $rc->getConstructor();
		$parameters = array();
		$names  = array();
		if(is_object($const)) {
			$parameters = $const->getParameters();
		}
		foreach($parameters as $par) $names[] = $par->getClass()->getName();
		return $names;
	}

	private function instantiateArguments($argumentNames) {
		$arguments = array();
		foreach($argumentNames as $name) $arguments[] = $this->instantiate($name);
		return $arguments;
	}

}

?>


