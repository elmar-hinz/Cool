<?php namespace Cool;

class Receivers implements \IteratorAggregate {

	protected $receivers = array();

	public function addReceiver(Receiver $receiver) {
		$this->receivers[] = $receiver;
	}

	public function getIterator() {
		return new \ArrayIterator($this->receivers);
	}

}

?>
