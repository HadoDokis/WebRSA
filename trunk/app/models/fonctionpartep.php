<?php
	class Fonctionpartep extends AppModel
	{
		public $name = 'Fonctionpartep';

		public $actsAs = array(
			'Autovalidate'
		);

		public $hasMany = array(
			'Partep'
		);
	}
?>