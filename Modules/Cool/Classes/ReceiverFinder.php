<?php namespace Cool;

class ReceiverFinder implements Singleton {

	private $receiverInterface = '\Cool\Receiver';
	private $register = array();

	public function __construct() {
		$this->register();
	}

	public function getReceiversForSignal($signal) {
		$list = array();
		foreach($this->register as $class) 
			if($class::listensTo() == $signal) 
				$list[] = $class;
		return $list;
	}

	protected function register() {
		$classes = get_declared_classes();
		foreach($classes as $class) {
			$ref = new \ReflectionClass($class);
			if(!$ref->isAbstract() && $ref->implementsInterface($this->receiverInterface))
				$this->register[] = $class;
		}
	}

}

?>
