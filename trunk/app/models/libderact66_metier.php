<?php
	class Libderact66Metier extends AppModel
	{
		public $name = 'Libderact66Metier';

		public $useTable = 'codesromemetiersdsps66';

		public $displayField = 'intitule';

		public $virtualFields = array(
			'intitule' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."code" || \'. \' || "%s"."name" )'
			),
		);
	}
?>