<?php

	class RessourceRessourcemensuelleFixture extends CakeTestFixture {
		var $name = 'RessourceRessourcemensuelle';
		var $table = 'ressources_ressourcesmensuelles';
		var $import = array( 'table' => 'ressources_ressourcesmensuelles', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'ressourcemensuelle_id' => '1',
				'ressource_id' => '1',
				'id' => '1',
			),
		);
	}

?>
