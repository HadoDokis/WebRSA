<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Dsp');

	class DspTestCase extends CakeAppModelTestCase {
		//test fonction filteroption
		function testFilterOptions() {
			$result = $this->Dsp->filterOptions('cg58', null);
			$this->assertNull($result);

			$result = $this->Dsp->filterOptions('cg93', null);
			$this->assertNull($result);

			/*
			$result = $this->Dsp->filterOptions('toto', 1);
			$this->assertNull($result);

			$result = $this->Dsp->filterOptions('toto', 1337);
			$this->assertNull($result);

			$result = $this->Dsp->filterOptions('toto', -42);
			$this->assertNull($result);
			*/
		}
	}

?>
