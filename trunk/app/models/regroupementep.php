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

		// INFO: le behavior Autovalidate ne trouve pas les contraintes UNIQUE (17/02/2011)
		public $validate = array(
			'name' => array(
				array(
					'rule' => array( 'isUnique' ),
				)
			)
		);
	}
?>