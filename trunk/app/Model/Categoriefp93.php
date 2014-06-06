<?php
	/**
	 * Code source de la classe Categoriefp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Categoriefp93 ...
	 *
	 * @package app.Model
	 */
	class Categoriefp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Categoriefp93';

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
			'Cataloguepdifp93',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Thematiquefp93' => array(
				'className' => 'Thematiquefp93',
				'foreignKey' => 'thematiquefp93_id',
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
			'Filierefp93' => array(
				'className' => 'Filierefp93',
				'foreignKey' => 'categoriefp93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
		);

		/**
		 * Retourne une condition qui est en fait une sous-requête, avec les
		 * jointures nécessaires pour atteindre le modèle Actionfp93, et comprenant
		 * les conditions passées en paramètre.
		 *
		 * @param array $conditions Les conditions à appliquer sur le modèle Actionfp93
		 * @return string
		 */
		public function getActionfp93Condition( array $conditions ) {
			$conditions[] = "Filierefp93.categoriefp93_id = {$this->alias}.{$this->primaryKey}";

			$query = array(
				'alias' => 'Filierefp93',
				'fields' => array( 'Filierefp93.categoriefp93_id' ),
				'joins' => array(
					$this->Filierefp93->join( 'Actionfp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions
			);

			$replacements = array(
				'Filierefp93' => 'filieresfps93',
				'Actionfp93' => 'actionsfps93',
			);

			$sql = $this->Filierefp93->sq( array_words_replace( $query, $replacements ) );

			return "{$this->alias}.{$this->primaryKey} IN ( {$sql} )";
		}
	}
?>