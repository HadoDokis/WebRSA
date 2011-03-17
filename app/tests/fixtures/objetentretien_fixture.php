<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ObjetentretienFixture extends CakeAppTestFixture {
		var $name = 'Objetentretien';
		var $table = 'objetsentretien';
		var $import = array( 'table' => 'objetsentretien', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'Objet d\'entretien 1',
				'modeledocument' => null,
			),
		);
	}

?>