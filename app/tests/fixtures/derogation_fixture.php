<?php

	class DerogationFixture extends CakeTestFixture {
		var $name = 'Derogation';
		var $table = 'derogations';
		var $import = array( 'table' => 'derogations', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'avispcgpersonne_id' => '1',
				'typedero' => null,
				'avisdero' => null,
				'ddavisdero' => null,
				'dfavisdero' => null,
			),
		);
	}

?>
