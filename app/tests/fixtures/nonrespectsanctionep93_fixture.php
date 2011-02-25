<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class Nonrespectsanctionep93Fixture extends CakeAppTestFixture {
		var $name = 'Nonrespectsanctionep93';
		var $table = 'nonrespectssanctionseps93';
		var $import = array( 'table' => 'nonrespectssanctionseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossierep_id' => '1',
				'propopdo_id' => null,
				'orientstruct_id' => '1001',
				'contratinsertion_id' => '10',
				'origine' => 'orientstruct',
				'decision' => null,
				'rgpassage' => '1',
				'montantreduction' => null,
				'dureesursis' => null,
				'sortienvcontrat' => 0,
				'active' => 1,
				'created' => '2010-11-04',
				'modified' => '2010-11-04',
			),
			array(
				'id' => '2',
				'dossierep_id' => '2',
				'propopdo_id' => null,
				'orientstruct_id' => '2002',
				'contratinsertion_id' => '11',
				'origine' => 'orientstruct',
				'decision' => null,
				'rgpassage' => '1',
				'montantreduction' => null,
				'dureesursis' => null,
				'sortienvcontrat' => 0,
				'active' => 1,
				'created' => '2010-11-04',
				'modified' => '2010-11-04',
			),
		);
	}

?>
