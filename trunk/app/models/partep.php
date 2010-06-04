<?php
	class Partep extends AppModel
	{
		public $name = 'Partep';

		public $actsAs = array(
			'Autovalidate'
		);

		public $belongsTo = array(
			'Ep',
			'Fonctionpartep'
		);
	}
?>