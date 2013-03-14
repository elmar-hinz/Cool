<?php namespace Cool;

class ContainerTest extends \PHPUnit_Framework_TestCase {

	private $container;

	public function setUp() {
		require_once(__DIR__.'/../../Cool/Classes/LoadTestHelper.php');
		\Cool\LoadTestHelper::loadAll();
		$this->container = new Container();
	}

	/**
	* @test
	*/
	public function container_is_a_singleton() {
		$this->assertInstanceOf('\Cool\Singleton', $this->container);
	}

	/**
	* @test
	*/
	public function instantiate_returns_an_object() {
		$o = $this->container->instantiate('Cool\Container');
		$this->assertInstanceOf('\Cool\Container', $o);
	}

	/**
	* @test
	*/
	public function container_treats_LoadTestHelper_not_as_singleton() {
		$obj1 = $this->container->instantiate('Cool\LoadTestHelper');
		$obj2 = $this->container->instantiate('Cool\LoadTestHelper');
		$this->assertNotSame($obj1, $obj2);
	}

	/**
	* @test
	*/
	public function container_treats_Finder_as_singleton() {
		$obj1 = $this->container->instantiate('Cool\Finder');
		$obj2 = $this->container->instantiate('Cool\Finder');
		$this->assertSame($obj1, $obj2);
	}

	/**
	* @test
	*/
	public function container_treats_itself_as_singleton () {
		$other = $this->container->instantiate('Cool\Container');
		$this->assertSame($other, $this->container);
	}

	/**
	* @test
	*/
	public function container_can_be_constructed_with_finder_and_treats_it_as_singleton(){
		$finder = new Finder();
		$container = new Container($finder);
		$other = $container->instantiate('Cool\Finder');
		$this->assertSame($other, $finder);
	}

}
?>
	

