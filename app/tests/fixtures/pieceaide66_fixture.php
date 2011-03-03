<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Pieceaide66Fixture extends CakeAppTestFixture {
		var $name = 'Pieceaide66';
		var $table = 'piecesaides66';
		var $import = array( 'table' => 'piecesaides66', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'name' => 'pieceaide66libelle1',
			),
			array(
				'id' => '2',
				'name' => 'pieceaide66libelle2',
			),
		);
	}

?>
