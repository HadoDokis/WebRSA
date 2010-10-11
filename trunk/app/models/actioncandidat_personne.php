<?php
	class ActioncandidatPersonne extends AppModel
	{
		public $name = 'ActioncandidatPersonne';

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
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
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => 'rendezvous_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $actsAs = array (
			'Nullable',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'enattente' => array(
						'values' => array( 'O', 'N' )
					),
					'bilanvenu' => array(
						'values' => array( 'VEN', 'NVE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanretenu' => array(
						'values' => array( 'RET', 'NRE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanrecu' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'presencecontrat' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'pieceallocataire' => array(
						'values' => array( 'CER', 'NCA', 'CV', 'AUT' ),
						'domain' => 'actioncandidat_personne'
					),
					'integrationaction' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					)
				)
			),
			'Formattable'
		);


		public $validate = array(
			'personne_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'referent_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'actioncandidat_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'enattente'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanvenu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanretenu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanrecu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'integrationaction'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'pieceallocataire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'ddaction' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'motifdemande' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
		);
	}
?>