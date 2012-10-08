<?php
	class OrientstructServiceinstructeur extends AppModel
	{
		public $name = 'OrientstructServiceinstructeur';

		public $validate = array(
			'orientstruct_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'serviceinstructeur_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>