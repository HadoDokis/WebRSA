<?php
	if( CAKE_BRANCH != '1.2' ) {
		App::uses( 'User', 'Model' );
	}

	class Chargeinsertion extends User
	{
		public $name = 'Chargeinsertion';

		public $displayField = 'nom_complet';

		public $useTable = 'users';

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			)
		);
	}
?>