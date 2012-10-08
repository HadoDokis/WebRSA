<?php
	class Typecourrierpcg66 extends AppModel
	{
		public $name = 'Typecourrierpcg66';

		public $order = 'Typecourrierpcg66.name ASC';

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
                
		public $hasOne = array(
			'Traitementpcg66' => array(
				'className' => 'Traitementpcg66',
				'foreignKey' => 'typecourrierpcg66_id',
				'dependent' => true,
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
                
		public $hasMany = array(
			'Modeletypecourrierpcg66' => array(
				'className' => 'Modeletypecourrierpcg66',
				'foreignKey' => 'typecourrierpcg66_id',
				'dependent' => true,
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
