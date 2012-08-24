<?php
	class Chargeinsertion extends User
	{
		public $name = 'Chargeinsertion';

		public $displayField = 'nom_complet';

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			)
		);
	}
?>