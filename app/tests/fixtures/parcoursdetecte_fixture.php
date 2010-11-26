<?php

	class ParcoursdetecteFixture extends CakeTestFixture {
		var $name = 'Parcoursdetecte';
		var $table = 'parcoursdetectes';
		var $import = array( 'table' => 'parcoursdetectes', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'orientstruct_id' => '1',
				'signale' => null,
				'commentaire' => null,
				'created' => '2009-01-02',
				'datetransref' => null,
				'ep_id' => null,
				'osnv_id' => '1',
			)
		);
	}

?>
