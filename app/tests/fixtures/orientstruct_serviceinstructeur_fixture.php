<?php

	class OrientstructServiceinstructeurFixture extends CakeTestFixture {
		var $name = 'OrientstructServiceinstructeur';
		var $table = 'orientsstructs_servicesinstructeurs';
		var $import = array( 'table' => 'orientsstructs_servicesinstructeurs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'orientstruct_id' => '1',
				'serviceinstructeur_id' => '1',
				'id' => '1',
			),
		);
	}

?>
