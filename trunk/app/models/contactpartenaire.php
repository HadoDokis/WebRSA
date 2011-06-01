<?php
	class Contactpartenaire extends AppModel
	{
		public $name = 'Contactpartenaire';

		public $displayField = 'nom_candidat';

		public $actsAs = array(
			'ValidateTranslate',
		);

		public $validate = array(
			'qual' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'nom' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'prenom' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
			'partenaire_id' => array(
				array(
					'rule' => array('notEmpty'),
				),
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
        public $hasMany = array(
            'Actioncandidat' => array(
                'className' => 'Actioncandidat',
                'foreignKey' => 'contactpartenaire_id',
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



        public $virtualFields = array(
            'nom_candidat' => array(
                'type'      => 'string',
                'postgres'  => '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
            )
        );
//		public $hasAndBelongsToMany = array(
//			'Partenaire' => array(
//				'className' => 'Partenaire',
//				'joinTable' => 'contactspartenaires_partenaires',
//				'foreignKey' => 'contactpartenaire_id',
//				'associationForeignKey' => 'partenaire_id',
//				'unique' => true,
//				'conditions' => '',
//				'fields' => '',
//				'order' => '',
//				'limit' => '',
//				'offset' => '',
//				'finderQuery' => '',
//				'deleteQuery' => '',
//				'insertQuery' => '',
//				'with' => 'ContactpartenairePartenaire'
//			)
//		);
		
		
	}
?>
