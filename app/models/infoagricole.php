<?php
	class Infoagricole extends AppModel
	{
		public $name = 'Infoagricole';

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
// 			'mtbenagri' => array(
// 				array(
// 					'rule' => 'notEmpty',
// 					'message' => 'Champ obligatoire'
// 				)
// 			),
// 			'dtbenagri' => array(
// 				array(
// 					'rule' => 'date',
// 					'message' => 'Veuillez vérifier le format de la date.'
// 				),
// 				array(
// 					'rule' => 'notEmpty',
// 					'message' => 'Champ obligatoire'
// 				)
// 			),
//
// 			'regfisagri' => array(
// 				array(
// 					'rule' => 'notEmpty',
// 					'message' => 'Champ obligatoire'
// 				)
// 			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Aideagricole' => array(
				'className' => 'Aideagricole',
				'foreignKey' => 'infoagricole_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
	}
?>