<?php
	class PartepSeanceep extends AppModel
	{
		public $name = 'PartepSeanceep';

		public $actsAs = array(
			'Autovalidate'
		);

		public $belongsTo = array(
			'Partep',
			'Seanceep',
			'Partep'
		);
	}
?>