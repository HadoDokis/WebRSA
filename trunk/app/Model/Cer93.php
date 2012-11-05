<?php
	/**
	 * Code source de la classe Cer93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cer93 gère les CER du CG 93.
	 *
	 * @package app.Model
	 */
	class Cer93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cer93';

		/**
		 * Récursivité.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
//			'Validation.Autovalidate',
//			'Enumerable',
			'Formattable',
			'Pgsqlcake.PgsqlAutovalidate',
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				93 => 'Contratinsertion/contratinsertion.odt'
			)
		);

		public $validate = array(
			'matricule' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'qual' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			// FIXME
			/*'adresse' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),*/
			'codepos' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'locaadr' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'isemploitrouv' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'secteuracti_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'metierexerce_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'dureehebdo' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'naturecontrat_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
					'message' => 'Champ obligatoire',
				)
			),
// 			'dureecdd' => array(
// 				'notEmpty' => array(
// 					'rule' => array( 'notEmptyIf', 'isemploitrouv', true, array( 'O' ) ),
// 					'message' => 'Champ obligatoire',
// 				)
// 			),
			'prevu' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'duree' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'pointparcours' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' )
				)
			),
			'datepointparcours' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'pointparcours', true, array( 'aladate' ) ),
					'message' => 'Champ obligatoire',
				)
			),
		);

		/**
		 * Liaisons "belongsTo" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Metierexerce' => array(
				'className' => 'Metierexerce',
				'foreignKey' => 'metierexerce_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Secteuracti' => array(
				'className' => 'Secteuracti',
				'foreignKey' => 'secteuracti_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Naturecontrat' => array(
				'className' => 'Naturecontrat',
				'foreignKey' => 'naturecontrat_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Liaisons "hasMany" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Compofoyercer93' => array(
				'className' => 'Compofoyercer93',
				'foreignKey' => 'cer93_id',
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
			'Diplomecer93' => array(
				'className' => 'Diplomecer93',
				'foreignKey' => 'cer93_id',
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
			'Expprocer93' => array(
				'className' => 'Expprocer93',
				'foreignKey' => 'cer93_id',
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
			'Histochoixcer93' => array(
				'className' => 'Histochoixcer93',
				'foreignKey' => 'cer93_id',
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

		/**
		 * Liaisons "hasAndBelongsToMany" avec d'autres modèles.
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
            'Sujetcer93' => array(
				'className' => 'Sujetcer93',
				'joinTable' => 'cers93_sujetscers93',
				'foreignKey' => 'cer93_id',
				'associationForeignKey' => 'sujetcer93_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Cer93Sujetcer93'
			),
		);

		/**
		 * 	Fonction permettant la sauvegarde du formulaire du CER 93.
		 *
		 * 	Une règle de validation est supprimée en amont
		 * 	Les valeurs de la table Compofoyercer93 sont mises à jour à chaque modifciation
		 *
		 * 	@param $data Les données à sauvegarder.
		 * 	@return boolean
		 */
		public function saveFormulaire( $data ) {
			$success = true;

			// Sinon, ça pose des problèmes lors du add car les valeurs n'existent pas encore
			$this->unsetValidationRule( 'contratinsertion_id', 'notEmpty' );

			foreach( array( 'Compofoyercer93', 'Diplomecer93', 'Expprocer93' ) as $hasManyModel ) {
				$this->{$hasManyModel}->unsetValidationRule( 'cer93_id', 'notEmpty' );

				if( isset( $data['Cer93']['id'] ) && !empty( $data['Cer93']['id'] ) ) {
					$success = $this->{$hasManyModel}->deleteAll(
						array( "{$hasManyModel}.cer93_id" => $data['Cer93']['id'] )
					) && $success;
				}
			}

			// On passe les champs du fieldset emploi trouvé si l'allocataire déclare
			// ne pas avoir trouvé d'emploi
			if( $data['Cer93']['isemploitrouv'] == 'N' ) {
				$fields = array( 'secteuracti_id', 'metierexerce_id', 'dureehebdo', 'naturecontrat_id', 'dureecdd' );
				foreach( $fields as $field ) {
					$data['Cer93'][$field] = null;
				}
			}

			if( !isset( $data['Cer93']['dureecdd'] ) ){
				$data['Cer93']['dureecdd'] = null;
			}

			// On passe le champ date de point de aprcours à null au cas où l'allocataire
			// décide finalement de faire le point à la find e son contrat
			if( $data['Cer93']['pointparcours'] == 'alafin' ) {
				$fields = array( 'datepointparcours' );
				foreach( $fields as $field ) {
					$data['Cer93'][$field] = null;
				}
			}

			$success = $this->saveResultAsBool(
				$this->saveAssociated( $data, array( 'validate' => 'first', 'atomic' => false, 'deep' => true ) )
			) && $success;

			if( !$success ) {
				debug( $this->validationErrors );
			}

			return $success;
		}

		/**
		 * Recherche des données CAF liées à l'allocataire dans le cadre du Cer93.
		 *
		 * @param integer $personne_id
		 * @return array
		 * @throws NotFoundException
		 * @throws InternalErrorException
		 */
		public function dataCafAllocataire( $personne_id ) {
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			$querydataCaf = array(
				'fields' => array_merge(
					$this->Contratinsertion->Personne->fields(),
					$this->Contratinsertion->Personne->Prestation->fields(),
					$this->Contratinsertion->Personne->Dsp->fields(),
					$this->Contratinsertion->Personne->DspRev->fields(),
					$this->Contratinsertion->Personne->Foyer->fields(),
					$this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->Contratinsertion->Personne->Foyer->Dossier->fields(),
					array(
						'Historiqueetatpe.identifiantpe',
						'Historiqueetatpe.etat'
					)
				),
				'joins' => array(
					$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
					$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
					$this->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
					$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
					$this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
					$this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array(
					'Personne.id' => $personne_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Dsp.id IS NULL',
							'Dsp.id IN ( '.$this->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'DspRev.id IS NULL',
							'DspRev.id IN ( '.$this->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Informationpe.id IS NULL',
							'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
						)
					),
					array(
						'OR' => array(
							'Historiqueetatpe.id IS NULL',
							'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
						)
					)
				),
				'contain' => false
			);
			$dataCaf = $this->Contratinsertion->Personne->find( 'first', $querydataCaf );

			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $dataCaf['DspRev']['id'] ) ) {
				$dataCaf['Dsp'] = $dataCaf['DspRev'];
			}
			unset( $dataCaf['DspRev'] );

			// On s'assure d'avoir trouvé l'allocataire
			if( empty( $dataCaf ) ) {
				throw new NotFoundException();
			}

			// Et que celui-ci soit bien demandeur ou conjoint
			if( !in_array( $dataCaf['Prestation']['rolepers'], array( 'DEM', 'CJT' ) ) ) {
				throw new InternalErrorException( "L'allocataire \"{$personne_id}\" doit être demandeur ou conjont" );
			}

			// Bloc 2 : Composition du foyer
			// Récupération des informations de composition du foyer de l'allocataire
			$composfoyerscers93 = $this->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'"Personne"."qual" AS "Compofoyercer93__qual"',
						'"Personne"."nom" AS "Compofoyercer93__nom"',
						'"Personne"."prenom" AS "Compofoyercer93__prenom"',
						'"Personne"."dtnai" AS "Compofoyercer93__dtnai"',
						'"Prestation"."rolepers" AS "Compofoyercer93__rolepers"'
					),
					'conditions' => array( 'Personne.foyer_id' => $dataCaf['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);
			$composfoyerscers93 = array( 'Compofoyercer93' => Set::classicExtract( $composfoyerscers93, '{n}.Compofoyercer93' ) );
			$dataCaf = Set::merge( $dataCaf, $composfoyerscers93 );

			return $dataCaf;
		}

		/**
		 * Préparation des données du formulaire d'ajout ou de modification d'un
		 * CER pour le CG 93.
		 *
		 * @param integer $personne_id
		 * @param integer $contratinsertion_id
		 * @param integer $user_id
		 * @return array
		 * @throws InternalErrorException
		 * @throws NotFoundException
		 */
		public function prepareFormDataAddEdit( $personne_id, $contratinsertion_id, $user_id ) {
			// Recherche des données CAF.
			$dataCaf = $this->dataCafAllocataire( $personne_id );

			// Querydata pour le contrat
			$querydataCer = array(
				'contain' => array(
					'Cer93' => array(
						'Diplomecer93' => array(
							'order' => array( 'Diplomecer93.annee DESC' )
						),
						'Expprocer93' => array(
							'order' => array( 'Expprocer93.anneedeb DESC' )
						),
						'Sujetcer93',
					),
				)
			);

			// Données de l'utilisateur
			$querydataUser = array(
				'conditions' => array(
					'User.id' => $user_id
				),
				'contain' => array(
					'Structurereferente',
					'Referent' => array(
						'Structurereferente'
					)
				)
			);
			$dataUser = $this->Contratinsertion->User->find( 'first', $querydataUser );

			// On s'assure que l'utilisateur existe
			if( empty( $dataUser ) ) {
				throw new InternalErrorException( "Utilisateur non trouvé \"{$user_id}\"" );
			}

			// Si c'est une modification, on lit l'enregistrement, on actualise
			// les données (CAF et dernier CER validé) et on renvoit.
			if( !empty( $contratinsertion_id ) ) {
				$querydataCerActuel = $querydataCer;
				$querydataCerActuel['conditions'] = array(
					'Contratinsertion.id' => $contratinsertion_id
				);
				$dataCerActuel = $this->Contratinsertion->find( 'first', $querydataCerActuel );

				// Il faut que l'enregistrement à modifier existe
				if( empty( $dataCerActuel ) ) {
					throw new NotFoundException();
				}

				// Il faut que l'enregistrement à modifier soit en attente
				if( $dataCerActuel['Contratinsertion']['decision_ci'] != 'E' ) {
					throw new InternalErrorException( "Tentative de modification d'un enregistrement déjà traité \"{$contratinsertion_id}\"" );
				}

				$fieldsToCopy = array(
					'Contratinsertion' => array( 'id', 'personne_id', 'rg_ci', 'decision_ci' ),
				);
				foreach( $fieldsToCopy as $modelName => $fields ) {
					foreach( $fields as $field ) {
						$data[$modelName][$field] = $dataCerActuel[$modelName][$field];
					}
				}

				$data['Cer93'] = $dataCerActuel['Cer93'];

				$modelsToCopy = array( 'Diplomecer93', 'Expprocer93', 'Sujetcer93' );
				foreach( $modelsToCopy as $modelName ) {
					$data[$modelName] = $dataCerActuel['Cer93'][$modelName];
				}

				// FIXME: il faut en faire quelque chose de $dataCerActuel
//				$this->log( var_export( $data, true ), LOG_DEBUG );
			}
			// Sinon, on construit un nouvel enregistrement vide, on y met les
			// données CAF et ancien CER.
			else {
				// Création d'un "enregistrement type" vide.
				$data = array(
					'Contratinsertion' => array(
						'id' => null,
						'decision_ci' => 'E',
						'rg_ci' => null
					),
					'Cer93' => array(
						'id' => null,
						'contratinsertion_id' => null,
						'nomutilisateur' => null,
						'structureutilisateur' => null,
						'nivetu' => null
					),
					'Compofoyercer93' => array(),
					'Diplomecer93' => array(),
					'Expprocer93' => array(),
					'Sujetcer93' => array(),
				);

				// On préremplit le formulaire avec des données de l'utilisateur connecté si possible
				if( !empty( $dataUser['Structurereferente']['id'] ) ) {
					$data['Contratinsertion']['structurereferente_id'] = $dataUser['Structurereferente']['id'];
				}
				else if( !empty( $dataUser['Referent']['id'] ) ) {
					$data['Contratinsertion']['structurereferente_id'] = $dataUser['Referent']['structurereferente_id'];
					$data['Contratinsertion']['referent_id'] = $dataUser['Referent']['id'];
				}
			}

			// On ajoute d'autres données de l'utilisateur connecté
			// TODO: du coup, on peut faire on delete set null (+la structure ?)
			$data['Cer93']['user_id'] = $user_id;
			$data['Cer93']['nomutilisateur'] = $dataUser['User']['nom_complet'];
			if( !empty( $dataUser['Structurereferente']['id'] ) ) {
				$data['Cer93']['structureutilisateur'] = $dataUser['Structurereferente']['lib_struc'];;
			}
			else if( !empty( $dataUser['Referent']['id'] ) ) {
				$data['Cer93']['structureutilisateur'] = $dataUser['Referent']['Structurereferente']['lib_struc'];
			}

			// Fusion avec les données CAF
			$data = Set::merge( $data, $dataCaf );

			// 1. Récupération de l'adresse complète afin de remplir le champ adresse du CER93
			$Option = ClassRegistry::init( 'Option' );
			$options =  array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				)
			);
			$typevoie = Set::enum( $dataCaf['Adresse']['typevoie'], $options['Adresse']['typevoie'] );
			$adresseComplete = trim( $dataCaf['Adresse']['numvoie'].' '.$typevoie.' '.$dataCaf['Adresse']['nomvoie']."\n".$dataCaf['Adresse']['compladr'].' '.$dataCaf['Adresse']['complideadr'] );

			// 2. Transposition des données
			//Bloc 2 : Etat civil
			$data['Cer93']['matricule'] = $dataCaf['Dossier']['matricule'];
			$data['Cer93']['numdemrsa'] = $dataCaf['Dossier']['numdemrsa'];
			$data['Cer93']['rolepers'] = $dataCaf['Prestation']['rolepers'];
			$data['Cer93']['dtdemrsa'] = $dataCaf['Dossier']['dtdemrsa'];
			$data['Cer93']['identifiantpe'] = $dataCaf['Historiqueetatpe']['identifiantpe'];
			$data['Cer93']['qual'] = $dataCaf['Personne']['qual'];
			$data['Cer93']['nom'] = $dataCaf['Personne']['nom'];
			$data['Cer93']['nomnai'] = $dataCaf['Personne']['nomnai'];
			$data['Cer93']['prenom'] = $dataCaf['Personne']['prenom'];
			$data['Cer93']['dtnai'] = $dataCaf['Personne']['dtnai'];
			$data['Cer93']['adresse'] = $adresseComplete;
			$data['Cer93']['codepos'] = $dataCaf['Adresse']['codepos'];
			$data['Cer93']['locaadr'] = $dataCaf['Adresse']['locaadr'];
			$data['Cer93']['sitfam'] = $dataCaf['Foyer']['sitfam'];

			// Bloc 3
			$data['Cer93']['inscritpe'] = null;
			if( isset( $dataCaf['Historiqueetatpe']['etat'] ) && !empty( $dataCaf['Historiqueetatpe']['etat'] ) ) {
				$data['Cer93']['inscritpe'] = ( $dataCaf['Historiqueetatpe']['etat'] == 'inscription' );
			}

			// Copie des données du dernier CER validé en cas d'ajout
			if( empty( $contratinsertion_id ) ) {
				// Données du dernier CER validé
				$sqDernierCerValide = $this->Contratinsertion->sq(
					array(
						'alias' => 'derniercervalide',
						'fields' => array( 'derniercervalide.id' ),
						'conditions' => array(
							'derniercervalide.personne_id = Contratinsertion.personne_id',
							'derniercervalide.decision_ci' => 'V',
						),
						'order' => array( 'derniercervalide.datevalidation_ci DESC' ),
						'limit' => 1
					)
				);
				$querydataDernierCerValide = $querydataCer;
				$querydataDernierCerValide['conditions'] = array(
					'Contratinsertion.personne_id' => $personne_id,
					"Contratinsertion.id IN ( {$sqDernierCerValide} )"
				);
				
				$dataDernierCerValide = $this->Contratinsertion->find( 'first', $querydataDernierCerValide );

				//Champ pour le bloc 5 reprenant ce qui était prévu dans le pcd CER
				$data['Cer93']['prevupcd'] = $dataDernierCerValide['Cer93']['prevu'];

				// Copie des données du dernier CER validé
				if( !empty( $dataDernierCerValide ) ) {
					// Copie des champs du CER précédent
					$cer93FieldsToCopy = array( 'incoherencesetatcivil', 'cmu', 'cmuc', 'nivetu', 'autresexps' );
					foreach( $cer93FieldsToCopy as $field ) {
						$data['Cer93'][$field] = $dataDernierCerValide['Cer93'][$field];
					}

					// Copie des enregistrements liés
					$cer93ModelsToCopy = array( 'Diplomecer93', 'Expprocer93', 'Sujetcer93' );
					foreach( $cer93ModelsToCopy as $modelName ) {
						if( isset( $dataDernierCerValide['Cer93'][$modelName] ) ) {
							$data[$modelName] = $dataDernierCerValide['Cer93'][$modelName];
							if( !empty( $data[$modelName] ) ) {
								foreach( array_keys( $data[$modelName] ) as $key ) {
									unset(
										$data[$modelName][$key]['id'],
										$data[$modelName][$key]['cer93_id'],
										$data[$modelName][$key]['Cer93Sujetcer93']['id'],
										$data[$modelName][$key]['Cer93Sujetcer93']['cer93_id']
									);
								}
							}
						}
					}
					
					if( !empty( $data['Sujetcer93'] ) ) {
						$sousSujetsIds = Set::filter( Set::extract( $data, '/Sujetcer93/Cer93Sujetcer93/soussujetcer93_id' ) );
						if( !empty( $sousSujetsIds ) ) {
							$sousSujets = $this->Sujetcer93->Soussujetcer93->find( 'list', array( 'conditions' => array( 'Soussujetcer93.id' => $sousSujetsIds ) ) );
							foreach( $data['Sujetcer93'] as $key => $values ) {
								if( isset( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) ) {
									$data['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => $sousSujets[$values['Cer93Sujetcer93']['soussujetcer93_id']] );
								}
								else {
									$data['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => null );
								}
							}
						}
						
						$data['Cer93']['sujetpcd'] = serialize( array( 'Sujetcer93' => $data['Sujetcer93'] ) );
						$data['Sujetcer93'] = array();
					}
				}
				else {
					// RG CER précédent, puis RG DSP
					$data['Cer93']['nivetu'] = $dataCaf['Dsp']['nivetu'];
				}
			}

			// Les données CAF prévalent
			$data['Cer93']['natlog'] = $dataCaf['Dsp']['natlog'];

			return $data;
		}


		/**
		 * Prépare les données du formulaire de saisie du CER du CG 93 à partir
		 * des données CAF et des CERs précédents, pour un allocataire donné.
		 *
		 * @param integer $personneId L'id technique de l'allocataire
		 * @param integer $contratinsertion_id L'id technique du CER que l'on souhaite éventuellement modifier
		 * @param integer $user_id L'id technique de l'utilisateur qui réalise la saisie du formulaire
		 * @return array
		 */
		/*public function prepareFormData( $personneId, $contratinsertion_id, $user_id  ) {
			// Donnée de la CAF stockée en base
			$Informationpe = ClassRegistry::init( 'Informationpe' );
			$dataCaf = $this->Contratinsertion->Personne->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Contratinsertion->Personne->fields(),
						$this->Contratinsertion->Personne->Prestation->fields(),
						$this->Contratinsertion->Personne->Dsp->fields(),
						$this->Contratinsertion->Personne->DspRev->fields(),
						$this->Contratinsertion->Personne->Foyer->fields(),
						$this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Contratinsertion->Personne->Foyer->Dossier->fields(),
						array(
//							$this->Contratinsertion->vfRgCiMax( '"Personne"."id"' ),
							'Historiqueetatpe.identifiantpe',
							'Historiqueetatpe.etat'
						)
					),
					'joins' => array(
						$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
						$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
						$this->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
						$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
						$this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
						$this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Personne.id' => $personneId,
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'Dsp.id IS NULL',
								'Dsp.id IN ( '.$this->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'DspRev.id IS NULL',
								'DspRev.id IN ( '.$this->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
							)
						),
						array(
							'OR' => array(
								'Informationpe.id IS NULL',
								'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
							)
						),
						array(
							'OR' => array(
								'Historiqueetatpe.id IS NULL',
								'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
							)
						)
					),
					'contain' => false
				)
			);

			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $dataCaf['DspRev']['id'] ) ) {
				$dataCaf['Dsp'] = $dataCaf['DspRev'];
				unset( $dataCaf['DspRev'], $dataCaf['Dsp']['id'], $dataCaf['Dsp']['dsp_id'] );
			}

			//Récupération de l'adresse complète afin de remplir le champ adresse du CER93
			$Option = ClassRegistry::init( 'Option' );
			$options =  array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				)
			);
			$typevoie = Set::enum( $dataCaf['Adresse']['typevoie'], $options['Adresse']['typevoie'] );
			$adresseComplete = trim( $dataCaf['Adresse']['numvoie'].' '.$typevoie.' '.$dataCaf['Adresse']['nomvoie']."\n".$dataCaf['Adresse']['compladr'].' '.$dataCaf['Adresse']['complideadr'] );

			// Transposition des données
			//Bloc 2 : Etat civil
			$dataCaf['Cer93']['matricule'] = $dataCaf['Dossier']['matricule'];
			$dataCaf['Cer93']['numdemrsa'] = $dataCaf['Dossier']['numdemrsa'];
			$dataCaf['Cer93']['rolepers'] = $dataCaf['Prestation']['rolepers'];
			$dataCaf['Cer93']['dtdemrsa'] = $dataCaf['Dossier']['dtdemrsa'];
			$dataCaf['Cer93']['identifiantpe'] = $dataCaf['Historiqueetatpe']['identifiantpe'];
			$dataCaf['Cer93']['qual'] = $dataCaf['Personne']['qual'];
			$dataCaf['Cer93']['nom'] = $dataCaf['Personne']['nom'];
			$dataCaf['Cer93']['nomnai'] = $dataCaf['Personne']['nomnai'];
			$dataCaf['Cer93']['prenom'] = $dataCaf['Personne']['prenom'];
			$dataCaf['Cer93']['dtnai'] = $dataCaf['Personne']['dtnai'];
			$dataCaf['Cer93']['adresse'] = $adresseComplete;
			$dataCaf['Cer93']['codepos'] = $dataCaf['Adresse']['codepos'];
			$dataCaf['Cer93']['locaadr'] = $dataCaf['Adresse']['locaadr'];
			$dataCaf['Cer93']['sitfam'] = $dataCaf['Foyer']['sitfam'];
			$dataCaf['Cer93']['natlog'] = $dataCaf['Dsp']['natlog'];

			// Bloc 3
			$dataCaf['Cer93']['inscritpe'] = ( ( !empty( $dataCaf['Historiqueetatpe']['etat'] ) && ( $dataCaf['Historiqueetatpe']['etat'] == 'inscription' ) ) ? true : null );

			// Bloc 2 : Composition du foyer
			// Récupération des informations de composition du foyer de l'allocataire
			$composfoyerscers93 = $this->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'"Personne"."qual" AS "Compofoyercer93__qual"',
						'"Personne"."nom" AS "Compofoyercer93__nom"',
						'"Personne"."prenom" AS "Compofoyercer93__prenom"',
						'"Personne"."dtnai" AS "Compofoyercer93__dtnai"',
						'"Prestation"."rolepers" AS "Compofoyercer93__rolepers"'
					),
					'conditions' => array( 'Personne.foyer_id' => $dataCaf['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);
			$composfoyerscers93 = array( 'Compofoyercer93' => Set::classicExtract( $composfoyerscers93, '{n}.Compofoyercer93' ) );
			$dataCaf = Set::merge( $dataCaf, $composfoyerscers93 );

			//Donnée du CER actuel
			$dataActuelCer= array();
			if( !empty( $contratinsertion_id )) {
				$dataActuelCer = $this->Contratinsertion->find(
					'first',
					array(
						'conditions' => array(
							'Contratinsertion.id' => $contratinsertion_id,
						),
						'contain' => array(
							'Cer93'
						)
					)
				);

				// Bloc 4 : Diplômes
				// Récupération des informations de diplômes de l'allocataire
				$diplomescers93 = $this->Diplomecer93->find(
					'all',
					array(
						'fields' => array(
							'Diplomecer93.id',
							'Diplomecer93.cer93_id',
							'Diplomecer93.name',
							'Diplomecer93.annee'
						),
						'conditions' => array( 'Diplomecer93.cer93_id' => $dataActuelCer['Cer93']['id'] ),
						'order' => array( 'Diplomecer93.annee DESC' ),
						'contain' => false
					)
				);
				$diplomescers93 = array( 'Diplomecer93' => Set::classicExtract( $diplomescers93, '{n}.Diplomecer93' ) );
				$dataActuelCer = Set::merge( $dataActuelCer, $diplomescers93 );

				// Bloc 4 : Formation et expériece
				// Récupération des informations de diplômes de l'allocataire
				$expsproscers93 = $this->Expprocer93->find(
					'all',
					array(
						'fields' => array(
							'Expprocer93.id',
							'Expprocer93.cer93_id',
							'Expprocer93.metierexerce_id',
							'Expprocer93.secteuracti_id',
							'Expprocer93.anneedeb',
							'Expprocer93.duree',
						),
						'conditions' => array( 'Expprocer93.cer93_id' => $dataActuelCer['Cer93']['id'] ),
						'order' => array( 'Expprocer93.anneedeb DESC' ),
						'contain' => false
					)
				);
				$expsproscers93 = array( 'Expprocer93' => Set::classicExtract( $expsproscers93, '{n}.Expprocer93' ) );
				$dataActuelCer = Set::merge( $dataActuelCer, $expsproscers93 );

				// Bloc 6 : Liste des sujets sur lesquels le CEr porte
				$sujetscers93 = $this->Cer93Sujetcer93->find(
					'all',
					array(
						'conditions' => array( 'Cer93Sujetcer93.cer93_id' => $dataActuelCer['Cer93']['id'] ),
						'contain' => false
					)
				);
				$dataActuelCer = Set::merge( $dataActuelCer, array( 'Sujetcer93' => array( 'Sujetcer93' => Set::classicExtract( $sujetscers93, '{n}.Cer93Sujetcer93' ) ) ) );
			}

			//Donnée du précédent CER validé
			$dataPcdCer = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array(
						'Cer93.incoherencesetatcivil',
//						'Cer93.inscritpe', // FIXME
						'Cer93.cmu',
						'Cer93.cmuc',
//						'Cer93.nivetu', // FIXME
						'Cer93.autresexps',
						'Cer93.isemploitrouv',
						'Cer93.secteuracti_id',
						'Cer93.metierexerce_id',
						'Cer93.dureehebdo',
						'Cer93.naturecontrat_id',
						'Cer93.dureecdd',
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personneId,
//						'OR' => array(
//							'Contratinsertion.id IS NULL',
							'Contratinsertion.id IN ( '.$this->Contratinsertion->sqDernierContrat( 'Contratinsertion.personne_id', true ).' )'
//						)
					),
					'contain' => array(
						'Cer93'
					)
				)
			);

			// FIXME
			//Cer93.sujetscerpcd
			//Cer93.prevupcd
			//Diplomecer93.0.id
			//Diplomecer93.0.cer93_id
			//Diplomecer93.0.name
			//Diplomecer93.0.annee
			//Expprocer93.0.id
			//Expprocer93.0.cer93_id
			//Expprocer93.0.metierexerce_id
			//Expprocer93.0.secteuracti_id
			//Expprocer93.0.anneedeb
			//Expprocer93.0.duree
			$formData = Set::merge( Set::merge( $dataCaf, $dataPcdCer ), $dataActuelCer );

			$formData['Cer93']['nivetu'] = $formData['Dsp']['nivetu'];

			//Données de l'utilsiateur connecté
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => array(
						'Structurereferente'
					)
				)
			);
			$formData['Cer93']['user_id'] = $user_id;
			$formData['Cer93']['nomutilisateur'] = $user['User']['nom_complet'];
			$formData['Cer93']['structureutilisateur'] = $user['Structurereferente']['lib_struc'];;

			// Dans le cas d'un ajout, il faut supprimer les id et les clés étrangères des
			// enregistrements que l'on "copie".
			if( empty( $contratinsertion_id ) ) {
				$keys = array(
					'Contratinsertion.id',
					'Cer93.id',
					'Cer93.contratinsertion_id',
					'Compofoyercer93.{n}.id',
					'Compofoyercer93.{n}.cer93_id',
					'Diplomecer93.{n}.id',
					'Diplomecer93.{n}.cer93_id',
					'Expprocer93.{n}.id',
					'Expprocer93.{n}.cer93_id'
				);
				foreach( $keys as $key ) {
					$formData = Set::remove( $formData, $key );
				}
			}

			return $formData;
		}*/

		/**
		 * Retourne le chemin relatif du modèle de document à utiliser pour
		 * l'enregistrement du PDF.
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return "Contratinsertion/contratinsertion.odt";
		}

		/**
		 * Récupère les données pour le PDF.
		 *
		 * @param integer $contratinsertion_id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $contratinsertion_id, $user_id ) {
			$this->Contratinsertion->Personne->forceVirtualFields = true;
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			$joins = array(
				$this->join( 'Contratinsertion', array( 'type' => 'INNER' ) ),
				$this->join( 'User', array( 'type' => 'INNER' ) ),
				$this->Contratinsertion->join( 'Personne', array( 'type' => 'INNER' )),
				$Informationpe->joinPersonneInformationpe( 'Personne', 'Informationpe', 'LEFT OUTER' ),
				$Informationpe->join( 'Historiqueetatpe', array( 'type' => 'LEFT OUTER' ) ),
				$this->Contratinsertion->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' )),
				$this->Contratinsertion->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' )),
				$this->Contratinsertion->Personne->join( 'Foyer', array( 'type' => 'INNER' )),
				$this->Contratinsertion->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER'  )),
				$this->Contratinsertion->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
				$this->Contratinsertion->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				$this->Contratinsertion->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) )
			);

			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->User->fields(),
					$this->Contratinsertion->fields(),
					$this->Contratinsertion->Personne->fields(),
					$this->Contratinsertion->Personne->Prestation->fields(),
					$this->Contratinsertion->Personne->Dsp->fields(),
					$this->Contratinsertion->Personne->DspRev->fields(),
					$this->Contratinsertion->Personne->Foyer->fields(),
					$this->Contratinsertion->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->Contratinsertion->Personne->Foyer->Dossier->fields(),
					array(
						$this->Contratinsertion->vfRgCiMax( '"Personne"."id"' ),
						'Historiqueetatpe.identifiantpe',
						'Historiqueetatpe.etat'
					)
				),
				'joins' => $joins,
				'conditions' => array(
					'Cer93.contratinsertion_id' => $contratinsertion_id,
					array(
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Contratinsertion->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Dsp.id IS NULL',
							'Dsp.id IN ( '.$this->Contratinsertion->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'DspRev.id IS NULL',
							'DspRev.id IN ( '.$this->Contratinsertion->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
						)
					),
					array(
						'OR' => array(
							'Informationpe.id IS NULL',
							'Informationpe.id IN( '.$Informationpe->sqDerniere( 'Personne' ).' )'
						)
					),
					array(
						'OR' => array(
							'Historiqueetatpe.id IS NULL',
							'Historiqueetatpe.id IN( '.$Informationpe->Historiqueetatpe->sqDernier( 'Informationpe' ).' )'
						)
					)
				),
				'contain' => false
			);

			// On copie les DspsRevs si elles existent à la place des DSPs (on garde l'information la plus récente)
			if( !empty( $data['DspRev']['id'] ) ) {
				$data['Dsp'] = $data['DspRev'];
				unset( $data['DspRev'], $data['Dsp']['id'], $data['Dsp']['dsp_id'] );
			}
			$data = $this->find( 'first', $queryData );

			// Liste des informations concernant la composition du foyer
			$composfoyerscers93 = $this->Contratinsertion->Personne->find(
				'all',
				array(
					'fields' => array(
						'"Personne"."qual" AS "Compofoyercer93__qual"',
						'"Personne"."nom" AS "Compofoyercer93__nom"',
						'"Personne"."prenom" AS "Compofoyercer93__prenom"',
						'"Personne"."dtnai" AS "Compofoyercer93__dtnai"',
						'"Prestation"."rolepers" AS "Compofoyercer93__rolepers"'
					),
					'conditions' => array( 'Personne.foyer_id' => $data['Foyer']['id'] ),
					'contain' => array(
						'Prestation'
					)
				)
			);

			// Liste des diplômes enregistrés pour ce CER
			$diplomescers93 = $this->Diplomecer93->find(
				'all',
				array(
					'fields' => array(
						'Diplomecer93.id',
						'Diplomecer93.cer93_id',
						'Diplomecer93.name',
						'Diplomecer93.annee'
					),
					'conditions' => array( 'Diplomecer93.cer93_id' => $data['Cer93']['id'] ),
					'order' => array( 'Diplomecer93.annee DESC' ),
					'contain' => false
				)
			);

			// Bloc 4 : Formation et expériece
			// Liste des expériences pro enregistrés pour ce CER
			$expsproscers93 = $this->Expprocer93->find(
				'all',
				array(
					'fields' => array(
						'Expprocer93.id',
						'Expprocer93.cer93_id',
						'Expprocer93.metierexerce_id',
						'Expprocer93.secteuracti_id',
						'Expprocer93.anneedeb',
						'Expprocer93.duree',
					),
					'conditions' => array( 'Expprocer93.cer93_id' => $data['Cer93']['id'] ),
					'order' => array( 'Expprocer93.anneedeb DESC' ),
					'contain' => false
				)
			);

			// Liste des sujets sur lequel porte ce CER
			$sujetscers93 = $this->Cer93Sujetcer93->find(
				'all',
				array(
					'conditions' => array( 'Cer93Sujetcer93.cer93_id' => $data['Cer93']['id'] ),
					'contain' => array(
						'Sujetcer93',
						'Soussujetcer93'
					)
				)
			);

			return array(
				$data,
				'compofoyer' => $composfoyerscers93,
				'exppro' => $expsproscers93,
				'diplome' => $diplomescers93,
				'sujetcer' => $sujetscers93
			);
		}


		/**
		 * Retourne le PDF par défaut, stocké, ou généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo et le stocke,
		 *
		 * @param integer $id Id du CER
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $contratinsertion_id, $user_id ) {
			$data = $this->getDataForPdf( $contratinsertion_id, $user_id );
			$modeleodt = $this->modeleOdt( $data );

			$Option = ClassRegistry::init( 'Option' );
			$options =  Set::merge(
				array(
					'Persone' => array(
						'qual' => $Option->qual()
					),
					'Adresse' => array(
						'typevoie' => $Option->typevoie()
					)
				),
				$this->enums()
			);

			return $this->ged( $data, $modeleodt, false, $options );
		}

		/**
		 * Retourne l'ensemble de données liées au CER en cours
		 *
		 * @param integer $id Id du CER
		 * @return array
		 */
		public function dataView( $contratinsertion_id ) {

			// Recherche du contrat pour l'affichage
			$data = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => array(
						'Cer93' => array(
							'Compofoyercer93',
							'Diplomecer93',
							'Expprocer93',
							'Histochoixcer93' => array(
								'order' => array( 'Histochoixcer93.etape ASC' )
							),
							'Sujetcer93'
						),
						'Structurereferente' => array(
							'Typeorient'
						),
						'Referent'
					)
				)
			);

			$sousSujetsIds = Set::filter( Set::extract( $data, '/Cer93/Sujetcer93/Cer93Sujetcer93/soussujetcer93_id' ) );
			if( !empty( $sousSujetsIds ) ) {
				$sousSujets = $this->Sujetcer93->Soussujetcer93->find( 'list', array( 'conditions' => array( 'Soussujetcer93.id' => $sousSujetsIds ) ) );
				foreach( $data['Cer93']['Sujetcer93'] as $key => $values ) {
					if( isset( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) && !empty( $values['Cer93Sujetcer93']['soussujetcer93_id'] ) ) {
						$data['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => $sousSujets[$values['Cer93Sujetcer93']['soussujetcer93_id']] );
					}
					else {
						$data['Cer93']['Sujetcer93'][$key]['Cer93Sujetcer93']['Soussujetcer93'] = array( 'name' => null );
					}
				}
			}

			return $data;
		}


		/**
		 *	Liste des options envoyées à la vue pour le CER93
		 * 	@return array
		 */
		public function optionsView() {
			// Options
			$options = array(
				'Cer93' => array(
					'formeci' => ClassRegistry::init( 'Option' )->forme_ci()
				),
				'Contratinsertion' => array(
					'structurereferente_id' => $this->Contratinsertion->Structurereferente->listOptions(),
					'referent_id' => $this->Contratinsertion->Referent->listOptions()
				),
				'Prestation' => array(
					'rolepers' => ClassRegistry::init( 'Option' )->rolepers()
				),
				'Personne' => array(
					'qual' => ClassRegistry::init( 'Option' )->qual()
				),
				'Adresse' => array(
					'typevoie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Serviceinstructeur' => array(
					'typeserins' => ClassRegistry::init( 'Option' )->typeserins()
				),
				'Expprocer93' => array(
					'metierexerce_id' => $this->Expprocer93->Metierexerce->find( 'list' ),
					'secteuracti_id' => $this->Expprocer93->Secteuracti->find( 'list' )
				),
				'Foyer' => array(
					'sitfam' => ClassRegistry::init( 'Option' )->sitfam()
				),
				'Dsp' => array(
					'natlog' => ClassRegistry::init( 'Option' )->natlog()
				),
				'dureehebdo' => array_range( '0', '39' ),
				'dureecdd' => ClassRegistry::init( 'Option' )->duree_cdd(),
				'Structurereferente' => array(
					'type_voie' => ClassRegistry::init( 'Option' )->typevoie()
				),
				'Naturecontrat' => array(
					'naturecontrat_id' => $this->Naturecontrat->find( 'list' )
				)
			);
			$options = Set::merge(
				$this->Contratinsertion->Personne->Dsp->enums(),
				$this->enums(),
				$this->Histochoixcer93->enums(),
				$options
			);
			return $options;

		}
	}
?>