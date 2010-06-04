<?php
	class DemandereorientSeanceep extends AppModel
	{
		public $name = 'DemandereorientSeanceep';

		public $actsAs = array(
			'Autovalidate'
		);

		public $belongsTo = array(
			'Demandereorient',
			'Seanceep'
		);

		public $hasMany = array(
			'Decisionreorient'
		);
	}
?>