<?php namespace Cool;

require_once(__DIR__.'/../Classes/DedicatedDirectoriesLoader.php');

class DedicatedDirectoriesLoaderTest extends \PHPUnit_Framework_TestCase {

	private $moduleBase = '/tmp/Modules';
	private $service = '/tmp/Modules/TestModule/Services/Subdirectorey/TestService.php';
	private $receiver  = '/tmp/Modules/TestModule/Receivers/Subdirectorey/TestReceiver.php';
	private $class  = '/tmp/Modules/TestModule/Classes/Subdirectorey/TestClass.php';
		
	function setUp() {
		exec('mkdir -p '.dirname($this->service));
		exec('mkdir -p '.dirname($this->receiver));
		exec('mkdir -p '.dirname($this->class));
		file_put_contents($this->service, '<?php $GLOBALS["service"] = TRUE; ?>');
		file_put_contents($this->receiver, '<?php $GLOBALS["receiver"] = TRUE; ?>');
		file_put_contents($this->class, '<?php $GLOBALS["class"] = TRUE; ?>');
		assert(file_exists($this->service));
		assert(file_exists($this->receiver));
		assert(file_exists($this->class));
		$this->sut = new DedicatedDirectoriesLoader();
	}

	function tearDown() {
		assert(file_exists($this->moduleBase));
		exec('rm -rf '.$this->moduleBase);
		assert(!file_exists($this->moduleBase));
	}

	/**
	* @test
	*/
	public function loader_can_be_created() {
		$this->assertInstanceOf('\Cool\DedicatedDirectoriesLoader', $this->sut);
	}

	/**
	* @test
	*/
	public function dedicated_directories_are_loaded() {
		$this->sut->addModuleBase($this->moduleBase);
		$this->sut->go();
		$this->assertArrayHasKey('service', $GLOBALS);
		$this->assertTrue($GLOBALS['service']);
		$this->assertArrayHasKey('receiver', $GLOBALS);
		$this->assertTrue($GLOBALS['receiver']);
	}

	/**
	* @test
	*/
	public function other_directories_are_not_loaded() {
		$this->sut->addModuleBase($this->moduleBase);
		$this->sut->go();
		$this->assertArrayNotHasKey('class', $GLOBALS);
	}

}

?>

