<?php namespace Cool;

require_once(__DIR__.'/../Classes/Manager.php');

class ManagerTest extends \PHPUnit_Framework_TestCase {

	/**
	* @test
	*/
	public function class_register_is_an_array() {
		$m = new Manager();
		$this->assertInternalType('array', $m->getClassRegister());
	}

	/**
	* @test
	*/
	public function fillClassRegister_works() {
		$m = new Manager();
		$m->fillClassRegister();
		// print_r($m->getClassRegister());
		$this->assertArrayHasKey('Cool\Manager', $m->getClassRegister());
		$this->assertArrayHasKey('PHPUnit_Framework_Test', $m->getClassRegister());
	}

	/**
	* @test
	*/
	public function constructor_fills_class_register() {
		$m = new Manager();
		$this->assertArrayHasKey('Iterator', $m->getClassRegister());
	}

}

?>
	

