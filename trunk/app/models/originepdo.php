<?php
	class Originepdo extends AppModel
	{
		public $name = 'Originepdo';

		public $displayField = 'libelle';

		public $actsAs = array(
			'ValidateTranslate'
		);

		public $hasMany = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'originepdo_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
	}
?>