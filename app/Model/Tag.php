<?php
	/**
	 * Code source de la classe Tag.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Tag ...
	 *
	 * @package app.Model
	 */
	class Tag extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Tag';

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Allocatairelie',
			'Formattable',
			'Gedooo.Gedooo',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Valeurtag' => array(
				'className' => 'Valeurtag',
				'foreignKey' => 'valeurtag_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'EntiteTag' => array(
				'className' => 'EntiteTag',
				'foreignKey' => 'tag_id',
			),
		);

		/**
		 * Récupère les données d'un tag
		 *
		 * @param integer $tag_id
		 * @return array
		 */
		public function findTagById( $tag_id ) {
			return $this->find('first', $this->queryTagByCondition(array('Tag.id' => $tag_id)));
		}

		/**
		 * Trouve tout les tags d'une personne
		 *
		 * @param string $modele
		 * @param integer $id
		 * @return array
		 */
		public function findTagModel( $modele, $id ) {
			$conditions = array(
				'modele' => $modele,
				'fk_value' => $id
			);

			$query = $this->queryTagByCondition($conditions);

			return $this->find('all', $query);
		}

		/**
		 * Renvoi la query de base pour les tags
		 *
		 * @param array $conditions
		 * @return array
		 */
		public function queryTagByCondition( $conditions ) {
			return array(
				'fields' => array_merge(
					$this->EntiteTag->fields(),
					$this->fields(),
					$this->Valeurtag->fields(),
					$this->Valeurtag->Categorietag->fields()
				),
				'joins' => array(
					$this->join('EntiteTag', array('type' => 'INNER')),
					$this->join('Valeurtag'),
					$this->Valeurtag->join('Categorietag')
				),
				'contain' => false,
				'conditions' => $conditions
			);
		}

		/**
		 * Met à jour l'etat du tag
		 *
		 * @param type $conditions
		 */
		public function updateEtatTagByConditions( array $conditions = array() ) {
			// Conditions tags périmés
			$conditionsPerime = $conditions;
			$conditionsPerime[] = array(
				'Tag.limite IS NOT NULL',
				'Tag.limite < NOW()',
			);
			$fieldsPerime = array('Tag.etat' => "'perime'");
			$success = $this->updateAllUnBound($fieldsPerime, $conditionsPerime);

			return $success;
		}

		/**
		 * Calcule l'etat du Tag après chaques modifications
		 *
		 * @param boolean $created
		 */
		public function afterSave( $created ) {
			parent::afterSave($created);
			$this->updateEtatTagByConditions( array( 'Tag.id' => $this->id ) );
		}

		/**
		 * Envoi un personne_id
		 * Si entité est sur le foyer, enverra le premier demandeur trouvé
		 *
		 * @param integer $tag_id
		 * @return integer personne_id
		 */
		public function personneId($tag_id) {
			$query = array(
				'fields' => array(
					'COALESCE("Personne"."id", "FoyerPersonneDem"."id") AS "Tag__personne_id"',
				),
				'joins' => array_merge(
					array(
						$this->join('EntiteTag', array('type' => 'INNER')),
						$this->EntiteTag->join('Personne'),
						$this->EntiteTag->join('Foyer', array('conditions' => array('Personne.id IS NULL'))),
					),
					array_words_replace(
						array(
							$this->EntiteTag->Foyer->join('Personne'),
							$this->EntiteTag->Foyer->Personne->join('Prestation'),
						), array(
							'Personne' => 'FoyerPersonneDem'
						)
					)
				),
				'conditions' => array(
					'Tag.id' => $tag_id,
					'OR' => array(
						'FoyerPersonneDem.id IS NULL',
						'Prestation.rolepers' => 'DEM'
					)
				)
			);

			return Hash::get($this->find('first', $query), 'Tag.personne_id');
		}

		/**
		 * Utile pour faire un filtre de recherche sans jointures sur Tag donc
		 * sans risquer d'ajouter des lignes
		 *
		 * @param array|string|integer $valeurtag_id
		 * @param string|integer $foyer_id mettre <= à 0 pour ignorer
		 * @param string|integer $personne_id mettre <= à 0 pour ignorer
		 * @return string
		 */
		public function sqHasTagValue($valeurtag_id, $foyer_id = '"Foyer"."id"', $personne_id = '"Personne"."id"') {
			$query = array(
				'fields' => 'Tag.id',
				'joins' => array(
					$this->join('EntiteTag', array(
						'type' => 'INNER',
						'conditions' => array(
							'OR' => array(
								array(
									'EntiteTag.modele' => 'Foyer',
									'EntiteTag.fk_value = '.$foyer_id
								),
								array(
									'EntiteTag.modele' => 'Personne',
									'EntiteTag.fk_value = '.$personne_id
								),
							)
						)
					))
				),
				'conditions' => array(
					is_string($valeurtag_id) ? 'Tag.valeurtag_id = '.$valeurtag_id : 'Tag.valeurtag_id' => $valeurtag_id,
					"Tag.etat" => 'encours'
				),
				'limit' => 1
			);

			$sq = words_replace(
				$this->sq($query),
				array('EntiteTag' => 'entites_tags', 'Tag' => 'tags')
			);

			return "(SELECT EXISTS($sq))";
		}
	}
?>