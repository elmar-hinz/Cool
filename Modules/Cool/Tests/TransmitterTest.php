<?php namespace Cool;

class TransmitterTest extends \PHPUnit_Framework_TestCase {

	private $signal;
	private $receiverA;
	private $receiverB;
	private $receiverFinder;
	private $receiverList = array('TestReceiverA', 'TestReceiverB');
	private $receiverIterator;
	private $receiversTemplate;
	private $sut;

	public function setUp() {
		// Load classes
		require_once(__DIR__.'/../../Cool/Classes/LoadTestHelper.php');
		\Cool\LoadTestHelper::loadAll();

		// Receiver A
		$createA = '
			class TestReceiverA implements \Cool\Receiver {
				static function listensTo() { return "\Cool\TestSignal"; }
				function go($sender, $data = NULL) { }
			}
		';
		if(!class_exists('\TestReceiverA')) eval($createA);

		// Receiver B
		$createB = '
			class TestReceiverB implements \Cool\Receiver {
				static function listensTo() { return "\Cool\TestSignal"; }
				function go($sender, $data = NULL) { }
			}
		';
		if(!class_exists('\TestReceiverB')) eval($createB);

		// Mock Receivers
		$this->receiverA = $this->getMock('TestReceiverA');
		$this->receiverB = $this->getMock('TestReceiverB');

		// Mock the signal
		$this->signal = $this->getMock('\Cool\Signal');

		// Mock the receiverFinder
		$this->receiverFinder = $this->getMock('\Cool\ReceiverFinder');
		$this->receiverFinder->expects($this->any())->method('getReceiversForSignal')
			->will($this->returnValue($this->receiverList));

		// Prep an iterator to return, that contains the mocked receivers
		$this->receiverIterator = new \ArrayIterator();
		$this->receiverIterator->append($this->receiverA);
		$this->receiverIterator->append($this->receiverB);

		// Mock the receivers iterable 
		$this->receiversTemplate  = $this->getMock('\Cool\Receivers');
		$this->receiversTemplate->expects($this->any())->method('getIterator')
			->will($this->returnValue($this->receiverIterator));

		// Compose the system under test
		$this->sut  = new Transmitter($this->receiverFinder, $this->receiversTemplate);
	}

	/**
	* @test
	*/
	public function can_be_constructed() {
		$this->assertInstanceOf('\Cool\Transmitter', $this->sut);
	}

	/**
	* @test
	*/
	public function broadcast_calls_getReceiverForSignal_once_with_the_className_of_Signal() {
		$list = array('TestReceiverA', 'TestReceiverB');
		$this->receiverFinder->expects($this->once())
			->method('getReceiversForSignal')
			->with(get_class($this->signal))
			->will($this->returnValue($list));
		$receivers = $this->sut->broadcast($this->signal);
	}

	/**
	* @test
	*/
	public function broadcast_clones_receiversTemplate() {
		$receivers = $this->sut->broadcast($this->signal);
		$this->assertEquals($receivers, $this->receiversTemplate);
		$this->assertNotSame($receivers, $this->receiversTemplate);
	}


	/**
	* @test
	*/
	public function broadcast_adds_receivers() {
		$this->receiversTemplate->expects($this->exactly(2))->method('addReceiver')
		->with( $this->logicalOr(
				$this->isInstanceOf('\TestReceiverA'), 
				$this->isInstanceOf('\TestReceiverB')
			));
		$this->sut->broadcast($this->signal);
	}

	/**
	* @test
	*/
	public function broadcast_calls_receive_on_receivers() {
		$this->receiverA
			->expects($this->once())->method('receive')->with($this->identicalTo($this->signal));
		$this->receiverB
			->expects($this->once())->method('receive')->with($this->identicalTo($this->signal));
		$receivers = $this->sut->broadcast($this->signal);
	}

	/**
	* @test
	*/
	public function broadcast_returns_receivers() {
		$receivers = $this->sut->broadcast($this->signal);
		$this->assertInstanceOf('\Cool\Receivers', $receivers);
		$this->assertContainsOnlyInstancesOf('\Cool\Receiver', $receivers);
	}

}

?>
