<?php namespace Cool;

class ReceiverFinder implements Singleton {

	private $receiverInterface = '\Cool\Receiver';
	private $register = array();

	public function __construct() {
		$this->register();
	}

	public function getReceiversForSignal($signal) {
		if(isset($this->register[$signal]))
			return $this->register[$signal];
		else 
			return array();
	}

	protected function register() {
		$classes = get_declared_classes();
		foreach($classes as $class) {
			$ref = new \ReflectionClass($class);
			if(!$ref->isAbstract() && $ref->implementsInterface($this->receiverInterface)) {
				$signal = $this->getSignalNameForClass($class);
				$this->register[$signal][] = $class;
			}
		}
	}

	protected function getSignalNameForClass($class) {
				return $class::listensTo();
	}

}

?>
