<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PartenaireFixture extends CakeAppTestFixture {
		var $name = 'Partenaire';
		var $table = 'partenaires';
		var $import = array( 'table' => 'partenaires', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'libstruc' => 'libstruct?',
				'numvoie' => '12',
				'typevoie' => 'R',
				'nomvoie' => 'Republique',
				'compladr' => 'compladr?',
				'numtel' => null,
				'numfax' => null,
				'email' => null,
				'codepostal' => '34000',
				'ville' => 'Montpellier',
			)
		);
	}

?>
