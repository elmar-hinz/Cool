<?php namespace Cool;

interface Receiver {

	/**
	* @returns String name of the Signal to listen to
	*/
	static function listensTo();
	
	/**
	* @param \Cool\Signal the received signal
	* @returns void 
	*/
	function receive(\Cool\Signal $signal);
	
}

?>

