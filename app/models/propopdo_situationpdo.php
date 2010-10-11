<?php
	class PropopdoSituationpdo extends AppModel
	{
		public $name = 'PropopdoSituationpdo';

		public $actsAs = array (
			'Nullable',
			'ValidateTranslate'
		);

		public $validate = array(
			'propopdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'situationpdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Situationpdo' => array(
				'className' => 'Situationpdo',
				'foreignKey' => 'situationpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>