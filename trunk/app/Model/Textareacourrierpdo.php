<?php
	class Textareacourrierpdo extends AppModel
	{
		public $name = 'Textareacourrierpdo';

		public $actsAs = array(
			'Enumerable',
			'Autovalidate'
		);

		public $belongsTo = array(
			'Courrierpdo' => array(
				'className' => 'Courrierpdo',
				'foreignKey' => 'courrierpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Contenutextareacourrierpdo' => array(
				'className' => 'Contenutextareacourrierpdo',
				'foreignKey' => 'textareacourrierpdo_id',
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