<?php
	class DetailressourcemensuelleRessourcemensuelle extends AppModel
	{
		public $name = 'DetailressourcemensuelleRessourcemensuelle';

		public $validate = array(
			'detailressourcemensuelle_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
			'ressourcemensuelle_id' => array(
				'numeric' => array(
					'rule' => array('numeric')
				),
			),
		);

		public $belongsTo = array(
			'Detailressourcemensuelle' => array(
				'className' => 'Detailressourcemensuelle',
				'foreignKey' => 'detailressourcemensuelle_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Ressourcemensuelle' => array(
				'className' => 'Ressourcemensuelle',
				'foreignKey' => 'ressourcemensuelle_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>