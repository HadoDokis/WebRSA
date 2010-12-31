<?php
	class Regroupementep extends AppModel
	{
		public $name = 'Regroupementep';

		public $order = array( 'Regroupementep.name ASC' );

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate'
		);

		public $hasMany = array(
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'regroupementep_id',
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