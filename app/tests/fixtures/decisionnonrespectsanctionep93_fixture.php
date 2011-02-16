<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Decisionnonrespectsanctionep93Fixture extends CakeAppTestFixture {
		var $name = 'Decisionnonrespectsanctionep93';
		var $table = 'decisionsnonrespectssanctionseps93';
		var $import = array( 'table' => 'decisionsnonrespectssanctionseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'nonrespectsanctionep93_id' => '1',
				'etape' => 'cg',
				'decision' => null,
				'montantreduction' => null,
				'dureesursis' => null,
				'commentaire' => null,
				'created' => null,
				'modified' => null,
			)
		);
	}

?>
