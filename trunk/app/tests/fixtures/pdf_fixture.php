<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class PdfFixture extends CakeAppTestFixture {
		var $name = 'Pdf';
		var $table = 'pdfs';
		var $import = array( 'table' => 'pdfs', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'modele' => 'modele?',
				'modeledoc' => 'modeledoc?',
				'fk_value' => 1,
				'document' => 'document?',
				'created' => null,
				'modified' => null,
			)
		);
	}

?>
