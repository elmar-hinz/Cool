<?php namespace Cool;

abstract class AbstractSignal implements Signal {
	
	static private $transmitter;
	private $mixedData;
	private $sender;

	static protected function getTransmitter() {
		if(!self::$transmitter) {
			$finder = new ReceiverFinder();
			$emptyReceiversTemplate = new Receivers();
			self::$transmitter = new Transmitter($finder, $emptyReceiversTemplate);
		}
		return self::$transmitter;
	}
	
	static public function send($sender, $mixed = NULL) {
		$class = get_called_class();
		$signal = new $class($sender, $mixed);
		$transmitter = static::getTransmitter();
		return $transmitter->broadcast($signal);
	}

	public function __construct($sender, $mixedData) {
		$this->sender = $sender;
		$this->mixedData = $mixedData;
	}

	public function getData() {
		return $this->mixedData;
	}

	public function	getSender() {
		return $this->sender;
	}

}

?>
