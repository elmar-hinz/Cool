<?php

require_once(__DIR__.'/../Classes/Receivers.php');

class ReceiversTest extends \PHPUnit_Framework_TestCase {

	private $sut;
	private $receiver1;
	private $receiver2;

	public function setUp() {
		$this->sut = new \Cool\Receivers();
		$this->receiver1 = $this->getMock('\Cool\Receiver');
		$this->receiver2 = $this->getMock('\Cool\Receiver');
	}

	/**
	* @test
	*/
	public function receiversClass_exists() {
		$this->assertTrue(class_exists('\Cool\Receivers'));
	}

	/**
	* @test
	*/
	public function implemnts_IteratorAggregate() {
		$this->assertInstanceOf('\IteratorAggregate', $this->sut);
	}

	/**
	* @test
	*/
	public function receivers_can_be_added() {
		$this->sut->addReceiver($this->receiver1);
		$this->sut->addReceiver($this->receiver2);
	}

	/**
	* @test
	*/
	public function receivers_can_be_traversed() {
		$this->sut->addReceiver($this->receiver1);
		$this->sut->addReceiver($this->receiver2);
		$collection = array();
		foreach($this->sut->getIterator() as $receiver) {
			$this->assertInstanceOf('\Cool\Receiver', $receiver);
			$collection[] = $receiver;
		}
		$this->assertContains($this->receiver2, $collection);
		$this->assertContains($this->receiver1, $collection);
	}

}

?>

