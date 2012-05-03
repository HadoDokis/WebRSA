<?php
	class Modeletypecourrierpcg66 extends AppModel
	{
		public $name = 'Modeletypecourrierpcg66';

		public $order = 'Modeletypecourrierpcg66.name ASC';

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
			'Typecourrierpcg66' => array(
				'className' => 'Typecourrierpcg66',
				'foreignKey' => 'typecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
                
		public $hasMany = array(
			'Piecemodeletypecourrierpcg66' => array(
				'className' => 'Piecemodeletypecourrierpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Modeletraitementpcg66' => array(
				'className' => 'Modeletraitementpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);
		
// 		public $hasAndBelongsToMany = array(
// 			// Test liaison avec situationspdos
// 			'Situationpdo' => array(
// 				'className' => 'Situationpdo',
// 				'joinTable' => 'modelestypescourrierspcgs66_situationspdos',
// 				'foreignKey' => 'modeletypecourrierpcg66_id',
// 				'associationForeignKey' => 'situationpdo_id',
// 				'unique' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'finderQuery' => '',
// 				'deleteQuery' => '',
// 				'insertQuery' => '',
// 				'with' => 'Modeletypecourrierpcg66Situationpdo'
// 			)
// 		);
	}
?>
