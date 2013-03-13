<?php namespace Cool;

require_once(__DIR__.'/../Classes/Finder.php');

class FinderTest extends \PHPUnit_Framework_TestCase {

	/**
	* @test
	*/
	public function class_register_is_an_array() {
		$m = new Finder();
		$this->assertInternalType('array', $m->getClassRegister());
	}

	/**
	* @test
	*/
	public function fillClassRegister_works() {
		$m = new Finder();
		$m->fillClassRegister();
		// print_r($m->getClassRegister());
		$this->assertArrayHasKey('Cool\Finder', $m->getClassRegister());
		$this->assertArrayHasKey('PHPUnit_Framework_Test', $m->getClassRegister());
	}

	/**
	* @test
	*/
	public function constructor_fills_class_register() {
		$m = new Finder();
		$this->assertArrayHasKey('Iterator', $m->getClassRegister());
	}

}

?>
	

