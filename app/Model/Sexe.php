<?php
	class Sexe extends AppModel
	{
		public $name = 'Sexe';
		public $useTable = false;

		public $options = array(
			'1' => 'Homme',
			'2' => 'Femme'
		);
	}
?>