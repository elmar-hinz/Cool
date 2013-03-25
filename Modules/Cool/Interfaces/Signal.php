<?php namespace Cool;

interface Signal {

	/**
	* @param $object the sender
	* @param $mixed mixed data from the sender
	* @return \Cool\Receivers 
	*/
	static public function send($sender, $mixed = NULL);
	
	/**
	* @return $object the sender
	*/
	public function getSender();
	
	/**
	* @return $mixed mixed data from the sender
	*/
	public function getData();
	
}

?>

