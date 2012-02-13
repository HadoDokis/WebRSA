<?php
	class PropopdoStatutdecisionpdo extends AppModel
	{
		public $name = 'PropopdoStatutdecisionpdo';

		public $actsAs = array (
			'Nullable',
			'ValidateTranslate'
		);

		public $validate = array(
			'propopdo_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'statutdecisionpdo_id' => array(
				array( 'rule' => 'notEmpty' )
			)
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Statutdecisionpdo' => array(
				'className' => 'Statutdecisionpdo',
				'foreignKey' => 'statutdecisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>