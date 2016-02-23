<?php
	/**
	 * Code source de la classe Tag.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */

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
			$this->updateEtatTagByConditions( array( 'Tag.id' => $this->id ) );
		}
	}
?>