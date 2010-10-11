<?php
	class Piecepermisb extends AppModel
	{
		public $name = 'Piecepermisb';

		public $displayField = 'libelle';

		public $validate = array(
			'libelle' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasAndBelongsToMany = array(
			'Permisb' => array(
				'className' => 'Permisb',
				'joinTable' => 'permisb_piecespermisb',
				'foreignKey' => 'piecepermisb_id',
				'associationForeignKey' => 'permisb_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'PermisbPiecepermisb'
			)
		);
	}
?>
