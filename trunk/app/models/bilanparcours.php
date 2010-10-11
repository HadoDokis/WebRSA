<?php
	class Bilanparcours extends AppModel
	{
		public $name = 'Bilanparcours';

		public $actsAs = array(
			'Formattable' => array(
				'suffix' => array(
					'referent_id'
				)
			),
			'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'accordprojet',
					'maintienorientsansep',
					'choixparcours',
					'maintienorientsansep',
					'changementrefsansep',
					'maintienorientparcours',
					'changementrefparcours',
					'reorientation',
					'examenaudition',
					'maintienorientavisep',
					'changementrefeplocale',
					'reorientationeplocale',
					'typeeplocale',
					'decisioncommission',
					'decisioncoordonnateur',
					'decisioncga'
				)
			)
		);

		public $validate = array(
			'personne_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'referent_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'structurereferente_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'rendezvous_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			/*'NvsansepReferent' => array(
				'className' => 'NvsansepReferent',
				'foreignKey' => 'nvsansep_referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'NvparcoursReferent' => array(
				'className' => 'NvparcoursReferent',
				'foreignKey' => 'nvparcours_referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)*/
		);
	}
?>
