<?php
	/**
	 * Code source de la classe Ficheprescription93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractSearch', 'Model/Abstractclass' );

	/**
	 * La classe Ficheprescription93 ...
	 *
	 * @package app.Model
	 */
	class Ficheprescription93 extends AbstractSearch
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
		 * Correspondance entre les champs virtuels de l'action et les champ réels.
		 *
		 * @var array
		 */
		public $correspondances = array(
			'Ficheprescription93.typethematiquefp93_id' => 'Thematiquefp93.type',
			'Ficheprescription93.thematiquefp93_id' => 'Thematiquefp93.id',
			'Ficheprescription93.categoriefp93_id' => 'Categoriefp93.id',
			'Ficheprescription93.filierefp93_id' => 'Filierefp93.id',
			'Ficheprescription93.prestatairefp93_id' => 'Prestatairefp93.id',
			'Ficheprescription93.actionfp93_id' => 'Actionfp93.id',
			'Ficheprescription93.numconvention' => 'Ficheprescription93.numconvention',
		);

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Allocatairelie',
			'Conditionnable',
			'Formattable' => array(
				'phone' => array( 'prestatairefp93_tel', 'prestatairefp93_fax' )
			),
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				93 => array(
					'Ficheprescription93/ficheprescription.odt',
				)
			),
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
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Filierefp93' => array(
				'className' => 'Filierefp93',
				'foreignKey' => 'filierefp93_id',
				'conditions' => null,
				'type' => 'INNER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Motifnonintegrationfp93' => array(
				'className' => 'Motifnonintegrationfp93',
				'foreignKey' => 'motifnonintegrationfp93_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Motifnonreceptionfp93' => array(
				'className' => 'Motifnonreceptionfp93',
				'foreignKey' => 'motifnonreceptionfp93_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Motifnonretenuefp93' => array(
				'className' => 'Motifnonretenuefp93',
				'foreignKey' => 'motifnonretenuefp93_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Motifnonsouhaitfp93' => array(
				'className' => 'Motifnonsouhaitfp93',
				'foreignKey' => 'motifnonsouhaitfp93_id',
				'conditions' => null,
				'type' => 'LEFT OUTER',
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
			'Prestatairefp93' => array(
				'className' => 'Prestatairefp93',
				'foreignKey' => 'prestatairefp93_id',
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
		 * Associations "Has and belongs to many".
		 *
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
			'Documentbeneffp93' => array(
				'className' => 'Documentbeneffp93',
				'joinTable' => 'documentsbenefsfps93_fichesprescriptions93',
				'foreignKey' => 'ficheprescription93_id',
				'associationForeignKey' => 'documentbeneffp93_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'Documentbeneffp93Ficheprescription93'
			),
			'Modtransmfp93' => array(
				'className' => 'Modtransmfp93',
				'joinTable' => 'fichesprescriptions93_modstransmsfps93',
				'foreignKey' => 'ficheprescription93_id',
				'associationForeignKey' => 'modtransmfp93_id',
				'unique' => true,
				'conditions' => null,
				'fields' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'finderQuery' => null,
				'deleteQuery' => null,
				'insertQuery' => null,
				'with' => 'Ficheprescription93Modtransmfp93'
			),
		);

		/**
		 * Règles de validation.
		 *
		 * @var array
		 */
		public $validate = array(
			// Début champs virtuels pour le formulaire d'ajout / modification
			'structurereferente_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'typethematiquefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'thematiquefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'thematiquefp93' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'categoriefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'categoriefp93' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'filierefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'filierefp93' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
			'prestatairefp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null
				)
			),
			'prestatairefp93' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'typethematiquefp93_id', true, array( 'horspdi' ) ),
					'message' => null
				)
			),
			'actionfp93_id' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'typethematiquefp93_id', true, array( 'pdi' ) ),
					'message' => null
				)
			),
			'actionfp93' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'typethematiquefp93_id', true, array( 'horspdi' ) ),
					'message' => null
				)
			),
			'prestatairefp93_adresse' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'typethematiquefp93_id', true, array( 'horspdi' ) ),
					'message' => null
				)
			),
			'prestatairefp93_codepos' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'typethematiquefp93_id', true, array( 'horspdi' ) ),
					'message' => null
				)
			),
			'prestatairefp93_localite' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmptyIf', 'typethematiquefp93_id', true, array( 'horspdi' ) ),
					'message' => null
				)
			),
			'prestatairefp93_tel' => array(
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				),
			),
			'prestatairefp93_fax' => array(
				'phoneFr' => array(
					'rule' => array( 'phoneFr' ),
					'allowEmpty' => true,
				),
			),
			'prestatairefp93_email' => array(
				'email' => array(
					'rule' => array( 'email' ),
					'allowEmpty' => true,
				),
			),
			// Fin champs virtuels pour le formulaire d'ajout / modification
			'objet' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => null,
					'allowEmpty' => false
				)
			),
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
		 * @param array $types Les types de jointure alias => type
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$types += array(
				'Ficheprescription93' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
				'Actionfp93' => 'LEFT OUTER',
				'Prestatairefp93' => 'LEFT OUTER',
				'Filierefp93' => 'LEFT OUTER',
				'Categoriefp93' => 'LEFT OUTER',
				'Thematiquefp93' => 'LEFT OUTER',
				'Detaildroitrsa' => 'LEFT OUTER',
				'Prestation' => 'LEFT OUTER',
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );

				$query = $Allocataire->searchQuery( $types );

				// Ajout des champs supplémentaires
				$query['fields'] = Hash::merge(
					$query['fields'],
					$this->fields(),
					$this->Actionfp93->fields(),
					$this->Referent->fields(),
					$this->Filierefp93->fields(),
					$this->Actionfp93->Prestatairefp93->fields(),
					$this->Filierefp93->Categoriefp93->fields(),
					$this->Filierefp93->Categoriefp93->Thematiquefp93->fields()
				);

				// Ajout des jointures supplémentaires
				$query['joins'][] = $this->Personne->join( 'Ficheprescription93', array( 'type' => $types['Ficheprescription93'] ) );
				$query['joins'][] = $this->join( 'Actionfp93', array( 'type' => $types['Actionfp93'] ) );
				$query['joins'][] = $this->join( 'Referent', array( 'type' => $types['Referent'] ) );
				$query['joins'][] = $this->join( 'Filierefp93', array( 'type' => $types['Filierefp93'] ) );
				$query['joins'][] = $this->Actionfp93->join( 'Prestatairefp93', array( 'type' => $types['Prestatairefp93'] ) );
				$query['joins'][] = $this->Filierefp93->join( 'Categoriefp93', array( 'type' => $types['Categoriefp93'] ) );
				$query['joins'][] = $this->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => $types['Thematiquefp93'] ) );

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
			if( !in_array( $ficheprescription93Exists, array( null, '' ), true ) ) {
				if( $ficheprescription93Exists ) {
					$query['conditions'][] = 'Ficheprescription93.id IS NOT NULL';
				}
				else {
					$query['conditions'][] = 'Ficheprescription93.id IS NULL';
				}
			}

			// 2. Ajout des filtres supplémentaires concernant l'action de la fiche de precription:
			$paths = array( 'Ficheprescription93.typethematiquefp93_id', 'Ficheprescription93.thematiquefp93_id', 'Ficheprescription93.categoriefp93_id', 'Ficheprescription93.filierefp93_id', 'Ficheprescription93.prestatairefp93_id', 'Ficheprescription93.actionfp93_id' );
			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( !empty( $value ) ) {
					if( isset( $this->correspondances[$path] ) ) {
						$correspondance = $this->correspondances[$path];
					}
					else {
						$correspondance = $path;
					}
					$query['conditions'][$correspondance] = $value;
				}
			}

			// 3. La même sans ne prendre que le suffixe
			$paths = array(
				'Ficheprescription93.statut',
				'Ficheprescription93.benef_retour_presente',
				'Ficheprescription93.personne_recue',
				'Ficheprescription93.personne_retenue',
				'Ficheprescription93.personne_a_integre',
			);
			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( !in_array( $value, array( null, '' ), true ) ) {
					$query['conditions'][$path] = $value;
				}
			}

			// 4. Recherche par numéro de convention
			$value = suffix( Hash::get( $search, 'Actionfp93.numconvention' ) );
			if( !empty( $value ) ) {
				$query['conditions']['UPPER( Actionfp93.numconvention ) LIKE'] = strtoupper( $value ).'%';
			}

			// 5. Plages de dates
			$paths = array( 'Ficheprescription93.rdvprestataire_date', 'Ficheprescription93.date_transmission', 'Ficheprescription93.date_retour', 'Ficheprescription93.df_action' );
			foreach( $paths as $path ) {
				$query['conditions'] = $this->conditionsDates( $query['conditions'], $search, $path );
			}

			// 6. Possède certaines dates
			$paths = array( 'Ficheprescription93.has_date_bilan_mi_parcours', 'Ficheprescription93.has_date_bilan_final', 'Ficheprescription93.has_date_retour' );
			foreach( $paths as $path ) {
				$value = Hash::get( $search, $path );
				if( !in_array( $value, array( null, '' ), true ) ) {
					$path = str_replace( '.has_', '.', $path );
					if( $value ) {
						$query['conditions'][] = "{$path} IS NOT NULL";
					}
					else {
						$query['conditions'][] = "{$path} IS NULL";
					}
				}
			}

			return $query;
		}

		/**
		 * Retourne les options nécessaires au formulaire de recherche, au formulaire,
		 * aux impressions, ...
		 *
		 * @todo actif
		 *
		 * @param array $params <=> array( 'allocataire' => true, 'find' => false, 'autre' => false )
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = array();
			$params = $params + array( 'allocataire' => true, 'find' => false, 'autre' => false );

			$motifsNames = array( 'Motifnonreceptionfp93', 'Motifnonretenuefp93', 'Motifnonsouhaitfp93', 'Motifnonintegrationfp93', 'Documentbeneffp93' );

			if( Hash::get( $params, 'allocataire' ) ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );

				$options = $Allocataire->options();
			}

			$options = Hash::merge(
				$options,
				$this->enums(),
				array( 'Ficheprescription93' => array( 'exists' => array( '0' => 'Non', '1' => 'Oui' ) ) ),
				$this->Actionfp93->enums(),
				$this->Filierefp93->enums(),
				$this->Filierefp93->Categoriefp93->enums(),
				$this->Filierefp93->Categoriefp93->Thematiquefp93->enums(),
				$this->Instantanedonneesfp93->enums()
			);

			if( Hash::get( $params, 'find' ) ) {
				$options = Hash::merge(
					$options,
					array( 'Ficheprescription93' => array( 'typethematiquefp93_id' => $options['Thematiquefp93']['type'] ) ),
					array( 'Modtransmfp93' => array( 'Modtransmfp93' => $this->Modtransmfp93->find( 'list' ) ) ),
					array( 'Documentbeneffp93' => array( 'Documentbeneffp93' => $this->Documentbeneffp93->find( 'list' ) ) )
				);

				foreach( $motifsNames as $motifName ) {
					$foreignKey = Inflector::underscore( $motifName ).'_id';

					$options[$this->alias][$foreignKey] = $this->{$motifName}->find( 'list' );
				}
			}

			// Valeurs "Autre" pour les motifs ...
			if( Hash::get( $params, 'autre' ) ) {
				foreach( $motifsNames as $motifName ) {
					$foreignKey = Inflector::underscore( $motifName ).'_id';

					$query = array(
						'fields' => array( "{$motifName}.id" ),
						'conditions' => array(
							"{$motifName}.autre" => '1'
						)
					);
					$options['Autre'][$this->alias][$foreignKey] = $this->{$motifName}->find( 'list', $query );
				}
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
			// A la création
			if( $id === null ) {
				$return = $this->Instantanedonneesfp93->getInstantane( $personne_id );

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
						$this->Instantanedonneesfp93->fields(),
						array(
							$this->Instantanedonneesfp93->sqVirtualField( 'benef_natpf' ),
							'Referent.structurereferente_id',
							'Actionfp93.numconvention',
							'Actionfp93.filierefp93_id',
							'( CASE WHEN "Thematiquefp93"."type" = \'horspdi\' THEN "Filierefp93"."name" ELSE NULL END ) AS "Ficheprescription93__filierefp93"',
							'Actionfp93.prestatairefp93_id',
							'Filierefp93.categoriefp93_id',
							'( CASE WHEN "Thematiquefp93"."type" = \'horspdi\' THEN "Categoriefp93"."name" ELSE NULL END ) AS "Ficheprescription93__categoriefp93"',
							'Categoriefp93.thematiquefp93_id',
							'( CASE WHEN "Thematiquefp93"."type" = \'horspdi\' THEN "Thematiquefp93"."name" ELSE NULL END ) AS "Ficheprescription93__thematiquefp93"',
							'Thematiquefp93.type',
						)
					),
					'contain' => false,
					'joins' => array(
						$this->join( 'Actionfp93' ),
						$this->join( 'Instantanedonneesfp93' ),
						$this->join( 'Referent' ),
						$this->join( 'Filierefp93' ),
						$this->Actionfp93->join( 'Prestatairefp93' ),
						$this->Filierefp93->join( 'Categoriefp93' ),
						$this->Filierefp93->Categoriefp93->join( 'Thematiquefp93' ),
					),
					'conditions' => array(
						"{$this->alias}.id" => $id
					)
				);
				$data = $this->find( 'first', $query );
// debug( $data[$this->alias] );

				if( empty( $data ) || $data[$this->alias]['statut'] == '99annulee' ) {
					throw new InternalErrorException();
				}

				// Récupération des modes de transmissions
				$query = array(
					'fields' => array(
						'Ficheprescription93Modtransmfp93.id',
						'Ficheprescription93Modtransmfp93.modtransmfp93_id',
					),
					'conditions' => array(
						'Ficheprescription93Modtransmfp93.ficheprescription93_id' => $id
					)
				);
				$data['Modtransmfp93']['Modtransmfp93'] = (array)$this->Ficheprescription93Modtransmfp93->find( 'list', $query );

				// Récupération des documents dont le bénéficiaire est invité à se munir
				$query = array(
					'fields' => array(
						'Documentbeneffp93Ficheprescription93.id',
						'Documentbeneffp93Ficheprescription93.documentbeneffp93_id',
					),
					'conditions' => array(
						'Documentbeneffp93Ficheprescription93.ficheprescription93_id' => $id
					)
				);
				$data['Documentbeneffp93']['Documentbeneffp93'] = (array)$this->Documentbeneffp93Ficheprescription93->find( 'list', $query );

				// Fin de la Récupération des données
				$return = $data;

				$return[$this->alias]['structurereferente_id'] = $data['Referent']['structurereferente_id'];
				$return[$this->alias]['referent_id'] = "{$data['Referent']['structurereferente_id']}_{$data[$this->alias]['referent_id']}";

				$return[$this->alias]['numconvention'] = $data['Actionfp93']['numconvention'];

				$return[$this->alias]['actionfp93_id'] = $data[$this->alias]['actionfp93_id'];
				$return[$this->alias]['prestatairefp93_id'] = $data['Actionfp93']['prestatairefp93_id'];
				$return[$this->alias]['filierefp93_id'] = $data['Actionfp93']['filierefp93_id'];
				$return[$this->alias]['categoriefp93_id'] = $data['Filierefp93']['categoriefp93_id'];
				$return[$this->alias]['thematiquefp93_id'] = $data['Categoriefp93']['thematiquefp93_id'];
				$return[$this->alias]['typethematiquefp93_id'] = $data['Thematiquefp93']['type'];
			}

			// Formattage de l'adresse
			$return['Instantanedonneesfp93']['benef_adresse'] =
				$return['Instantanedonneesfp93']['benef_numvoie']
				.' '.value( ClassRegistry::init( 'Option' )->typevoie(), $return['Instantanedonneesfp93']['benef_typevoie'] )
				.' '.$return['Instantanedonneesfp93']['benef_nomvoie']
				.( !empty( $return['Instantanedonneesfp93']['benef_complideadr'] ) ? "\n".$return['Instantanedonneesfp93']['benef_complideadr'] : '' )
				.( !empty( $return['Instantanedonneesfp93']['benef_compladr'] ) ? "\n".$return['Instantanedonneesfp93']['benef_compladr'] : '' );

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
							$this->Instantanedonneesfp93->fields()
						),
						'contain' => false,
						'joins' => array(
							$this->join( 'Instantanedonneesfp93' )
						),
						'conditions' => array(
							"{$this->alias}.id" => $id
						)
					)
				);

				unset( $ficheprescription[$this->alias]['created'], $ficheprescription[$this->alias]['modified'] );
			}

			$data = Hash::merge( $ficheprescription, $data );

			// Certains champs sont désactivés via javascript et ne sont pas renvoyés
			$autres = Hash::get( $this->options( array( 'allocataire' => false, 'find' => false, 'autre' => true ) ), 'Autre' );

			$value = Hash::get( $data, 'Ficheprescription93.personne_recue' );
			if( $value !== '0' ) {
				$data['Ficheprescription93']['motifnonreceptionfp93_id'] = null;
				$data['Ficheprescription93']['personne_nonrecue_autre'] = null;
			}
			else if( !in_array( $data['Ficheprescription93']['motifnonreceptionfp93_id'], $autres['Ficheprescription93']['motifnonreceptionfp93_id'] ) ) {
				$data['Ficheprescription93']['personne_nonrecue_autre'] = null;
			}

			$value = Hash::get( $data, 'Ficheprescription93.personne_retenue' );
			if( $value !== '0' ) {
				$data['Ficheprescription93']['motifnonretenuefp93_id'] = null;
				$data['Ficheprescription93']['personne_nonretenue_autre'] = null;
			}
			else if( !in_array( $data['Ficheprescription93']['motifnonretenuefp93_id'], $autres['Ficheprescription93']['motifnonretenuefp93_id'] ) ) {
				$data['Ficheprescription93']['personne_nonretenue_autre'] = null;
			}

			$value = Hash::get( $data, 'Ficheprescription93.personne_souhaite_integrer' );
			if( $value !== '0' ) {
				$data['Ficheprescription93']['motifnonsouhaitfp93_id'] = null;
				$data['Ficheprescription93']['personne_nonsouhaite_autre'] = null;
			}
			else if( !in_array( $data['Ficheprescription93']['motifnonsouhaitfp93_id'], $autres['Ficheprescription93']['motifnonsouhaitfp93_id'] ) ) {
				$data['Ficheprescription93']['personne_nonsouhaite_autre'] = null;
			}

			$value = Hash::get( $data, 'Ficheprescription93.personne_a_integre' );
			if( $value === '' ) {
				$data['Ficheprescription93']['personne_date_integration'] = null;
				$data['Ficheprescription93']['motifnonintegrationfp93_id'] = null;
				$data['Ficheprescription93']['personne_nonintegre_autre'] = null;
			}
			else if( $value === '0' ) {
				$data['Ficheprescription93']['personne_date_integration'] = null;
			}
			else if( $value === '1' ) {
				$data['Ficheprescription93']['motifnonintegrationfp93_id'] = null;
				$data['Ficheprescription93']['personne_nonintegre_autre'] = null;
			}
			else if( $value === '1' && !in_array( $data['Ficheprescription93']['motifnonintegrationfp93_id'], $autres['Ficheprescription93']['motifnonintegrationfp93_id'] ) ) {
				$data['Ficheprescription93']['personne_nonintegre_autre'] = null;
			}

			// Cases à cocher "Le bénéficiaire est invité à se munir de"
			$values = (array)Hash::get( $data, 'Documentbeneffp93.Documentbeneffp93' );
			if( count( array_intersect( $values, $autres['Ficheprescription93']['documentbeneffp93_id'] ) ) == 0 ) {
				$data['Ficheprescription93']['documentbeneffp93_autre'] = null;
			}

			// Modification de l'état suivant les données
			$statut = '01renseignee';
			if( dateComplete(  $data, 'Ficheprescription93.date_signature' ) ) {
				$statut = '02signee';
			}
			if( $statut == '02signee' && dateComplete(  $data, 'Ficheprescription93.date_transmission' ) ) {
				$statut = '03transmise_partenaire';
			}
			if( $statut == '03transmise_partenaire' && dateComplete(  $data, 'Ficheprescription93.date_retour' ) ) {
				$statut = '04effectivite_renseignee';
			}
			if( $statut == '04effectivite_renseignee' && Hash::get(  $data, 'Ficheprescription93.personne_recue' ) != '' ) {
				$statut = '05suivi_renseigne';
			}
			$data[$this->alias]['statut'] = $statut;

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
						'Structurereferente.numfax',
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

			if( $data[$this->alias]['statut'] == '01renseignee' || empty( $id ) ) {
				$instantanedonneesfp93 = $this->Instantanedonneesfp93->getInstantane( $personne_id );
				$data = Hash::merge( $instantanedonneesfp93, $data );
			}

			if( !empty( $referent ) ) {
				$data = Hash::merge(
					$data,
						array(
						'Instantanedonneesfp93' => array(
							'referent_fonction' => $referent['Referent']['fonction'],
							'structure_name' => $referent['Structurereferente']['lib_struc'],
							'structure_num_voie' => $referent['Structurereferente']['num_voie'],
							'structure_type_voie' => $referent['Structurereferente']['type_voie'],
							'structure_nom_voie' => $referent['Structurereferente']['nom_voie'],
							'structure_code_postal' => $referent['Structurereferente']['code_postal'],
							'structure_ville' => $referent['Structurereferente']['ville'],
							'structure_tel' => $referent['Structurereferente']['numtel'],
							'structure_fax' => $referent['Structurereferente']['numfax'],
							'referent_email' => $referent['Referent']['email'],
						)
					)
				);
			}
			// Fin Instantanedonnees93

			$success = true;
			// Différenciation action PDI / Hors PDI
			// TODO: factoriser
			$typethematiquefp93_id = Hash::get( $data, "{$this->alias}.typethematiquefp93_id" );
			if( $typethematiquefp93_id === 'horspdi' ) {
				$conditions = array(
					'Thematiquefp93' => array(
						'type' => 'horspdi',
						'name' => Hash::get( $data, "{$this->alias}.thematiquefp93" ),
					)
				);
				$thematiquefp93_id = $this->Filierefp93->Categoriefp93->Thematiquefp93->createOrUpdate( $conditions );
				$success = $success && !empty( $thematiquefp93_id );
				if( $success ) {
					$conditions = array(
						'Categoriefp93' => array(
							'thematiquefp93_id' => $thematiquefp93_id,
							'name' => Hash::get( $data, "{$this->alias}.categoriefp93" ),
						)
					);
					$categoriefp93_id = $this->Filierefp93->Categoriefp93->createOrUpdate( $conditions );
					$success = $success && !empty( $categoriefp93_id );
					if( $success ) {
						$conditions = array(
							'Filierefp93' => array(
								'categoriefp93_id' => $categoriefp93_id,
								'name' => Hash::get( $data, "{$this->alias}.filierefp93" ),
							)
						);
						$filierefp93_id = $this->Filierefp93->createOrUpdate( $conditions );
						$success = $success && !empty( $filierefp93_id );
						if( $success ) {
							$data[$this->alias]['filierefp93_id'] = $filierefp93_id;
							$conditions = array(
								'Prestatairefp93' => array(
									'name' => Hash::get( $data, "{$this->alias}.prestatairefp93" ),
								)
							);
							$prestatairefp93_id = $this->Actionfp93->Prestatairefp93->createOrUpdate( $conditions );
							$success = $success && !empty( $prestatairefp93_id );
							if( $success ) {
								$data[$this->alias]['prestatairefp93_id'] = $prestatairefp93_id;
								$conditions = array(
									'Adresseprestatairefp93' => array(
										'prestatairefp93_id' => $prestatairefp93_id,
										'adresse' => Hash::get( $data, "{$this->alias}.prestatairefp93_adresse" ),
										'codepos' => Hash::get( $data, "{$this->alias}.prestatairefp93_codepos" ),
										'localite' => Hash::get( $data, "{$this->alias}.prestatairefp93_localite" ),
										'tel' => Hash::get( $data, "{$this->alias}.prestatairefp93_tel" ),
										'fax' => Hash::get( $data, "{$this->alias}.prestatairefp93_fax" ),
										'email' => Hash::get( $data, "{$this->alias}.prestatairefp93_email" ),
									)
								);
								$adresseprestatairefp93_id = $this->Actionfp93->Prestatairefp93->Adresseprestatairefp93->createOrUpdate( $conditions );
								$success = $success && !empty( $adresseprestatairefp93_id );
							}
						}
					}
				}
			}

			if( $typethematiquefp93_id === 'pdi' ) {
				$data = Hash::merge(
					$data,
					array(
						$this->alias => array(
							'actionfp93' => null,
						)
					)
				);
			}
			else {
				$data = Hash::merge(
					$data,
					array(
						$this->alias => array(
							'actionfp93_id' => null,
						)
					)
				);
			}

			// Sauvegarde de la fiche
			$this->create( $data );
			$success = ( $this->save() !== false ) && $success;

			$nivetu = Hash::get( $data, 'Instantanedonneesfp93.benef_nivetu' );
			$query = array(
				'fields' => array(
					'Dsp.id',
					'Dsp.nivetu',
					'DspRev.id',
					'DspRev.nivetu',
				),
				'contain' => false,
				'joins' => array(
					$this->Personne->join(
						'Dsp',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'Dsp.id IN ( '.$this->Personne->Dsp->sqDerniereDsp( 'Personne.id' ).' )'
							)
						)
					),
					$this->Personne->join(
						'DspRev',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'DspRev.id IN ( '.$this->Personne->DspRev->sqDerniere( 'Personne.id' ).' )'
							)
						)
					),
				),
				'conditions' => array(
					'Personne.id' => $personne_id
				)
			);
			$oldRecord = $this->Personne->find( 'first', $query );

			$newRecord = array();
			$newModelName = null;

			if( !empty( $oldRecord['DspRev']['id'] ) ) {
				if( $oldRecord['DspRev']['nivetu'] !== $nivetu ) {
					$oldModelName = 'DspRev';
					$newModelName = 'DspRev';
					$linkedSuffix = '';
				}
			}
			// Pas de DspRev mais une Dsp
			else if( !empty( $oldRecord['Dsp']['id'] ) ) {
				if( $oldRecord['Dsp']['nivetu'] !== $nivetu ) {
					$oldModelName = 'Dsp';
					$newModelName = 'DspRev';
					$linkedSuffix = 'Rev';
				}
			}
			else if( !empty( $nivetu ) ) {
				$oldModelName = 'Dsp';
				$newModelName = 'Dsp';
				$linkedSuffix = '';
			}

			if( $newModelName !== null ) {
				// Début
				$removePaths = array(
					"{$oldModelName}.id",
					"{$oldModelName}.created",
					"{$oldModelName}.modified",
				);
				$replacements = array( 'Dsp' => 'DspRev' );

				$query = array(
					'contain' => array(),
					'conditions' => array(
						"{$oldModelName}.id" => $oldRecord[$oldModelName]['id']
					)
				);
				foreach( $this->Personne->{$oldModelName}->hasMany as $alias => $params ) {
					if( strstr( $alias, 'Detail' ) !== false ) {
						$query['contain'][] = $alias;

						$removePaths[] = "{$alias}.{n}.id";
						$removePaths[] = "{$alias}.{n}.{$params['foreignKey']}";

						$replacements[$alias] = "{$alias}{$linkedSuffix}";
					}
				}
				$newRecord = $this->Personne->{$oldModelName}->find( 'first', $query );

				foreach( $removePaths as $removePath ) {
					$newRecord = Hash::remove( $newRecord, $removePath );
				}

				$newRecord = array_words_replace( $newRecord, $replacements );
				$newRecord[$newModelName]['personne_id'] = $personne_id;
				$newRecord[$newModelName]['dsp_id'] = Hash::get( $oldRecord, 'Dsp.id' );
				$newRecord[$newModelName]['nivetu'] = $nivetu;

				$success = $this->saveResultAsBool(
					$this->Personne->{$newModelName}->saveAll(
						$newRecord,
						array( 'atomic' => false, 'deep' => true )
					)
				) && $success;
			}
			// Fin

			// Instantané données
			$data['Instantanedonneesfp93']['ficheprescription93_id'] = $this->id;
			$this->Instantanedonneesfp93->create( $data );
			$success = ( $this->Instantanedonneesfp93->save() !== false ) && $success;

			if( !$success && empty( $this->validationErrors ) ) {
				$hiddenErrors = array(
					'Instantanedonneesfp93' => $this->Instantanedonneesfp93->validationErrors
				);
				unset( $hiddenErrors['Instantanedonneesfp93']['ficheprescription93_id'] );

				if( !empty( $hiddenErrors ) ) {
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
				$messages['Instantanedonneesfp93.benef_toppersdrodevorsa_notice'] = 'notice';
			}

			$etatdosrsa = Hash::get( $result, 'Situationdossierrsa.etatdosrsa' );
			if( !in_array( $etatdosrsa, (array)Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' ), true ) ) {
				$messages['Instantanedonneesfp93.benef_etatdosrsa_ouverts'] = 'notice';
			}

			return $messages;
		}

		/**
		 * Récupération des informations pour l'impression.
		 *
		 * @param integer $ficheprescription93_id
		 * @param integer $user_id
		 * @return array
		 * @throws NotFoundException
		 */
		public function getDataForPdf( $ficheprescription93_id, $user_id = null ) {
			$data = $this->find(
				'first',
				array(
					'fields' => Hash::merge(
						$this->fields(),
						$this->Instantanedonneesfp93->fields(),
						$this->Referent->fields(),
						$this->Actionfp93->fields(),
						$this->Filierefp93->fields(),
						$this->Actionfp93->Prestatairefp93->fields(),
						$this->Filierefp93->Categoriefp93->fields(),
						$this->Filierefp93->Categoriefp93->Thematiquefp93->fields()
					),
					'contain' => false,
					'joins' => array(
						$this->join( 'Actionfp93', array( 'type' => 'INNER' ) ),
						$this->join( 'Instantanedonneesfp93', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'INNER' ) ),
						$this->Actionfp93->join( 'Filierefp93', array( 'type' => 'INNER' ) ),
						$this->Actionfp93->join( 'Prestatairefp93', array( 'type' => 'INNER' ) ),
						$this->Filierefp93->join( 'Categoriefp93', array( 'type' => 'INNER' ) ),
						$this->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						"{$this->alias}.id" => $ficheprescription93_id
					)
				)
			);

			if( empty( $data ) ) {
				throw new NotFoundException();
			}

			if( !empty( $user_id ) ) {
				$User = ClassRegistry::init( 'User' );
				$user = $User->find(
					'first',
					array(
						'fields' => $User->fields(),
						'contain' => false,
						'conditions' => array(
							'User.id' => $user_id
						)
					)
				);
				unset( $user['User']['password'] );

				$data = Hash::merge( $data, $user );
			}

			return $data;
		}

		/**
		 * Retourne le chemin relatif du modèle de document à utiliser pour
		 * l'enregistrement du PDF.
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return 'Ficheprescription93/ficheprescription.odt';
		}

		/**
		 * Retourne le PDF par défaut généré par les appels aux méthodes
		 * getDataForPdf, modeleOdt, options et à la méthode ged du behavior
		 * Gedooo,
		 *
		 * @param integer $id Id de la fiche de prescription
		 * @param integer $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $ficheprescription93_id, $user_id = null ) {
			$data = $this->getDataForPdf( $ficheprescription93_id, $user_id );
			$modeleodt = $this->modeleOdt( $data );
			$options = $this->options( array( 'allocataire' => true ) );

			// TODO: un paramètre à options()
			$options = Hash::merge(
				$options,
				array(
					'Instantanedonnees93' => array(
						'benef_typevoie' => $options['Adresse']['typevoie'],
						'benef_qual' => $options['Personne']['qual'],
						'structure_type_voie' => $options['Adresse']['typevoie'],
					),
					'Referent' => array(
						'qual' => $options['Personne']['qual']
					),
					'Type' => array(
						'voie' => $options['Adresse']['typevoie']
					)
				)
			);

			return $this->ged( $data, $modeleodt, true, $options );
		}
	}
?>