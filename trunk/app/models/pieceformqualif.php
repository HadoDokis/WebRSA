<?php
	class Pieceformqualif extends AppModel
	{
		public $name = 'Pieceformqualif';

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
			'Formqualif' => array(
				'className' => 'Formqualif',
				'joinTable' => 'formsqualifs_piecesformsqualifs',
				'foreignKey' => 'pieceformqualif_id',
				'associationForeignKey' => 'formqualif_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'FormqualifPieceformqualif'
			)
		);
	}
?>
