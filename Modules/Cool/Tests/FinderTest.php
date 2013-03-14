<?php namespace Cool;

class FinderTest extends \PHPUnit_Framework_TestCase {
	
	private $finder;

	public function setUp() {
		require_once(__DIR__.'/../../Cool/Classes/LoadTestHelper.php');
		\Cool\LoadTestHelper::loadAll();
		$this->finder = new Finder();
	}

	/**
	* @test
	*/
	public function finder_is_a_singleton() {
		$this->assertInstanceOf('\Cool\Singleton', $this->finder);
	}

	/**
	* @test
	*/
	public function class_register_is_an_array() {
		$this->assertInternalType('array', $this->finder->getClassRegister());
	}

	/**
	* @test
	*/
	public function fillClassRegister_works() {
		$this->finder->fillClassRegister();
		$this->assertArrayHasKey('Cool\Finder', $this->finder->getClassRegister());
		$this->assertArrayHasKey('PHPUnit_Framework_Test', $this->finder->getClassRegister());
	}

	/**
	* @test
	*/
	public function constructor_fills_class_register() {
		$this->assertArrayHasKey('Iterator', $this->finder->getClassRegister());
		// print_r($this->finder->getClassRegister());
	}

	/**
	* @test
	*/
	public function getClass_returns_a_classname() {
		$cn = $this->finder->getClass('Iterator');
		$rf = new \ReflectionClass($cn);
		$this->assertTrue($rf->isSubclassOf('Iterator'));

	}

	/**
	* @test
	* @expectedException Exception
	* @expectedExceptionMessage No class for interface
	*/
	public function getClass_throws_exeption_for_missing_subclasses() {
		$this->finder->getClass('NotThere');
	}

	/**
	* @test
	*/
	public function getService_finds_any_service_for_Service() {
		$classname = $this->finder->getService('Cool\Service');
		$rf = new \ReflectionClass($classname);
		$this->assertTrue($rf->isSubclassOf('Cool\Service'));
	}

	/**
	* @test
	*/
	public function getService_finds_DummyService() {
		$classname = $this->finder->getService('Cool\DummyService');
		$this->assertEquals('Cool\DummyService', $classname);
	}

	/**
	* @test
	* @expectedException Exception
	* @expectedExceptionMessage No service found
	*/
	public function getService_throws_exeption_if_unsuccessful() {
		$this->finder->getService('NotThere');
	}

}

?>
	

