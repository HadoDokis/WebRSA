<?php
	/**
	 * Fichier source de la classe PersonneReferent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PersonneReferent ...
	 *
	 * @package app.Model
	 */
	class PersonneReferent extends AppModel
	{
		public $name = 'PersonneReferent';

		public $actsAs = array(
			'Allocatairelie',
			'Formattable' => array(
				'suffix' => array( 'referent_id' )
			),
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe'
				)
			)
		);

		public $validate = array(
			'referent_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dddesignation' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				)
			),
			'dfdesignation' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.',
					'allowEmpty' => true,
				),
				array(
					'rule' => array( 'compareDates', 'dddesignation', '>=' ),
					'message' => 'La date de fin de désignation doit être au moins la même que la date de début de désignation'
				)
			)
		);

		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'PersonneReferent\'',
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
			)
		);

		/**
		 * Retourne une sous-requête permettant de connaître le dernier référent de parcours pour un
		 * allocataire donné.
		 *
		 * @param string $field Le champ Personne.id sur lequel faire la sous-requête
		 * @return string
		 */
		public function sqDerniere( $field, $cloture = null ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false, false );
			$conditionCloture = null;
			if( !is_null( $cloture ) ) {
				if( $cloture ) {
					$conditionCloture = "AND {$table}.dfdesignation IS NOT NULL";
				}
				else {
					$conditionCloture = "AND {$table}.dfdesignation IS NULL";
				}

			}
			return "SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.personne_id = ".$field."
						$conditionCloture
					ORDER BY {$table}.dddesignation DESC
					LIMIT 1";
		}

		/**
		 * Lors de l'ajout d'une orientation ou  d'un référent ($modelName), on ajoute un nouveau référent de parcours si celui-ci a été précisé lors de la création.
		 *
		 * @param array $data
		 * @param string $modelName
		 * @param string $datefindesignation
		 * @return boolean
		 */
		public function referentParModele( $data, $modelName, $datefindesignation ) {
			$saved = true;

			$last_referent = $this->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id'=> $data[$modelName]['personne_id']
					),
					'order' => array(
						'PersonneReferent.dddesignation DESC',
						'PersonneReferent.id DESC'
					),
					'contain' => false
				)
			);

			list( $structurereferente_id, $referent_id ) = explode( '_', $data[$modelName]['referent_id'] );

			if ( !empty( $referent_id ) && ( empty( $last_referent ) || ( isset( $last_referent['PersonneReferent']['referent_id'] ) && !empty( $last_referent['PersonneReferent']['referent_id'] ) && $last_referent['PersonneReferent']['referent_id'] != $referent_id ) ) ) {
				if ( !empty( $last_referent ) && empty( $last_referent['PersonneReferent']['dfdesignation'] ) ) {
					$last_referent['PersonneReferent']['dfdesignation'] = $data[$modelName][$datefindesignation];
					$this->create( $last_referent );
					$saved = $this->save( $last_referent ) && $saved;
				}

				$personnereferent['PersonneReferent'] = array(
					'personne_id' => $data[$modelName]['personne_id'],
					'referent_id' => $referent_id,
					'structurereferente_id' => $structurereferente_id,
					'dddesignation' => $data[$modelName][$datefindesignation]
				);
				$this->create( $personnereferent );
				$saved = $this->save( $personnereferent ) && $saved;
			}

			return $saved;
		}


		/**
		 * Sous-requête permettant de savoir si une entrée existe dans la table personnes_referents
		 * pour une entrée de la table referents, et que la date de fin de désignation du référent
		 * n'est pas remplie.
		 *
		 * @param Model $Model
		 * @param string $fieldName Si null, renvoit uniquement la sous-reqête,
		 * 	sinon renvoit la sous-requête aliasée pour un champ (avec l'alias du
		 * 	modèle).
		 * @param string $modelAlias Si null, utilise l'alias de la class PersonneReferent, sinon la valeur donnée.
		 * @return string
		 */
		public function sqNbLiesActifs( Model $Model, $fieldId = 'Referent.id', $fieldName = null, $modelAlias = null ) {
			$alias = Inflector::underscore( $this->alias );

			$modelAlias = ( is_null( $modelAlias ) ? $this->alias : $modelAlias );

			$sq = $this->sq(
					array(
						'fields' => array(
							"COUNT( {$alias}.id )"
						),
						'alias' => $alias,
						'conditions' => array(
							"{$alias}.referent_id = $fieldId",
							"{$alias}.dfdesignation IS NULL"
						)
					)
			);

			if( !is_null( $fieldName ) ) {
				$sq = "( {$sq} ) AS \"{$modelAlias}__{$fieldName}\"";
			}

			return $sq;
		}

		/**
		 * Si je possédais un référent et que l'actuel n'existe pas -> clôture
		 * Si je possédais un référent et qu'il est différent de l'actuel -> clôture, création
		 * Si je possédais un référent et qu'il est le même que l'actuel -> rien ne se passe
		 * Si je ne possédais pas de référent et qu'il y en a un -> création
		 * Si je ne possédais pas de référent et qu'il n'y en pas -> rien ne se passe
		 *
		 * @param integer $personne_id
		 * @param integer $referent_id
		 * @param array $personne_referent
		 * @return boolean
		 */
		public function changeReferentParcours( $personne_id, $referent_id, $personne_referent ) {
			$success = true;

			if( !empty( $personne_referent ) ) {
				$personne_referent[$this->alias]['structurereferente_id'] = prefix( $personne_referent[$this->alias]['structurereferente_id'] );
				$personne_referent[$this->alias]['referent_id'] = suffix( $personne_referent[$this->alias]['referent_id'] );
			}

			$personne_referent_actuel = $this->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'contain' => false
				)
			);

			// Si je ne possédais pas de référent et qu'il n'y en pas -> rien ne se passe
			if( empty( $personne_referent_actuel ) && empty( $personne_referent ) ) {
				return $success;
			}

			// Si je possédais un référent et qu'il est le même que l'actuel -> rien ne se passe
			$referentDejaAssigne = (
				!empty( $personne_referent_actuel )
				&& !empty( $personne_referent )
				&& ( $personne_referent_actuel['PersonneReferent']['structurereferente_id'] == $personne_referent['PersonneReferent']['structurereferente_id'] )
				&& ( $personne_referent_actuel['PersonneReferent']['referent_id'] == $personne_referent['PersonneReferent']['referent_id'] )
			);
			if( $referentDejaAssigne ) {
				return $success;
			}

			if( !empty( $personne_referent_actuel ) ) {
				$dfdesignation = (
					( isset( $personne_referent['PersonneReferent']['dddesignation'] ) && !empty( $personne_referent['PersonneReferent']['dddesignation'] ) )
					? $personne_referent['PersonneReferent']['dddesignation']
					: date( 'Y-m-d' )
				);

				$this->id = $personne_referent_actuel['PersonneReferent']['id'];
				$success = $this->saveField( 'dfdesignation', $dfdesignation ) && $success;
			}

			if( !empty( $referent_id ) ) {
				$this->create( $personne_referent );
				return $this->save() && $success;
			}

			return $success;
		}
	}
?>