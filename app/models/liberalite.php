<?php
	class Liberalite extends AppModel
	{
		public $name = 'Liberalite';

		public $validate = array(
			'avispcgpersonne_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Avispcgpersonne' => array(
				'className' => 'Avispcgpersonne',
				'foreignKey' => 'avispcgpersonne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>