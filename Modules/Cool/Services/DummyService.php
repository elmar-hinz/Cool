<?php namespace Cool;

class DummyService implements Service {
	static public function canServe($mixedCriteria) {
		return TRUE;
	}
}


?>
