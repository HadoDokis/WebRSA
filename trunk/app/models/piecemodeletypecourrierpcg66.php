<?php
	class Piecemodeletypecourrierpcg66 extends AppModel
	{
		public $name = 'Piecemodeletypecourrierpcg66';

		public $order = 'Piecemodeletypecourrierpcg66.name ASC';

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Modeletypecourrierpcg66' => array(
				'className' => 'Modeletypecourrierpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasAndBelongsToMany = array(
			'Modeletraitementpcg66' => array(
				'className' => 'Modeletraitementpcg66',
				'joinTable' => 'mtpcgs66_pmtcpcgs66',
				'foreignKey' => 'piecemodeletypecourrierpcg66_id',
				'associationForeignKey' => 'modeletraitementpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Mtpcg66Pmtcpcg66'
			)
		);    
	}
?>
