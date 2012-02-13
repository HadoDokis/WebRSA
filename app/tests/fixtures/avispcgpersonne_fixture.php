<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class AvispcgpersonneFixture extends CakeAppTestFixture {
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
			array(
				'id' => '2',
				'personne_id' => '4004',
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
