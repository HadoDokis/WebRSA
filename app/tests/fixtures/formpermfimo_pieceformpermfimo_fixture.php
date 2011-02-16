<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class FormpermfimoPieceformpermfimoFixture extends CakeAppTestFixture {
		var $name = 'FormpermfimoPieceformpermfimo';
		var $table = 'formspermsfimo_piecesformspermsfimo';
		var $import = array( 'table' => 'formspermsfimo_piecesformspermsfimo', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'formpermfimo_id' => '1',
				'pieceformpermfimo_id' => '1',
			),
		);
	}

?>
