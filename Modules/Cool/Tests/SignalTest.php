<?php

require_once(__DIR__.'/../Interfaces/Signal.php');

class SignalTest extends \PHPUnit_Framework_TestCase {

	/**
	* @test
	*/
	public function isInterface() {
		$this->assertTrue(interface_exists('\Cool\Signal'));
	}

	/**
	* @test
	*/
	public function has_method_send() {
		$this->assertTrue(method_exists('\Cool\Signal', 'send'));
	}

	/**
	* @test
	*/
	public function has_method_getSender() {
		$this->assertTrue(method_exists('\Cool\Signal', 'getSender'));
	}

	/**
	* @test
	*/
	public function has_method_getData() {
		$this->assertTrue(method_exists('\Cool\Signal', 'getData'));
	}

}

?>

