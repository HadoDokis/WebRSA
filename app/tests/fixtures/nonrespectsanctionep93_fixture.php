<?php

	class Nonrespectsanctionep93Fixture extends CakeTestFixture {
		var $name = 'Nonrespectsanctionep93';
		var $table = 'nonrespectssanctionseps93';
		var $import = array( 'table' => 'nonrespectssanctionseps93', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'dossierep_id' => '1',
				'propopdo_id' => '1',
				'orientstruct_id' => '1',
				'contratinsertion_id' => '1',
				'origine' => 'origine',
				'decision' => null,
				'rgpassage' => '1',
				'montantreduction' => null,
				'dureesursis' => null,
				'sortienvcontrat' => false,
				'active' => true,
				'created' => null,
				'modified' => null,
			),
		);
	}

?>
