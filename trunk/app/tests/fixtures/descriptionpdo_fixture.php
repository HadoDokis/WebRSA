<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class DescriptionpdoFixture extends CakeAppTestFixture {
		var $name = 'Descriptionpdo';
		var $table = 'descriptionspdos';
		var $import = array( 'table' => 'descriptionspdos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>