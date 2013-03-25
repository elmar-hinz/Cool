<?php namespace Cool;


class ReceiverFindeTest extends \PHPUnit_Framework_TestCase {

	private $sut;

	public function setUp() {
		require_once(__DIR__.'/../../Cool/Classes/LoadTestHelper.php');
		\Cool\LoadTestHelper::loadAll();
		$this->sut = new ReceiverFinder();
		// Receiver A
		$createA = '
			class TestReceiverA implements \Cool\Receiver {
				static function listensTo() { return "\Cool\TestSignal"; }
				function receive($signal) { }
			}
		';
		if(!class_exists('\TestReceiverA')) eval($createA);
		// Receiver B
		$createB = '
			class TestReceiverB implements \Cool\Receiver {
				static function listensTo() { return "\Cool\TestSignal"; }
				function receive($signal) { }
			}
		';
		if(!class_exists('\TestReceiverB')) eval($createB);

		// Foreign Receiver
		$createF = '
			class TestReceiverF implements \Cool\Receiver {
				static function listensTo() { return "\Cool\TestSignalForeign"; }
				function receive($signal) { }
			}
		';
		if(!class_exists('\TestReceiverF')) eval($createF);

		class_exists('\TestReceiverA');
		class_exists('\TestReceiverB');
		class_exists('\TestReceiverF');
	}

	/**
	* @test
	*/
	public function the_constructor_works() {
		$this->assertInstanceOf('\Cool\ReceiverFinder', $this->sut);
		$this->assertInstanceOf('\Cool\Singleton', $this->sut);
	}

	/**
	* @test
	*/
	public function getReceiversForSignal_works() {
		$receivers = $this->sut->getReceiversForSignal('\Cool\TestSignal');
		$this->assertContains('TestReceiverA', $receivers);
		$this->assertContains('TestReceiverB', $receivers);
		$this->assertNotContains('TestReceiverF', $receivers);
	}

	/**
	* @test
	*/
	public function getReceiversForSignal_returns_empty_array_if_no_signal() {
		$receivers = $this->sut->getReceiversForSignal('\No\Siganl');
		$this->assertEmpty($receivers);
	}

}

?>

