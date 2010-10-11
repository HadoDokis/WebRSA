<?php
	class Reducrsa extends AppModel
	{
		public $name = 'Reducrsa';

		public $validate = array(
			'avispcgdroitrsa_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'avispcgdroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>