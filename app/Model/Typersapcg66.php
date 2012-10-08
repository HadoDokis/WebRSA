<?php
	class Typersapcg66 extends AppModel
	{
		public $name = 'Typersapcg66';

		public $recursive = -1;

		public $actsAs = array(
			'Validation.Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $hasAndBelongsToMany = array(
			'Decisiondossierpcg66' => array(
				'className' => 'Decisiondossierpcg66',
				'joinTable' => 'decisionsdossierspcgs66_typesrsapcgs66',
				'foreignKey' => 'typersapcg66_id',
				'associationForeignKey' => 'decisiondossierpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Decisiondossierpcg66Typersapcg66'
			)
		);
	}
?>