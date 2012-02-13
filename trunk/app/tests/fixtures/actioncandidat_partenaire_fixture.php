<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ActioncandidatPartenaireFixture extends CakeAppTestFixture {
		var $name = 'ActioncandidatPartenaire';
		var $table = 'actionscandidats_partenaires';
		var $import = array( 'table' => 'actionscandidats_partenaires', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'actioncandidat_id' => '1',
				'partenaire_id' => '1',
			),
		);
	}

?>
