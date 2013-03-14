<?php namespace Cool;

require_once(__DIR__.'/../Classes/ActiveLoader.php');

class ActiveLoaderTest extends \PHPUnit_Framework_TestCase {
	
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
		$this->assertInstanceOf('\Cool\ActiveLoader', new ActiveLoader());
	}

	/**
	* @test
	*/
	public function bases_can_be_added() {
		$includer = new ActiveLoader();
		$includer->addBase($this->dir1);
		$includer->addBase($this->dir2);
		$expect = array($this->dir1, $this->dir2);
		$this->assertEquals($expect, $includer->getBases());
	}

	/**
	* @test
	*/
	public function files_are_recursively_included() {
		$includer = new ActiveLoader();
		$includer->addBase($this->dir1);
		$includer->go();
		$this->assertEquals(1, $GLOBALS['dummy1']);
		$this->assertEquals('two', $GLOBALS['dummy2']);
	}

}

?>
