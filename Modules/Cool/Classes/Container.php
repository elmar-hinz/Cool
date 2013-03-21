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
	
	public function getService($serviceType, $mixedCriteria = NULL) {
		$name = $this->finder->getService($serviceType, $mixedCriteria);
		return $this->getInstance($name);
	}

	public function getHooks($hookType, $mixedCriteria) {
		$names = $this->finder->getHooks($hookType, $mixedCriteria);
		$hooks = array();
		foreach($names as $name) $hook[] = $this->getInstance($name);
		return $hooks;
	}

	public function getInstance($wandtedClass) {
		$class =  $this->finder->getClass($wandtedClass);
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
		foreach($argumentNames as $name) $arguments[] = $this->getInstance($name);
		return $arguments;
	}

}

?>

