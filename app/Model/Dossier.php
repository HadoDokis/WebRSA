<?php
	/**
	 * Code source de la classe Dossier.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConfigurableQueryFields', 'ConfigurableQuery.Utility' );

	/**
	 * La classe Dossier ...
	 *
	 * @package app.Model
	 */
	class Dossier extends AppModel
	{
		public $name = 'Dossier';

		public $actsAs = array(
			'Conditionnable',
			'Formattable'
		);

		public $validate = array(
			'numdemrsa' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
				array(
					'rule' => array( 'between', 11, 11 ),
					'message' => 'Le n° de demande est composé de 11 caractères'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dtdemrsa' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.',
					'allowEmpty' => true
				)
			),
			'matricule' => array(
				'rule' => array( 'between', 15, 15 ),
				'message' => 'Le n° est composé de 15 caractères minimum (ajouter des zéros à la fin)',
				'allowEmpty' => true
			)
		);

		public $hasOne = array(
			'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'dossier_id',
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
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'dossier_id',
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
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'dossier_id',
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
			'Jeton' => array(
				'className' => 'Jeton',
				'foreignKey' => 'dossier_id',
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
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'dossier_id',
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

		public $hasMany = array(
			'Infofinanciere' => array(
				'className' => 'Infofinanciere',
				'foreignKey' => 'dossier_id',
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
			'Suiviinstruction' => array(
				'className' => 'Suiviinstruction',
				'foreignKey' => 'dossier_id',
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

		/**
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Personne' => array(
				'className' => 'Personne',
				'joinTable' => 'derniersdossiersallocataires',
				'foreignKey' => 'dossier_id',
				'associationForeignKey' => 'personne_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'Dernierdossierallocataire'
			),
		);

		/**
		 * Mise en majuscule du champ numdemrsa.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array() ) {
			if( !empty( $this->data['Dossier']['numdemrsa'] ) ) {
				$this->data['Dossier']['numdemrsa'] = strtoupper( $this->data['Dossier']['numdemrsa'] );
			}

			return parent::beforeSave( $options );
		}

		public function searchQuery() {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$departement = (int)Configure::read( 'Cg.departement' );

				// FIXME: on part du dossier et pas de la personne
				$Allocataire = ClassRegistry::init( 'Allocataire' );
				$types = array(
					'Calculdroitrsa' => 'LEFT OUTER',
					'Foyer' => 'INNER',
					'Prestation' => $departement == 66 ? 'LEFT OUTER' : 'INNER',
					'Personne' => 'LEFT OUTER',
					'Adressefoyer' => 'LEFT OUTER',
					'Dossier' => 'INNER',
					'Adresse' => 'LEFT OUTER',
					'Situationdossierrsa' => 'INNER',
					'Detaildroitrsa' => 'LEFT OUTER'
				);
				$query = $Allocataire->searchQuery( $types, 'Dossier' );

				// Le CD 66 veut pouvoir trouver les allocataires et les personnes sans prestation
				if( $departement === 66 ) {
					unset( $query['conditions']['Prestation.rolepers'] );
					$query['joins'][4] = $this->Foyer->Personne->join(
						'Prestation',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Prestation.rolepers' => array( 'DEM', 'CJT' )
							)
						)
					);
				}

				$query['joins'] = array_values( $query['joins'] );

				$query['order'] = array( 'Personne.nom ASC' );

				// Ajout des spécificités du moteur de recherche
				$query['fields'] = array_merge(
					array( 0 => 'Dossier.id' ),
					$query['fields'],
					ConfigurableQueryFields::getModelsFields(
						array(
							$this->Foyer->Personne->Dsp,
							$this->Foyer->Personne->DspRev,
							$this->Foyer->Personne->Orientstruct,
							$this->Foyer->Personne->Orientstruct->Structurereferente,
							$this->Foyer->Personne->Orientstruct->Typeorient
						)
					)
				);

				$query['joins'] = array_merge(
					$query['joins'],
					array(
						$this->Foyer->Personne->join(
							'Dsp',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Dsp.id IN ( '.$this->Foyer->Personne->Dsp->sqDerniereDsp().' )'
								)
							)
						),
						$this->Foyer->Personne->join(
							'DspRev',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'DspRev.id IN ( '.$this->Foyer->Personne->DspRev->sqDerniere().' )'
								)
							)
						),
						$this->Foyer->Personne->join(
							'Orientstruct',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Orientstruct.statut_orient' => 'Orienté',
									'Orientstruct.id IN ( '.$this->Foyer->Personne->Orientstruct->sqDerniere().' )'
								)
							)
						),
						$this->Foyer->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Foyer->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
					)
				);

				// Début des jointures supplémentaires par département

				// CD 58
				if( $departement == 58 ) {
					// Travailleur social chargé de l'évaluation: "Nom du chargé de
					// l'évaluation" lorsque l'on crée une orientation
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields(
							array(
								$this->Foyer->Personne->Dossiercov58,
								$this->Foyer->Personne->Dossiercov58->Propoorientationcov58,
								$this->Foyer->Personne->Dossiercov58->Propoorientationcov58->Referentorientant,
								$this->Foyer->Personne->Dossiercov58->Propoorientationcov58->Structureorientante
							)
						)
					);
					$query['joins'][] = $this->Foyer->Personne->join(
						'Dossiercov58',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array( 'Dossiercov58.themecov58' => 'proposorientationscovs58' )
						)
					);
					$query['joins'][] = $this->Foyer->Personne->Dossiercov58->join( 'Propoorientationcov58', array( 'type' => 'LEFT OUTER' ) );
					$query['joins'][] = $this->Foyer->Personne->Dossiercov58->Propoorientationcov58->join( 'Referentorientant', array( 'type' => 'LEFT OUTER' ) );
					$query['joins'][] = $this->Foyer->Personne->Dossiercov58->Propoorientationcov58->join( 'Structureorientante', array( 'type' => 'LEFT OUTER' ) );

					// Dernière activité
					$query['fields'] = array_merge(
						$query['fields'],
						ConfigurableQueryFields::getModelsFields(
							array(
								$this->Foyer->Personne->Activite
							)
						)
					);

					$query['joins'][] = $this->Foyer->Personne->join(
						'Activite',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Activite.id IN ( '.$this->Foyer->Personne->Activite->sqDerniere().' )'
							),
						)
					);

					$query = $this->Foyer->Personne->completeQueryVfEtapeDossierOrientation58( $query );
				}

				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		public function searchConditions( array $query, array $search ) {
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$query = $Allocataire->searchConditions( $query, $search );

			// Critères sur le dossier - service instructeur
			$serviceinstructeur_id = (string)Hash::get( $search, 'Serviceinstructeur.id' );
			if( $serviceinstructeur_id !== '' ) {
				$subQuery = array_words_replace(
					array(
						'alias' => 'Suiviinstruction',
						'fields' => array( 'Suiviinstruction.dossier_id' ),
						'contain' => false,
						'joins' => array(
							$this->Suiviinstruction->join( 'Serviceinstructeur', array( 'type' => 'INNER' ) )
						),
						'conditions' => array(
							'Serviceinstructeur.id' => $serviceinstructeur_id
						)
					),
					array( 'Suiviinstruction' => 'suivisinstruction', 'Serviceinstructeur' => 'servicesinstructeurs' )
				);
				$query['conditions'][] = '"Dossier"."id" IN ( '.$this->Suiviinstruction->sq( $subQuery ).' )';
			}

			// Possède...
			if( $this->Foyer->Personne->Behaviors->attached( 'LinkedRecords' ) === false ) {
				$this->Foyer->Personne->Behaviors->attach( 'LinkedRecords' );
			}
			$linkedModelNames = array( 'Cui', 'Orientstruct', 'Contratinsertion', 'Dsp' );
			foreach( $linkedModelNames as $linkedModelName ) {
				$fieldName = 'has_'.Inflector::underscore( $linkedModelName );
				$exists = (string)Hash::get( $search, "Personne.{$fieldName}" );
				if( in_array( $exists, array( '0', '1' ), true ) ) {
					$sql = $this->Foyer->Personne->linkedRecordVirtualField( $linkedModelName );
					$query['conditions'][] = $exists ? $sql : 'NOT ' . $sql;
				}
			}

			// Condition sur la nature du logement
			$natlog = (string)Hash::get( $search, 'Dsp.natlog' );
			if( $natlog !== '' ) {
				$query['conditions'][] = array(
					'OR' => array(
						array(
							// On cherche dans les Dsp si pas de Dsp mises à jour
							'DspRev.id IS NULL',
							'Dsp.natlog' => $natlog
						),
						'DspRev.natlog' => $natlog,
					)
				);
			}

			// Début des spécificités par département
			$departement = Configure::read( 'Cg.departement' );

			// La personne possède-t-elle un rôle ?
			if( $departement === 66 ) {
				$exists = (string)Hash::get( $search, 'Personne.has_prestation' );
				if( $exists === '0' ) {
					$query['conditions'][] = 'Prestation.rolepers IS NULL';
				}
				else if( $exists === '1' ) {
					$query['conditions']['Prestation.rolepers'] = array( 'DEM', 'CJT' );
				}
			}
			else {
				//$query['conditions'][]
			}


			// CD 58: travailleur social chargé de l'évaluation: "Nom du chargé de
			// l'évaluation" lorsque l'on crée une orientation
			if( $departement == 58 ) {
				$referentorientant_id = (string)Hash::get( $search, 'Propoorientationcov58.referentorientant_id' );
				if( $referentorientant_id !== '' ) {
					$query['conditions']['Propoorientationcov58.referentorientant_id'] = $referentorientant_id;
				}

				$query = $this->Foyer->Personne->completeQueryVfEtapeDossierOrientation58( $query, $search );
			}

			// CD 66: Personne ne possédant pas d'orientation et sans entrée Nonoriente66
			if( $departement == 66 ) {
				$exists = (string)Hash::get( $search, 'Personne.has_orientstruct' );
				if( $exists === '0' ) {
					$sql = $this->Foyer->Personne->linkedRecordVirtualField( 'Nonoriente66' );
					$query['conditions'][] = 'NOT ' . $sql;
				}
			}

			return $query;
		}

		/**
		 * Retourne un querydata prenant en compte les différents filtres du moteur de recherche.
		 *
		 * INFO (pour le CG66): ATTENTION, depuis que la possibilité de créer des dossiers avec un numéro
		 * temporaire existe, il est possible (via le bouton Ajouter) de créer des dossiers avec des allocataires
		 * ne possédant ni date de naissance, ni NIR.
		 * Du coup, lors de la recherche, si la case "Uniquement la dernière demande..." est cochée, les dossiers
		 * temporaires, avec allocataire sans NIR ou sans date de naissance ne ressortiront pas lors de cette
		 * recherche -> il faut donc décocher la case pour les voir apparaître
		 *
		 * @param array $params
		 * @return array
		 */
		public function search( $params ) {
			$query = $this->searchQuery();
			$query = $this->searchConditions( $query, $params );

			return $query;
		}

		public function prechargement() {
			$success = parent::prechargement() !== false;

			$query = $this->search( array() );
			$success = !empty( $query ) && $success;

			// Export des champs disponibles
			$fileName = TMP.DS.'logs'.DS.__CLASS__.'__searchQuery__cg'.Configure::read( 'Cg.departement' ).'.csv';
			ConfigurableQueryFields::exportQueryFields( $query, Inflector::tableize( $this->name ), $fileName );

			return $success;
		}

		/**
		 * Vérification que les champs spécifiés dans le paramétrage par les clés
		 * Cohortesrendezvous.cohorte.fields, Cohortesrendezvous.cohorte.innerTable
		 * et Cohortesrendezvous.exportcsv dans le webrsa.inc existent bien dans
		 * la requête de recherche renvoyée par la méthode cohorte().
		 *
		 * @return array
		 */
		public function checkParametrage() {
			$keys = array( 'Dossiers.index.fields', 'Dossiers.index.innerTable', 'Dossiers.exportcsv' );
			$query = $this->search( array() );

			$return = ConfigurableQueryFields::getErrors( $keys, $query );

			return $return;
		}

		/**
		 * Retourne un querydata prenant en compte les différents filtres du moteur de recherche.
		 *
		 * INFO (pour le CG66): ATTENTION, depuis que la possibilité de créer des dossiers avec un numéro
		 * temporaire existe, il est possible (via le bouton Ajouter) de créer des dossiers avec des allocataires
		 * ne possédant ni date de naissance, ni NIR.
		 * Du coup, lors de la recherche, si la case "Uniquement la dernière demande..." est cochée, les dossiers
		 * temporaires, avec allocataire sans NIR ou sans date de naissance ne ressortiront pas lors de cette
		 * recherche -> il faut donc décocher la case pour les voir apparaître
		 *
		 * @param array $params
		 * @return array
		 */
		/*public function search( $params ) {
			$conditions = array();

			$typeJointure = 'INNER';
			if( Configure::read( 'Cg.departement' ) != 66) {
				$conditions = array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.rgadr' => '01'
					),
					'Prestation.rolepers' => array( 'DEM', 'CJT' )
				);
			}
			else {
				$typeJointure = 'LEFT OUTER';
				$conditions = array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.rgadr' => '01'
					)
				);

				if( isset( $params['Prestation']['rolepers'] ) ){
					if( $params['Prestation']['rolepers'] == '0' ){
						$conditions[] = 'Prestation.rolepers IS NULL';
					}
					else if( $params['Prestation']['rolepers'] == '1' ){
						$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
					}
					else {
						$conditions[] = array(
							'OR' => array(
								'Prestation.rolepers IS NULL',
								'Prestation.rolepers IN ( \'DEM\', \'CJT\' )'
							)
						);
					}
				}
			}

			$joins = array(
				$this->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
				$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
				$this->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
				$this->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
				$this->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
				$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				$this->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
				$this->Foyer->Personne->join( 'Dsp', array( 'type' => 'LEFT OUTER' ) ),
				$this->Foyer->Personne->join( 'DspRev', array( 'type' => 'LEFT OUTER' ) ),
				$this->Foyer->Personne->join( 'Orientstruct',
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array( 'Orientstruct.statut_orient' => 'Orienté' )
					)
				),
				$this->Foyer->Personne->join( 'Prestation', array( 'type' => $typeJointure ) ),
				$this->Foyer->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
			);

			// Dernière orientation
			$conditions[] = array(
				'OR' => array(
					'Orientstruct.id IS NULL',
					'Orientstruct.id IN ( '.$this->Foyer->Personne->Orientstruct->sqDerniere().' )',
				)
			);

			// Condition sur la nature du logement
			$natlog = Hash::get( $params, 'Dsp.natlog' );
			if( !empty( $natlog ) ) {
				$conditions[] = array(
					'OR' => array(
						array(
							// On cherche dans les Dsp si pas de Dsp mises à jour
							'DspRev.id IS NULL',
							'Dsp.natlog' => $natlog
						),
						'DspRev.natlog' => $natlog,
					)
				);
			}

			// Dernières Dsp
			$conditions[] = array(
				array(
					'OR' => array(
						'Dsp.id IS NULL',
						'Dsp.id IN ( '.$this->Foyer->Personne->Dsp->sqDerniereDsp().' )'
					),
				),
				array(
					'OR' => array(
						'DspRev.id IS NULL',
						'DspRev.id IN ( '.$this->Foyer->Personne->DspRev->sqDerniere().' )'
					),
				),
			);

			$conditions = $this->conditionsAdresse( $conditions, $params );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $params );

			// Critères sur le dossier - service instructeur
			if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ) {
				$conditions[] = "Dossier.id IN ( SELECT suivisinstruction.dossier_id FROM suivisinstruction INNER JOIN servicesinstructeurs ON suivisinstruction.numdepins = servicesinstructeurs.numdepins AND suivisinstruction.typeserins = servicesinstructeurs.typeserins AND suivisinstruction.numcomins = servicesinstructeurs.numcomins AND suivisinstruction.numagrins = servicesinstructeurs.numagrins WHERE servicesinstructeurs.id = '".Sanitize::paranoid( $params['Serviceinstructeur']['id'] )."' )";
			}

			/// Statut de présence contrat engagement reciproque
			$hasContrat  = Set::extract( $params, 'Personne.hascontrat' );
			if( !empty( $hasContrat ) && in_array( $hasContrat, array( 'O', 'N' ) ) ) {
				if( $hasContrat == 'O' ) {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) = 0';
				}
			}

			// Personne ne possédant pas d'orientation, ne possédant aucune entrée dans la table orientsstructs
			if( isset( $params['Orientstruct']['exists'] ) && ( $params['Orientstruct']['exists'] != '' ) ) {
				if( $params['Orientstruct']['exists'] ) {
					$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) = 0';
					if( Configure::read( 'Cg.departement' ) == 66 ) {
						$joins[] = $this->Foyer->Personne->join( 'Nonoriente66', array( 'type' => 'LEFT OUTER' ) );
						$conditions[] = array( 'Nonoriente66.id IS NULL' );
					}
				}
			}

			if( $this->Foyer->Personne->Behaviors->attached( 'LinkedRecords' ) === false ) {
				$this->Foyer->Personne->Behaviors->attach( 'LinkedRecords' );
			}

			$hasCui = (string)Hash::get( $params, 'Cui.exists' );
			if( in_array( $hasCui, array( '0', '1' ), true ) ) {
				$exists = $this->Foyer->Personne->linkedRecordVirtualField( 'Cui' );
				$conditions[] = $hasCui ? $exists : 'NOT ' . $exists;
			}

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				//Travailleur social chargé de l'évaluation
				// Représente le "Nom du chargé de l'évaluation" lorsque l'on crée une orientation
				// via la table proposorientaitonscovs58
				$referent_id = Set::classicExtract( $params, 'PersonneReferent.referent_id' );
				if( isset( $referent_id ) && !empty( $referent_id ) ) {
					$joins = array_merge(
						$joins,
						array(
							$this->Foyer->Personne->join( 'Dossiercov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Foyer->Personne->Dossiercov58->join( 'Propoorientationcov58', array( 'type' => 'LEFT OUTER' ) )
						)
					);
					$conditions[] = array( 'Propoorientationcov58.referentorientant_id = \''.Sanitize::clean( $referent_id ).'\'' );
				}

				// Présence DSP ?
				$sqDspId = 'SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1';
				$sqDspExists = "( {$sqDspId} ) IS NOT NULL";
				if( isset( $params['Dsp']['exists'] ) && ( $params['Dsp']['exists'] != '' ) ) {
					if( $params['Dsp']['exists'] ) {
						$conditions[] = "( {$sqDspExists} )";
					}
					else {
						$conditions[] = "( ( {$sqDspId} ) IS NULL )";
					}
				}
			}


			$querydata = array(
				'fields' => array(
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Dossier.fonorg',
					'Personne.nir',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.dtnai',
					'Personne.idassedic',
					'Personne.nomcomnai',
					'Adresse.numvoie',
					'Adresse.libtypevoie',
					'Adresse.nomvoie',
					'Adresse.codepos',
					'Adresse.numcom',
					'Adresse.nomcom',
					'Situationdossierrsa.etatdosrsa',
					'Prestation.rolepers',
					'Personne.sexe',
					'Dsp.natlog',
					'DspRev.natlog',
					'Typeorient.lib_type_orient',
				),
				'recursive' => -1,
				'joins' => $joins,
				'limit' => 10,
				'order' => array( 'Personne.nom ASC' ),
				'conditions' => $conditions
			);

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$querydata['fields'][] = 'Activite.act';
				$querydata['joins'][] = $this->Foyer->Personne->join(
					'Activite',
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							'Activite.id IN ( '.$this->Foyer->Personne->Activite->sqDerniere().' )'
						),
					)
				);
			}

			// Référent du parcours
			$querydata = $this->Foyer->Personne->PersonneReferent->completeQdReferentParcours( $querydata, $params );

			// Ajout de l'étape du dossier d'orientation de l'allocataire pour le CG 58
			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$this->forceVirtualFields = true;
				$querydata = $this->Foyer->Personne->completeQueryVfEtapeDossierOrientation58( $querydata, $params );
			}

			return $querydata;
		}*/

		/**
		 * Renvoit un numéro de RSA temporaire (sous la form "TMP00000000", suivant le numéro de la
		 * séquence dossiers_numdemrsatemp_seq) pour l'ajout de dossiers au CG 66.
		 *
		 * @return string
		 */
		public function generationNumdemrsaTemporaire() {
			$numSeq = $this->query( "SELECT nextval('dossiers_numdemrsatemp_seq');" );
			if( $numSeq === false ) {
				return null;
			}

			$numdemrsaTemp = sprintf( "TMP%08s",  $numSeq[0][0]['nextval'] );
			return $numdemrsaTemp;
		}

		/**
		 *
		 * @param array $params Une manière d'identifier le dossier, via la valeur
		 *	de l'une des clés suivantes: id, foyer_id, personne_id.
		 * @param array $qdPartsJetons Les parties de querydata permettant
		 *	d'obtenir des informations sur le jeton éventuel du dossier.
		 * @return array
		 * @throws NotFoundException
		 */
		public function menu( $params, $qdPartsJetons ) {
			$conditions = array();

			if( !empty( $params['id'] ) && is_numeric( $params['id'] ) ) {
				$conditions['Dossier.id'] = $params['id'];
			}
			else if( !empty( $params['foyer_id'] ) && is_numeric( $params['foyer_id'] ) ) {
				$conditions['Foyer.id'] = $params['foyer_id'];
			}
			else if( !empty( $params['personne_id'] ) && is_numeric( $params['personne_id'] ) ) {
				$conditions['Dossier.id'] = $this->Foyer->Personne->dossierId( $params['personne_id'] );
			}

			if( empty( $conditions ) ) {
				throw new NotFoundException();
			}

			// Données du dossier RSA.
			$querydata = array(
				'fields' => array(
					'Dossier.id',
					'Dossier.matricule',
					'Dossier.fonorg',
					'Dossier.numdemrsa',
					'Foyer.id',
					$this->Foyer->sqVirtualField( 'enerreur' ),
					$this->Foyer->sqVirtualField( 'sansprestation' ),
					'Situationdossierrsa.etatdosrsa',
				),
				'joins' => array(
					$this->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->join( 'Situationdossierrsa', array( 'type' => 'LEFT' ) ),
				),
				'conditions' => $conditions,
				'contain' => false
			);

			$keys = array( 'fields', 'joins' );
			foreach( $keys as $key ) {
				$querydata[$key] = array_merge( $querydata[$key], $qdPartsJetons[$key] );
			}
			$dossier = $this->find( 'first', $querydata );

			$adresses = $this->Foyer->Adressefoyer->find(
				'all',
				array(
					'fields' => array(
						'Adressefoyer.rgadr',
						'Adressefoyer.dtemm',
						'"Adresse"."numcom" AS "Adressefoyer__codeinsee"',
					),
					'conditions' => array(
						'Adressefoyer.foyer_id' => $dossier['Foyer']['id']
					),
					'joins' => array(
						$this->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) )
					),
					'contain' => false,
					'order' => array( 'Adressefoyer.rgadr ASC', 'Adressefoyer.dtemm DESC' )
				)
			);
			// Mise en forme des adresses du foyer, ajout des champs virtuels ddemm et dfemm
			$adresses = Hash::combine( $adresses, '{n}.Adressefoyer.rgadr', '{n}.Adressefoyer' );
			$ddemm = null;
			$dfemm = null;
			foreach( $adresses as $rgadr => $adresse ) {
				$dfemm = ( is_null( $ddemm ) ? null : date( 'Y-m-d', strtotime( '-1 day', strtotime( $ddemm ) ) ) );
				$ddemm = $adresse['dtemm'];

				$adresses[$rgadr]['ddemm'] = $ddemm;
				$adresses[$rgadr]['dfemm'] = $dfemm;
			}
			$dossier = Set::merge( $dossier, array( 'Adressefoyer' => $adresses ) );

			// Les personnes du foyer
			$conditionsDerniereOrientstruct = array(
				'Orientstruct.id IN ( '.$this->Foyer->Personne->Orientstruct->sqDerniere().' )'
			);

			$query = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Prestation.rolepers',
					'Orientstruct.structurereferente_id',
					'Structurereferente.typestructure',
					$this->Foyer->Personne->Memo->sqNbMemosLies($this, 'Personne.id', 'nb_memos_lies' )
				),
				'conditions' => array(
					'Personne.foyer_id' => Hash::get( $dossier, 'Foyer.id' ),
				),
				'joins' => array(
					$this->Foyer->Personne->join( 'Prestation', array( 'type' => 'LEFT' ) ),
					$this->Foyer->Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER', 'conditions' => $conditionsDerniereOrientstruct ) ),
					$this->Foyer->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
				),
				'contain' => false,
				'order' => array(
					'( CASE WHEN Prestation.rolepers = \'DEM\' THEN 0 WHEN Prestation.rolepers = \'CJT\' THEN 1 WHEN Prestation.rolepers = \'ENF\' THEN 2 ELSE 3 END ) ASC',
					'Personne.nom ASC',
					'Personne.prenom ASC'
				)
			);

			if( Configure::read( 'AncienAllocataire.enabled' ) ) {
				$sqAncienAllocataire = $this->Foyer->Personne->sqAncienAllocataire();
				$query['fields'][] = "( \"Prestation\".\"id\" IS NULL AND {$sqAncienAllocataire} ) AS \"Personne__ancienallocataire\"";
			}

			$personnes = $this->Foyer->Personne->find( 'all', $query );

			// Reformattage pour la vue
			$dossier['Foyer']['Personne'] = Hash::extract( $personnes, '{n}.Personne' );

			foreach( array( 'Prestation', 'Orientstruct', 'Structurereferente', 'Memo', 'Bilanparcours66' ) as $modelName ) {
				foreach( Hash::extract( $personnes, "{n}.{$modelName}" ) as $i => $datas ) {
					$dossier['Foyer']['Personne'] = Hash::insert( $dossier['Foyer']['Personne'], "{$i}.{$modelName}", $datas );
				}
			}

			if( !empty( $params['personne_id'] ) && is_numeric( $params['personne_id'] ) ) {
				$dossier['personne_id'] = $params['personne_id'];
			}

			return $dossier;
		}
	}
?>