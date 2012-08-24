<?php
	class Secretaire extends User
	{
		public $name = 'Secretaire';

		public $displayField = 'nom_complet';

		public $virtualFields = array(
			'nom_complet' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			)
		);
	}
?>