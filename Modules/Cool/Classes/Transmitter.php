<?php namespace Cool;

class Transmitter implements Singleton {
	
	private $finder;
	private $receiversTemplate;

	public function __construct(\Cool\ReceiverFinder  $finder, 
		\Cool\Receivers $emptyReceiversTemplate) {
		$this->finder = $finder;
		$this->receiversTemplate = $emptyReceiversTemplate;
	}

	/**
	* Braodcasts a signal
	* Returns all receivers listening to the signal.
	*
	* @param \Cool\Signal
	* @return \Cool\Receivers
	*/
	public function broadcast($signal) {
		$list  = $this->finder->getReceiversForSignal(get_class($signal));
		$receivers = clone $this->receiversTemplate;
		foreach($list as $class) $receivers->addReceiver(new $class());
		foreach($receivers as $receiver) $receiver->receive($signal);
		return $receivers;
	}

}

?>
