<?php
	class Ajoutdossier extends AppModel
	{
		public $name = 'Ajoutdossier';

		public $useTable = false;

		public $validate = array(
			'serviceinstructeur_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);
	}
?>