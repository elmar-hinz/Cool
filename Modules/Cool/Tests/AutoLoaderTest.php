<?php namespace Cool;

require_once(__DIR__.'/../Classes/AutoLoader.php');

class AutoLoaderTest extends \PHPUnit_Framework_TestCase {
	
	private $modules = '/tmp/Modules';
	private $module = '/tmp/Modules/Test';
	private $classes = '/tmp/Modules/Test/Classes';
	private $interfaces = '/tmp/Modules/Test/Interfaces';
	private $signals  = '/tmp/Modules/Test/Signals';
	private $receivers  = '/tmp/Modules/Test/Receivers';
	private $class = '/tmp/Modules/Test/Classes/MyClass.php';
	private $interfaceClass = '/tmp/Modules/Test/Classes/MyInterfaceClass.php';
	private $interface = '/tmp/Modules/Test/Interfaces/MyInterface.php';
	private $signal = '/tmp/Modules/Test/Signals/MySignal.php';
	private $receiver = '/tmp/Modules/Test/Receivers/MyReceiver.php';
	private $object;

	function setUp() {
		$this->object = new AutoLoader();	
		@mkdir($this->modules);
		@mkdir($this->module);
		@mkdir($this->classes);
		@mkdir($this->interfaces);
		@mkdir($this->signals);
		@mkdir($this->receivers);
		file_put_contents($this->class, '<?php namespace Test; class MyClass {} ?>');
		file_put_contents($this->interfaceClass, '<?php namespace Test; class MyInterfaceClass implements MyInterface {} ?>');
		file_put_contents($this->interface, '<?php namespace Test; interface MyInterface {} ?>');
		file_put_contents($this->signal, '<?php namespace Test; class MySignal {} ?>');
		file_put_contents($this->receiver, '<?php namespace Test; class MyReceiver {} ?>');
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
		$this->assertFileExists($this->signal);
		$this->assertFileExists($this->receiver);
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
	public function interfaceClass_can_be_loaded() {
		$this->object->addModuleBase($this->modules);
		$this->object->go();
		$this->assertTrue(class_exists('\\Test\\MyInterfaceClass', TRUE));
	}

	/**
	* @test
	*/
	public function interface_can_be_loaded() {
		$this->object->addModuleBase($this->modules);
		$this->object->go();
		$this->assertTrue(interface_exists('\\Test\\MyInterface', TRUE));
	}

	/**
	* @test
	*/
	public function signal_can_be_loaded() {
		$this->object->addModuleBase($this->modules);
		$this->object->go();
		$this->assertTrue(class_exists('\\Test\\MySignal', TRUE));
	}

	/**
	* @test
	*/
	public function receiver_can_be_loaded() {
		$this->object->addModuleBase($this->modules);
		$this->object->go();
		$this->assertTrue(class_exists('\\Test\\MyReceiver', TRUE));
	}

}

?>

