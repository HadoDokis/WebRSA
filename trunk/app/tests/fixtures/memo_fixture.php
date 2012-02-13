<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class MemoFixture extends CakeAppTestFixture {
		var $name = 'Memo';
		var $table = 'memos';
		var $import = array( 'table' => 'memos', 'connection' => 'default', 'records' => false);
		var $records = array(
		);
	}

?>