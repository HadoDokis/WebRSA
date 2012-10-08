<?php
	class Aideagricole extends AppModel
	{
		public $name = 'Aideagricole';

		public $validate = array(
			'infoagricole_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Infoagricole' => array(
				'className' => 'Infoagricole',
				'foreignKey' => 'infoagricole_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>