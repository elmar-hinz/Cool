<?php namespace Cool;

require_once(__DIR__.'/../Classes/AutoLoader.php');

class AutoLoaderTest extends \PHPUnit_Framework_TestCase {
	
	private $modules = '/tmp/Modules';
	private $module = '/tmp/Modules/Test';
	private $classes = '/tmp/Modules/Test/Classes';
	private $interfaces = '/tmp/Modules/Test/Classes/Interfaces';
	private $class = '/tmp/Modules/Test/Classes/MyClass.php';
	private $interfaceClass = '/tmp/Modules/Test/Classes/MyInterfaceClass.php';
	private $interface = '/tmp/Modules/Test/Classes/Interfaces/MyInterface.php';
	private $object;

	function setUp() {
		$this->object = new AutoLoader();	
		@mkdir($this->modules);
		@mkdir($this->module);
		@mkdir($this->classes);
		@mkdir($this->interfaces);
		file_put_contents($this->class, '<?php namespace Test; class MyClass {} ?>');
		file_put_contents($this->interfaceClass, '<?php namespace Test; class MyInterfaceClass implements MyInterface {} ?>');
		file_put_contents($this->interface, '<?php namespace Test; interface MyInterface {} ?>');
	}

	function tearDown() {
		@exec('rm -rf ' . $this->modules);
	}

	/**
	* @test
	*/
	public function files_exist_() {
		$this->assertFileExists($this->class);
		$this->assertFileExists($this->interfaceClass);
		$this->assertFileExists($this->interface);
	}

	/**
	* @test
	*/
	public function loader_can_be_created() {
		$this->assertInstanceOf('\Cool\AutoLoader', $this->object);
	}

	/**
	* @test
	*/
	public function bases_can_be_added() {
		$this->object->addBase('/one');
		$this->object->addBase('/two');
		$this->assertEquals(array('/one', '/two'), $this->object->getBases());
	}

	/**
	* @test
	*/
	public function interfaceclass_can_be_loaded() {
		$this->object->addBase($this->modules);
		$this->object->go();
		$this->assertTrue(class_exists('\\Test\\MyInterfaceClass', TRUE));
	}

	/**
	* @test
	*/
	public function interface_can_be_loaded() {
		$this->object->addBase($this->modules);
		$this->object->go();
		$this->assertTrue(interface_exists('\\Test\\MyInterface', TRUE));
	}

}

?>

