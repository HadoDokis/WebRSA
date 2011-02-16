<?php
	require_once( TESTS.'cake_app_test_fixture.php' );

	class ParticipantcomiteFixture extends CakeAppTestFixture {
		var $name = 'Participantcomite';
		var $table = 'participantscomites';
		var $import = array( 'table' => 'participantscomites', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'qual' => null,
				'nom' => null,
				'prenom' => null,
				'fonction' => null,
				'organisme' => null,
				'numtel' => null,
				'mail' => null,
			)
		);
	}

?>
