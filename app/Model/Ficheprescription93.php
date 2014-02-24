<?php
	/**
	 * Code source de la classe Ficheprescription93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Ficheprescription93 ...
	 *
	 * @package app.Model
	 */
	class Ficheprescription93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Ficheprescription93';

		/**
		 * Récursivité par défaut du modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Allocatairelie',
			'Conditionnable',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Has one".
		 *
		 * @var array
		 */
		public $hasOne = array(
			'Instantanedonneesfp93' => array(
				'className' => 'Instantanedonneesfp93',
				'foreignKey' => 'ficheprescription93_id',
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'dependent' => true
			),
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Actionfp93' => array(
				'className' => 'Actionfp93',
				'foreignKey' => 'actionfp93_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Règles de validation.
		 *
		 * @var array
		 */
		public $validate = array(
			'dd_action' => array(
				'compareDates' => array(
					'rule' => array( 'compareDates', 'df_action', '<' ),
					'message' => 'La date de début d\'action doit être strictement inférieure à la date de fin d\'action',
					'allowEmpty' => true
				)
			),
			'df_action' => array(
				'compareDates' => array(
					'rule' => array( 'compareDates', 'dd_action', '>' ),
					'message' => 'La date de fin d\'action doit être strictement supérieure à la date de début d\'action',
					'allowEmpty' => true
				)
			),
		);

		/**
		 * Retourne le querydata de base à utiliser dans le moteur de recherche.
		 *
		 * @todo Si on faisait le find sur le modèle Ficheprescription93 (voir
		 * comment faire les jointures ici), ça irait peut-être plus vite (pour
		 * les personnes possédant une fiche de prescription uniquement).
		 *
		 * @return array
		 */
		public function searchQuery() {
			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );

				$query = $Allocataire->searchQuery();

				// Ajout des champs supplémentaires
				$query['fields'] = Hash::merge(
					$query['fields'],
					$this->fields(),
					$this->Actionfp93->fields(),
					$this->Actionfp93->Filierefp93->fields(),
					$this->Actionfp93->Filierefp93->Categoriefp93->fields(),
					$this->Actionfp93->Filierefp93->Categoriefp93->Thematiquefp93->fields()
				);

				// Ajout des jointures supplémentaires
				$query['joins'][] = $this->Personne->join( 'Ficheprescription93', array( 'type' => 'LEFT OUTER' ) );
				$query['joins'][] = $this->join( 'Actionfp93', array( 'type' => 'LEFT OUTER' ) );
				$query['joins'][] = $this->Actionfp93->join( 'Filierefp93', array( 'type' => 'LEFT OUTER' ) );
				$query['joins'][] = $this->Actionfp93->Filierefp93->join( 'Categoriefp93', array( 'type' => 'LEFT OUTER' ) );
				$query['joins'][] = $this->Actionfp93->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => 'LEFT OUTER' ) );

				// Enregistrement dans le cache
				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			// 1. On complète les conditions de base de l'allocataire
			$Allocataire = ClassRegistry::init( 'Allocataire' );
			$query = $Allocataire->searchConditions( $query, $search );

			$ficheprescription93Exists = Hash::get( $search, 'Ficheprescription93.exists' );
			if( $ficheprescription93Exists !== null ) {
				if( $ficheprescription93Exists ) {
					$query['conditions'][] = 'Ficheprescription93.id IS NOT NULL';
				}
				else {
					$query['conditions'][] = 'Ficheprescription93.id IS NULL';
				}
			}

			// 2. Ajout des filtres supplémentaires concernant l'action de la fiche de precription:
			//	 type de thématique, thématique, catégorie, filière, prestataire, action
			$paths = array( 'Thematiquefp93.type','Categoriefp93.thematiquefp93_id','Filierefp93.categoriefp93_id','Actionfp93.filierefp93_id', 'Actionfp93.prestatairefp93_id', 'Ficheprescription93.actionfp93_id' );
			foreach( $paths as $path ) {
				$value = suffix( Hash::get( $search, $path ) );
				if( !empty( $value ) ) {
					$query['conditions'][$path] = $value;
				}
			}

			// 3. La même sans ne prendre que le suffixe
			$paths = array( 'Ficheprescription93.statut' );
			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( !empty( $value ) ) {
					$query['conditions'][$path] = $value;
				}
			}

			// 4. Plages de dates
			$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, 'Ficheprescription93.rdvprestataire_date' );

			return $query;
		}

		/**
		 * Retourne un querydata suivant les filtres renvoyés par le moteur de
		 * recherche.
		 *
		 * @param array $search
		 * @return array
		 */
		public function search( array $search = array() ) {
			$query = $this->searchQuery();

			$query = $this->searchConditions( $query, $search );

			return $query;
		}

		/**
		 * Retourne les options nécessaires au formulaire de recherche, au formulaire,
		 * aux impressions, ...
		 *
		 * @param boolean $allocataireOptions
		 * @param boolean $findLists
		 * @return array
		 */
		public function options( $allocataireOptions = true, $findLists = false ) {
			$options = array();

			if( $allocataireOptions ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );

				$options = $Allocataire->options();
			}

			$options = Hash::merge(
				$options,
				$this->enums(),
				array( 'Ficheprescription93' => array( 'exists' => array( '0' => 'Non', '1' => 'Oui' ) ) ),
				$this->Actionfp93->enums(),
				$this->Actionfp93->Filierefp93->enums(),
				$this->Actionfp93->Filierefp93->Categoriefp93->enums(),
				$this->Actionfp93->Filierefp93->Categoriefp93->Thematiquefp93->enums()
			);

			if( $findLists ) {
				$options = Hash::merge(
					$options,
					array( 'Categoriefp93' => array( 'thematiquefp93_id' => $this->Actionfp93->Filierefp93->Categoriefp93->Thematiquefp93->findListPrefixed( 'type', 'id', 'name' ) ) ),
					array( 'Filierefp93' => array( 'categoriefp93_id' => $this->Actionfp93->Filierefp93->Categoriefp93->findListPrefixed( 'thematiquefp93_id', 'id', 'name' ) ) ),
					array( 'Actionfp93' => array( 'filierefp93_id' => $this->Actionfp93->Filierefp93->findListPrefixed( 'categoriefp93_id', 'id', 'name' ) ) ),
					array( 'Actionfp93' => array( 'prestatairefp93_id' => $this->Actionfp93->Prestatairefp93->findListPrefixed2() ) )//,
					// TODO: voir ci-dessous
					// array( 'Ficheprescription93' => array( 'actionfp93_id' => $this->Actionfp93->findListPrefixed( 'filierefp93_id', 'id', 'name' ) ) )
				);

				// Test Ficheprescription93.actionfp93_id
				$conditions = array();
				$query = array(
					'fields' => array(
						'Actionfp93.id',
						'Actionfp93.filierefp93_id',
						'Actionfp93.prestatairefp93_id',
						'Actionfp93.name',
					),
					'contain' => false,
					'conditions' => $conditions,
					'order' => array(
						'Actionfp93.name ASC'
					)
				);
				$results = $this->Actionfp93->find( 'all', $query );

				$options['Ficheprescription93']['actionfp93_id'] = Hash::combine(
					$results,
					array( '%s_%s_%s', "{n}.Actionfp93.filierefp93_id", "{n}.Actionfp93.prestatairefp93_id", '{n}.Actionfp93.id' ),
					"{n}.Actionfp93.name"
				);
			}

			return $options;
		}

		/**
		 * Préparation des données du formulaire d'ajout / de modification.
		 *
		 * @param integer $personne_id
		 * @param integer $id
		 * @return array
		 * @throws InternalErrorException
		 */
		public function prepareFormDataAddEdit( $personne_id, $id = null ) {
			// Pour l'état 01renseignee
			if( $id === null ) {
				$return = $this->Instantanedonneesfp93->Situationallocataire->getSituation( $personne_id );
				$return = $this->Instantanedonneesfp93->Situationallocataire->foo( $return );

				$return[$this->alias]['personne_id'] = $personne_id;
				$return[$this->alias]['statut'] = '01renseignee';

				// Référent du parcours actuel
				$referentparcours = $this->Personne->PersonneReferent->referentParcoursActuel( $personne_id );
				if( !empty( $referentparcours ) ) {
					$return[$this->alias]['structurereferente_id'] = $referentparcours['Referent']['structurereferente_id'];
					$return[$this->alias]['referent_id'] = "{$referentparcours['Referent']['structurereferente_id']}_{$referentparcours['Referent']['id']}";
				}
			}
			else {
				$query = array(
					'fields' => Hash::merge(
						$this->fields(),
						array(
							'Referent.structurereferente_id',
							'Actionfp93.filierefp93_id',
							'Actionfp93.prestatairefp93_id',
							'Filierefp93.categoriefp93_id',
							'Categoriefp93.thematiquefp93_id',
							'Thematiquefp93.type',
						)
					),
					'contain' => false,
					'joins' => array(
						$this->join( 'Referent' ),
						$this->join( 'Actionfp93' ),
						$this->Actionfp93->join( 'Filierefp93' ),
						$this->Actionfp93->Filierefp93->join( 'Categoriefp93' ),
						$this->Actionfp93->Filierefp93->Categoriefp93->join( 'Thematiquefp93' ),
					),
					'conditions' => array(
						"{$this->alias}.id" => $id
					)
				);
				$data = $this->find( 'first', $query );

				if( empty( $data ) || ( $data[$this->alias]['statut'] !== '01renseignee' ) ) {
					throw new InternalErrorException();
				}

				$return = $data;

				$return['Ficheprescription93']['structurereferente_id'] = $data['Referent']['structurereferente_id'];
				$return['Ficheprescription93']['referent_id'] = "{$data['Referent']['structurereferente_id']}_{$data[$this->alias]['referent_id']}";

				$return[$this->alias]['actionfp93_id'] = "{$data['Actionfp93']['filierefp93_id']}_{$data[$this->alias]['actionfp93_id']}";
				$return['Actionfp93']['filierefp93_id'] = "{$data['Filierefp93']['categoriefp93_id']}_{$data['Actionfp93']['filierefp93_id']}";
				$return['Filierefp93']['categoriefp93_id'] = "{$data['Categoriefp93']['thematiquefp93_id']}_{$data['Filierefp93']['categoriefp93_id']}";
				$return['Categoriefp93']['thematiquefp93_id'] = "{$data['Thematiquefp93']['type']}_{$data['Categoriefp93']['thematiquefp93_id']}";

				// FIXME: Actionfp93.prestatairefp93_id -> à vérifier
				$return['Actionfp93']['prestatairefp93_id'] = "{$data['Actionfp93']['filierefp93_id']}_{$data['Actionfp93']['prestatairefp93_id']}";
				$return[$this->alias]['actionfp93_id'] = "{$return['Actionfp93']['prestatairefp93_id']}_{$data[$this->alias]['actionfp93_id']}";
			}

			return $return;
		}

		/**
		 * Tentative de sauvegarde du formulaire d'ajout / de modification.
		 *
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEdit( array $data ) {
			// Pour l'état 01renseignee
			$unneeded = array( 'Validate', 'Thematiquefp93', 'Categoriefp93', 'Filierefp93', 'Actionfp93' );
			foreach( $unneeded as $modelName ) {
				unset( $data[$modelName] );
			}

			$id = Hash::get( $data, "{$this->alias}.{$this->primaryKey}" );
			$personne_id = Hash::get( $data, "{$this->alias}.personne_id" );

			$ficheprescription = array();
			// En cas de modification, on va rechercher les informations qui ne sont pas renvoyées par le formulaire
			if( !empty( $id ) ) {
				$ficheprescription = $this->find(
					'first',
					array(
						'fields' => Hash::merge(
							$this->fields(),
							array(
								'Instantanedonneesfp93.id',
								'Situationallocataire.id',
							)
						),
						'contain' => false,
						'joins' => array(
							$this->join( 'Instantanedonneesfp93' ),
							$this->Instantanedonneesfp93->join( 'Situationallocataire' )
						),
						'conditions' => array(
							"{$this->alias}.id" => $id
						)
					)
				);

				unset( $ficheprescription[$this->alias]['created'], $ficheprescription[$this->alias]['modified'] );
			}

			$data = Hash::merge( $ficheprescription, $data );

			// Début Situationallocataire ...
			$situationallocataire = $this->Instantanedonneesfp93->Situationallocataire->getSituation( $personne_id );
			$situationallocataire = $this->Instantanedonneesfp93->Situationallocataire->foo( $situationallocataire );
			// Fin Situationallocataire

			// Début Instantanedonnees93 ...
			$referent_id = suffix( Hash::get( $data, "{$this->alias}.referent_id" ) );
			$referent = $this->Referent->find(
				'first',
				array(
					'fields' => array(
						'Referent.fonction',
						'Referent.email',
						'Structurereferente.lib_struc',
						'Structurereferente.num_voie',
						'Structurereferente.type_voie',
						'Structurereferente.nom_voie',
						'Structurereferente.code_postal',
						'Structurereferente.ville',
						'Structurereferente.numtel',
						// 'Structurereferente.fax', // TODO
					),
					'contain' => false,
					'joins' => array(
						$this->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) )
					),
					'conditions' => array(
						'Referent.id' => $referent_id
					),
				)
			);

			$instantanedonneesfp93 = array();
			if( !empty( $referent ) ) {
				// TODO: pour l'état 01renseignee seulement
				$instantanedonneesfp93 = array(
					'Instantanedonneesfp93' => array(
						'referent_fonction' => $referent['Referent']['fonction'],
						'structure_name' => $referent['Structurereferente']['lib_struc'],
						'structure_num_voie' => $referent['Structurereferente']['num_voie'],
						'structure_type_voie' => $referent['Structurereferente']['type_voie'],
						'structure_nom_voie' => $referent['Structurereferente']['nom_voie'],
						'structure_code_postal' => $referent['Structurereferente']['code_postal'],
						'structure_ville' => $referent['Structurereferente']['ville'],
						'structure_tel' => $referent['Structurereferente']['numtel'],
						//'structure_fax' => $referent['Structurereferente']['fax'], // TODO
						'referent_email' => $referent['Referent']['email'],
					)
				);
			}
			// Fin Instantanedonnees93

			$data = Hash::merge(
				$data,
				$situationallocataire,
				$instantanedonneesfp93
			);

			$this->create( $data );
			$success = ( $this->save() !== false );

			if( $success ) {
				$this->Instantanedonneesfp93->Situationallocataire->create( $data );
				$success = ( $this->Instantanedonneesfp93->Situationallocataire->save() !== false ) && $success;

				$data['Instantanedonneesfp93']['ficheprescription93_id'] = $this->id;
				$data['Instantanedonneesfp93']['situationallocataire_id'] = $this->Instantanedonneesfp93->Situationallocataire->id;
				$this->Instantanedonneesfp93->create( $data );
				$success = ( $this->Instantanedonneesfp93->save() !== false ) && $success;

				if( !$success ) { // FIXME: nati vide au départ pour 531873
					$hiddenErrors = array(
						'Instantanedonneesfp93' => $this->Instantanedonneesfp93->validationErrors,
						'Situationallocataire' => $this->Instantanedonneesfp93->Situationallocataire->validationErrors
					);
					debug( $hiddenErrors );
				}
			}

			return $success;
		}

		/**
		 * Permet de savoir si un ajout est possible à partir des messages
		 * renvoyés par la méthode messages.
		 *
		 * @param array $messages
		 * @return boolean
		 */
		public function addEnabled( array $messages ) {
			return !in_array( 'error', $messages );
		}

		/**
		 * Messages à envoyer à l'utilisateur.
		 *
		 * @param integer $personne_id
		 * @return array
		 */
		public function messages( $personne_id ) {
			$messages = array();

			$query = array(
				'fields' => array(
					'Calculdroitrsa.toppersdrodevorsa',
					'Personne.nati',
					'Situationdossierrsa.etatdosrsa',
				),
				'contain' => false,
				'joins' => array(
					$this->Personne->join( 'Calculdroitrsa', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'LEFT OUTER' ) ),
				),
				'conditions' => array(
					'Personne.id' => $personne_id
				)
			);

			$result = $this->Personne->find( 'first', $query );

			$toppersdrodevorsa = Hash::get( $result, 'Calculdroitrsa.toppersdrodevorsa' );
			if( empty( $toppersdrodevorsa ) ) {
				$messages['Calculdroitrsa.toppersdrodevorsa_notice'] = 'notice';
			}

			$etatdosrsa = Hash::get( $result, 'Situationdossierrsa.etatdosrsa' );
			if( !in_array( $etatdosrsa, (array)Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' ), true ) ) {
				$messages['Situationdossierrsa.etatdosrsa_ouverts'] = 'notice';
			}

			$nati = Hash::get( $result, 'Personne.nati' );
			if( empty( $nati ) ) {
				$messages['Personne.nati_inconnue'] = 'error';
			}

			return $messages;
		}


		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les méthodes qui ne font rien.
		 */
		public function prechargement() {
			$query = $this->searchQuery();
			return !empty( $query );
		}
	}
?>