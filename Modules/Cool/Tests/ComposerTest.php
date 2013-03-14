<?php namespace Cool;

require_once(__DIR__.'/../Classes/Finder.php');
require_once(__DIR__.'/../Classes/Composer.php');

class ComposerTest extends \PHPUnit_Framework_TestCase {

	private $composer;

	public function setUp() {
		$this->composer = new Composer();
	}

	/**
	* @test
	*/
	public function instantiate_returns_an_object() {
		$o = $this->composer->instantiate('Cool\Composer');
		$this->assertInstanceOf('Cool\Composer', $o);
	}

}
?>
	

