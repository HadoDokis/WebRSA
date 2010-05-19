<?php

	class AvispcgpersonneFixture extends CakeTestFixture {
		var $name = 'Avispcgpersonne';
		var $table = 'avispcgpersonnes';
		var $import = array( 'table' => 'avispcgpersonnes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'personne_id' => '1',
				'avisevaressnonsal' => null,
				'dtsouressnonsal' => null,
				'dtevaressnonsal' => null,
				'mtevalressnonsal' => null,
				'excl' => null,
				'ddexcl' => null,
				'dfexcl' => null,
			),
		);
	}

?>
