<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ComiteapreParticipantcomiteFixture extends CakeAppTestFixture {
		var $name = 'ComiteapreParticipantcomite';
		var $table = 'comitesapres_participantscomites';
		var $import = array( 'table' => 'comitesapres_participantscomites', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'comiteapre_id' => '1',
				'participantcomite_id' => '1',
				'presence' => null,
			),
		);
	}

?>
