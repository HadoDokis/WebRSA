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
			/**
			 * INFO : Les modeles liés en fk_value doivent avoir la function dossierId()
			 */
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'fk_value',
				'conditions' => array(
					'Tag.modele = \'Personne\''
				),
				'fields' => '',
				'order' => ''
			),
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'fk_value',
				'conditions' => array(
					'Tag.modele = \'Foyer\''
				),
				'fields' => '',
				'order' => ''
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
					$this->fields(),
					$this->Valeurtag->fields(),
					$this->Valeurtag->Categorietag->fields()
				),
				'joins' => array(
					$this->join('Valeurtag'),
					$this->Valeurtag->join('Categorietag')
				),
				'contain' => false,
				'conditions' => $conditions
			);
		}
	}
?>