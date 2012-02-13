<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PiecepdoFixture extends CakeAppTestFixture {
		var $name = 'Piecepdo';
		var $table = 'piecespdos';
		var $import = array( 'table' => 'piecespdos', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'propopdo_id' => '1',
				'libelle' => 'libellé',
				'dateajout' => null,
			)
		);
	}

?>
