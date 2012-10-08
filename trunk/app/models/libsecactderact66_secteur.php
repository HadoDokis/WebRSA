<?php
	class Libsecactderact66Secteur extends AppModel
	{
		public $name = 'Libsecactderact66Secteur';

		public $displayField = 'intitule';

		public $useTable = 'codesromesecteursdsps66';

		public $virtualFields = array(
			'intitule' => array(
				'type'      => 'string',
				'postgres'  => '( "%s"."code" || \'. \' || "%s"."name" )'
			),
		);
	}
?>