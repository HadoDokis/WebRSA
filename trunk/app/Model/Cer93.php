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
	 * La classe Cer93 ...
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
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Secteuracti' => array(
				'className' => 'Secteuracti',
				'foreignKey' => 'secteuracti_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Naturecontrat' => array(
				'className' => 'Naturecontrat',
				'foreignKey' => 'naturecontrat_id',
				'conditions' => null,
				'type' => 'INNER',
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


		public function prepareFormData( $personneId, $contratinsertion_id, $user_id  ) {
			// Donnée de la CAF stockée en base
			$this->Contratinsertion->Personne->forceVirtualFields = true;
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
							$this->Contratinsertion->vfRgCiMax( '"Personne"."id"' ),
							'Historiqueetatpe.identifiantpe',
							'Historiqueetatpe.etat',
							'Adresse.adresse_complete'
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
			$dataCaf['Cer93']['adresse'] = $dataCaf['Adresse']['adresse_complete'];//FIXME virtual fiuelds adresse.php
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
			}

			//Donnée du précédent CER validé
			$dataPcdCer = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.personne_id' => $personneId,
						'OR' => array(
							'Contratinsertion.id IS NULL',
							'Contratinsertion.id IN ( '.$this->Contratinsertion->sqDernierContrat( 'Contratinsertion.personne_id', true ).' )'
						)
					),
					'contain' => false
				)
			);

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
		}

		
		/**
		 * Retourne le PDF par défaut, stocké, ou généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo et le stocke,
		 *
		 * @param integer $id Id du CER
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $id ) {
			$data = $this->getDataForPdf( $id );
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

	}
?>