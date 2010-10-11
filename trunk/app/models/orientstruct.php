<?php
	class Orientstruct extends AppModel
	{
		public $name = 'Orientstruct';

		public $actsAs = array(
	//             'Autovalidate',
			'Enumerable' => array(
				'fields' => array(
					'accordbenef' => array(
						'values' => array( 0, 1 )
					),
					'urgent' => array(
						'values' => array( 0, 1 )
					),
					'etatorient' => array( 'domain' => 'orientstruct' ),
					/*'accordrefaccueil' => array(
						'values' => array( 0, 1 )
					),
					'decisionep' => array(
						'values' => array( 0, 1 )
					),
					'decisioncg' => array(
						'values' => array( 0, 1 )
					),*/
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			)
		);

		public $validate = array(
			'structurereferente_id' => array(
				array(
					'rule' => array( 'choixStructure', 'statut_orient' ),
					'message' => 'Champ obligatoire'
				)
			),
			'typeorient_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'date_propo' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			),
			'date_valid' => array(
				'notEmpty' => array(
					'rule' => 'date',
					'message' => 'Veuillez entrer une date valide'
				)
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typeorient' => array(
				'className' => 'Typeorient',
				'foreignKey' => 'typeorient_id',
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
			'Parcoursdetecte' => array(
				'className' => 'Parcoursdetecte',
				'foreignKey' => 'orientstruct_id',
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


		public $hasAndBelongsToMany = array(
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'joinTable' => 'orientsstructs_servicesinstructeurs',
				'foreignKey' => 'orientstruct_id',
				'associationForeignKey' => 'serviceinstructeur_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'OrientstructServiceinstructeur'
			)
		);

		/**
		*
		*/

		public function choixStructure( $field = array(), $compare_field = null ) {
			foreach( $field as $key => $value ) {
				if( !empty( $this->data[$this->name][$compare_field] ) && ( $this->data[$this->name][$compare_field] != 'En attente' ) && empty( $value ) ) {
					return false;
				}
			}
			return true;
		}

		/**
		*
		*/

		public function dossierId( $ressource_id ) {
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$ressource = $this->findById( $ressource_id, null, null, 1 );

			if( !empty( $ressource ) ) {
				return $ressource['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		* Récupère les données pour le PDf
		*/

		public function getDataForPdf( $id, $user_id ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
			$qual = $optionModel->qual();
			$typevoie = $optionModel->typevoie();


			$orientstruct = $this->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.id' => $id
					)
				)
			);

			/*$typeorient = $this->Structurereferente->Typeorient->find(
				'first',
				array(
					'conditions' => array(
						'Typeorient.id' => $orientstruct['Orientstruct']['typeorient_id'] // FIXME structurereferente_id
					)
				)
			);*/

			$this->Personne->Foyer->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->Personne->Foyer->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => $orientstruct['Personne']['foyer_id'],
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$orientstruct['Adresse'] = $adresse['Adresse'];

			// Récupération de l'utilisateur
			$user = ClassRegistry::init( 'User' )->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					)
				)
			);
			$orientstruct['User'] = $user['User'];

			// Recherche des informations du dossier
			$foyer = $this->Personne->Foyer->findById( $orientstruct['Personne']['foyer_id'], null, null, -1 );
			$dossier = $this->Personne->Foyer->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $foyer['Foyer']['dossier_id']
					),
					'recursive' => -1
				)
			);

			$orientstruct['Dossier'] = $dossier['Dossier'];

			if( isset( $orientstruct[$this->alias]['statut_orient'] ) && $orientstruct[$this->alias]['statut_orient'] == 'Orienté' ) {
				//Ajout pour le numéro de poste du référent de la structure
				$referent = $this->Personne->Referent->find(
					'first',
					array(
						'conditions' => array(
							'Referent.structurereferente_id' => $orientstruct['Structurereferente']['id']
						),
						'recursive' => -1
					)
				);

				if( !empty( $referent ) ) {
					$orientstruct['Referent'] = $referent['Referent'];
				}
			}

			$orientstruct['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $orientstruct['Personne']['dtnai'] ) );
			$orientstruct['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $orientstruct, 'Personne.qual' ) );
			$orientstruct['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Adresse.typevoie' ) );
			$orientstruct['Structurereferente']['type_voie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Structurereferente.type_voie' ) );


			$personne_referent = $this->Personne->Referent->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => Set::classicExtract( $orientstruct, 'Personne.id' )
					)
				)
			);

			if( !empty( $personne_referent ) ){
				$orientstruct = Set::merge( $orientstruct, $personne_referent );
			}

			return $orientstruct;
		}

		/**
		* Ajout d'entrée dans la table orientsstructs pour les DEM ou CJT RSA n'en possédant pas
		*/

		public function fillAllocataire() {
			$sql = "INSERT INTO orientsstructs (personne_id, statut_orient)
					(
						SELECT DISTINCT personnes.id, 'Non orienté' AS statut_orient
							FROM personnes
								INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = 'RSA' AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' ) )
							WHERE personnes.id NOT IN (
								SELECT orientsstructs.personne_id
									FROM orientsstructs
							)
					);";
			return $this->query( $sql );
		}
	}
?>