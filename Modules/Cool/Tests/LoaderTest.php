<?php namespace Cool;


class LoaderTest extends \PHPUnit_Framework_TestCase {

	private $sut;

	public function setUp() {
		require_once(__DIR__.'/../Classes/Loader.php');
		$this->sut = $this->getMockBuilder('\Cool\Loader')->setMethods(array('go'))->getMock();
	}

	/**
	* @test
	*/
	public function loader_can_be_created() {
		$this->assertInstanceOf('Cool\Loader', $this->sut);
	}

	/**
	* @test
	*/
	public function moduleBases_can_be_added() {
		$this->sut->addModuleBase('directory1');
		$this->sut->addModuleBase('directory2');
		$expect = array('directory1', 'directory2');
		$this->assertEquals($expect, $this->sut->getModuleBases());
	}

}

?>
	

