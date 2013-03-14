<?php namespace Cool;

class LoadTestHelper {
	
	static public function autoLoadAll() {
		require_once(__DIR__.'/../../Cool/Classes/AutoLoader.php');
		$l = new AutoLoader();
		$l->addBase(__DIR__.'/../..');
		$l->go();
	}

	static public function loadAll() {
		self::autoLoadAll();
		$l = new ActiveLoader();
		$l->addBase(__DIR__.'/../..');
		$l->go();
	}
}

?>
