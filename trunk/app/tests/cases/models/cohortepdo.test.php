<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Cohortepdo');

	class CohortepdoTestCase extends CakeAppModelTestCase {

		/*
		//function search( $statutValidationAvis, $mesCodesInsee, $filtre_zone_geo, $criterespdo, $lockedDossiers )
		function testSearch() {

			$criteres = array('typepdo_id', 'decisionpdo_id', 'motifpdo', 'datedecisionpdo', 'matricule', 'numcomptt', 'user_id', 'daterevision');

			$result = $this->Cohortepdo->search(null, null, null, null, null);
			$this->assertTrue($result);
			$result = $this->Cohortepdo->search(null, null, null, null, $criteres);
			$this->assertTrue($result);

			$result = $this->Cohortepdo->search('Decisionpdo::valide', null, null, null, null);
			$this->assertTrue($result);
			$result = $this->Cohortepdo->search('Decisionpdo::valide', null, null, null, $criteres);
			$this->assertTrue($result);

			$result = $this->Cohortepdo->search('Decisionpdo::nonvalide', null, null, null, null);
			$this->assertTrue($result);
			$result = $this->Cohortepdo->search('Decisionpdo::nonvalide', null, null, null, $criteres);
			$this->assertTrue($result);
		}
		*/
	}

?>
