<?php namespace Cool;

class AbstractSignalTest extends \PHPUnit_Framework_TestCase {

	private $mixedData;
	private $sender;
	private $sut;

	public function setUp() {
		require_once(__DIR__.'/../../Cool/Classes/LoadTestHelper.php');
		\Cool\LoadTestHelper::loadAll();
		$this->sender = new \stdClass();
		$this->mixedData = array('one');
		$this->sut = $this->getMockForAbstractClass('\Cool\AbstractSignal', array($this->sender, $this->mixedData));
	}

	/**
	* @test
	*/
	public function signal_can_be_constructed() {
		$this->assertInstanceOf('\Cool\Signal', $this->sut);
	}

	/**
	* @test
	*/
	public function getData_works() {
		$this->assertSame($this->mixedData, $this->sut->getData());
	}

	/**
	* @test
	*/
	public function getSender_works() {
		$this->assertSame($this->sender, $this->sut->getSender());
	}

	/**
	* @test
	*/
	public function getTransmitter_returns_signalton() {
		$rfm = new \ReflectionMethod('\Cool\AbstractSignal', 'getTransmitter'); 
		$rfm->setAccessible(TRUE);
		$t1 = $rfm->invoke(NULL);
		$t2 = $rfm->invoke(NULL);
		$this->assertSame($t1, $t2);
		$this->assertInstanceOf('\Cool\Transmitter', $t1);
	}

	/**
	* @test
	*/
	public function send_works() {
		$this->markTestIncomplete();	
		// didn't work with php 5.3.3
		/*
		$class = get_class($this->sut);
		$rfm = new \ReflectionMethod($class, 'send'); 
		$receivers = $rfm->invoke(NULL, $this->sender, $this->mixedData);
		$this->assertInstanceOf('\Cool\Receivers', $receivers);
		*/
	}

}
?>


