<?php
	class Motifdemreorient extends AppModel
	{
		public $name = 'Motifdemreorient';

		public $actsAs = array(
			'Autovalidate'
		);

		public $hasMany = array(
			'Demandereorient'
		);
	}
?>