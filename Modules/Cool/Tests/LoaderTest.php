<?php namespace Cool;

require_once(__DIR__.'/../Classes/Loader.php');

class LoaderTest extends \PHPUnit_Framework_TestCase {
	
	private $dir1 = '/tmp/dummy1';
	private $file1 = '/tmp/dummy1/Dummy1.php';
	private $dir2 = '/tmp/dummy1/dummy2/';
	private $file2 = '/tmp/dummy1/dummy2/Dummy2.php';

	function setUp() {
		mkdir($this->dir1);
		mkdir($this->dir2);
		touch($this->file1);
		touch($this->file2);
		file_put_contents($this->file1, '<?php $GLOBALS["dummy1"] = 1; ?>');
		file_put_contents($this->file2, '<?php $GLOBALS["dummy2"] = "two"; ?>');
	}

	function tearDown() {
		unlink($this->file2);
		unlink($this->file1);
		rmdir($this->dir2);
		rmdir($this->dir1);
	}

	/**
	* @test
	*/
	public function includer_can_be_created() {
		$this->assertInstanceOf('\Cool\Loader', new Loader());
	}

	/**
	* @test
	*/
	public function pathes_can_be_added() {
		$includer = new Loader();
		$includer->addPath($this->dir1);
		$includer->addPath($this->dir2);
		$expect = array($this->dir1, $this->dir2);
		$this->assertEquals($expect, $includer->getPathes());
	}

	/**
	* @test
	*/
	public function files_are_recursively_included() {
		$includer = new Loader();
		$includer->addPath($this->dir1);
		$includer->run();
		$this->assertEquals(1, $GLOBALS['dummy1']);
		$this->assertEquals('two', $GLOBALS['dummy2']);
	}

}

?>
