<?php
	class PropopdoStatutpdo extends AppModel {
		public $name = 'PropopdoStatutpdo';

		public $actsAs = array (
			'Formattable',
			'ValidateTranslate'
		);

		public $validate = array(
			'propopdo_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'statutpdo_id' => array(
				array( 'rule' => 'notEmpty' )
			)
		);
		//The Associations below have been created with all possible keys, those that are not needed can be removed

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Statutpdo' => array(
				'className' => 'Statutpdo',
				'foreignKey' => 'statutpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>