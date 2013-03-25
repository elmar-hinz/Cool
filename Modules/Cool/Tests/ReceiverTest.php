<?php

require_once(__DIR__.'/../Interfaces/Receiver.php');

class ReceiverTest extends \PHPUnit_Framework_TestCase {

	/**
	* @test
	*/
	public function isInterface() {
		$this->assertTrue(interface_exists('\Cool\Receiver'));
	}

	/**
	* @test
	*/
	public function has_method_listensTo() {
		$this->assertTrue(method_exists('\Cool\Receiver', 'listensTo'));
	}

	/**
	* @test
	*/
	public function has_method_receive() {
		$this->assertTrue(method_exists('\Cool\Receiver', 'receive'));
	}

}

?>

