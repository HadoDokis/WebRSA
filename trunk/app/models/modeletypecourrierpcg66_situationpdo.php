<?php
	class Modeletypecourrierpcg66Situationpdo extends AppModel
	{
		public $name = 'Modeletypecourrierpcg66Situationpdo';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $belongsTo = array(
			'Modeletypecourrierpcg66' => array(
				'className' => 'Modeletypecourrierpcg66',
				'foreignKey' => 'modeletypecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Situationpdo' => array(
				'className' => 'Situationpdo',
				'foreignKey' => 'situationpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

// 		public $hasMany = array(
// 			'Traitementpcg66' => array(
// 				'className' => 'Traitementpcg66',
// 				'foreignKey' => 'personnepcg66_situationpdo_id',
// 				'dependent' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'exclusive' => '',
// 				'finderQuery' => '',
// 				'counterQuery' => ''
// 			)
// 		);
	}
?>