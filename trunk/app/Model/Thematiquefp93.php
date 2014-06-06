<?php
	/**
	 * Code source de la classe Thematiquefp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Thematiquefp93 ...
	 *
	 * @package app.Model
	 */
	class Thematiquefp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Thematiquefp93';

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
			'Validation2.Validation2Formattable' => array(
				'Validation2.Validation2DefaultFormatter' => array(
					'suffix'  => '/_{0,1}id$/'
				)
			),
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Categoriefp93' => array(
				'className' => 'Categoriefp93',
				'foreignKey' => 'thematiquefp93_id',
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
			$conditions[] = "Categoriefp93.thematiquefp93_id = {$this->alias}.{$this->primaryKey}";

			$query = array(
				'alias' => 'Categoriefp93',
				'fields' => array( 'Categoriefp93.thematiquefp93_id' ),
				'joins' => array(
					$this->Categoriefp93->join( 'Filierefp93', array( 'type' => 'INNER' ) ),
					$this->Categoriefp93->Filierefp93->join( 'Actionfp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => $conditions
			);

			$replacements = array(
				'Categoriefp93' => 'categoriesfps93',
				'Filierefp93' => 'filieresfps93',
				'Actionfp93' => 'actionsfps93',
			);

			$sql = $this->Categoriefp93->sq( array_words_replace( $query, $replacements ) );

			return "{$this->alias}.{$this->primaryKey} IN ( {$sql} )";
		}
	}
?>