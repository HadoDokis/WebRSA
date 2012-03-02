<?php
	class Piecetypecourrierpcg66 extends AppModel
	{
		public $name = 'Piecetypecourrierpcg66';

		public $order = 'Piecetypecourrierpcg66.name ASC';

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
                    'Piecetraitementpcg66' => array(
                            'className' => 'Piecetraitementpcg66',
                            'foreignKey' => 'piecetypecourrierpcg66_id',
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
