<?php
	class Apre66 extends AppModel
	{
		public $name = 'Apre66';

		public $displayField = 'numeroapre';

		public $useTable = 'apres';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
					'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
					'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
					'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
					'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
// 					'ajoutcomiteexamen' => array( 'type' => 'no', 'domain' => 'apre' ),
					'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' ),
					'eligibiliteapre' => array( 'type' => 'eligibiliteapre', 'domain' => 'apre' ),
// 					'presence' => array( 'type' => 'presence', 'domain' => 'apre' ),
					'justificatif' => array( 'type' => 'justificatif', 'domain' => 'apre' ),
					'isdecision' => array( 'domain' => 'apre' ),
					'etatdossierapre' => array( 'domain' => 'apre' ),
					'haspiecejointe' => array( 'domain' => 'apre' )
				)
			),
			'Frenchfloat' => array(
				'fields' => array(
					'montantaverser',
					'montantattribue',
					'montantdejaverse'
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			),
			'Gedooo'
		);

		public $validate = array(
			'activitebeneficiaire' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typedemandeapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'avistechreferent' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'secteurprofessionnel' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'montantaverser' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
			),
			'montantattribue' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
			),
			'structurereferente_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'datedemandeapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'referent_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			//Partie activité bénéficiaire
			'typecontrat' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire',
					'required' => false
				)
			),
// 			'dateentreeemploi' => array(
// 				array(
// 					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E', 'F' ) ),
// 					'message' => 'Champ obligatoire'
// 				)
// 			),

			'dureecontrat' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			/*'nbheurestravaillees' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),*/
			'nomemployeur' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'adresseemployeur' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'hascer' => array(
				array(
					'rule' => array('equalTo', '1'), 
					'message' => 'Champ obligatoire'
				)
			),
			'isbeneficiaire' => array(
				array(
					'rule' => array('equalTo', '1'),
					'message' => 'Champ obligatoire'
				)
			),
			'respectdelais' => array(
				array(
					'rule' => array('equalTo', '1'), 
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $hasOne = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'apre_id',
				'dependent' => false,
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

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

        public $hasMany = array(
            'Fichiermodule' => array(
                'className' => 'Fichiermodule',
                'foreignKey' => false,
                'dependent' => false,
                'conditions' => array(
                    'Fichiermodule.modele = \'Apre66\'',
                    'Fichiermodule.fk_value = {$__cakeID__$}'
                ),
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
            )
        );
	//         public $hasAndBelongsToMany = array(
	//             'Pieceaide66' => array(
	//                 'className'              => 'Pieceaide66',
	//                 'joinTable'              => 'aidesapres66_piecesaides66',
	//                 'foreignKey'             => 'aideapre66_id',
	//                 'associationForeignKey'  => 'pieceaide66_id',
	//                 'with'                   => 'Aideapre66Pieceaide66'
	//             )
	//         );

		/**
		*
		*/

		public function dossierId( $apre_id ){
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( "Personne.id = {$this->alias}.personne_id" )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$apre = $this->findById( $apre_id, null, null, 0 );

			if( !empty( $apre ) ) {
				return $apre['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		*
		*/

		public function numeroapre() {
				$numSeq = $this->query( "SELECT nextval('apres_numeroapre_seq');" );
				if( $numSeq === false ) {
					return null;
				}

				$numapre = date('Ym').sprintf( "%010s",  $numSeq[0][0]['nextval'] );
				return $numapre;
		}

		/**
		* Ajout de l'identifiant de la séance lors de la sauvegarde.
		*/

		public function beforeValidate( $options = array() ) {
			$primaryKey = Set::classicExtract( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$numeroapre = Set::classicExtract( $this->data, "{$this->alias}.numeroapre" );

			if( empty( $primaryKey ) && empty( $numeroapre ) && empty( $this->{$this->primaryKey} ) ) {
				$this->data[$this->alias]['numeroapre'] = $this->numeroapre();
			}

			return true;
		}
	}
?>
