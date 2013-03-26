<?php namespace Cool;


class ReceiverFindeTest extends \PHPUnit_Framework_TestCase {

	private $sut;

	public function setUp() {
		require_once(__DIR__.'/../../Cool/Classes/LoadTestHelper.php');
		\Cool\LoadTestHelper::loadAll();
		$this->sut = new ReceiverFinder();

		// TestSignal
		$createSignal = 'namespace Cool;
			class TestSignal implements Signal {
				static function send($object, $mixedData = NULL) {}
				function getSender(){}
				function getData(){}
			}
		';
		if(!class_exists('\Cool\TestSignal')) eval($createSignal);

		// TestSignalForeign
		$createSignalForeign = 'namespace Cool;
			class TestSignalForeign implements Signal {
				static function send($object, $mixedData = NULL) {}
				function getSender(){}
				function getData(){}
			}
		';
		if(!class_exists('\Cool\TestSignalForeign')) eval($createSignalForeign);

		assert(class_exists('\Cool\TestSignal'));
		assert(class_exists('\Cool\TestSignalForeign'));

		// Receiver A
		$createA = 'namespace Cool;
			class TestReceiverA implements Receiver {
				static function listensTo() { return "\Cool\TestSignal"; }
				function receive(\Cool\Signal $signal) { }
			}
		';
		if(!class_exists('\Cool\TestReceiverA')) eval($createA);

		// Receiver B
		$createB = 'namespace Cool;
			class TestReceiverB implements Receiver {
				static function listensTo() { return "\Cool\TestSignal"; }
				function receive(Signal $signal) { }
			}
		';
		if(!class_exists('\Cool\TestReceiverB')) eval($createB);

		// Foreign Receiver
		$createF = 'namespace Cool;
			class TestReceiverF implements Receiver {
				static function listensTo() { return "\Cool\TestSignalForeign"; }
				function receive(Signal $signal) { }
			}
		';
		if(!class_exists('\Cool\TestReceiverF')) eval($createF);

		assert(class_exists('\Cool\TestReceiverA'));
		assert(class_exists('\Cool\TestReceiverB'));
		assert(class_exists('\Cool\TestReceiverF'));
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
		$this->assertContains('Cool\TestReceiverA', $receivers);
		$this->assertContains('Cool\TestReceiverB', $receivers);
		$this->assertNotContains('Cool\TestReceiverF', $receivers);
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

