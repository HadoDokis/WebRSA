<?php
	/**
	 * Code source de la classe Prestatairefp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Prestatairefp93 ...
	 *
	 * @package app.Model
	 */
	class Prestatairefp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Prestatairefp93';

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
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);

		/**
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Actionfp93' => array(
				'className' => 'Actionfp93',
				'foreignKey' => 'prestatairefp93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
			'Adresseprestatairefp93' => array(
				'className' => 'Adresseprestatairefp93',
				'foreignKey' => 'prestatairefp93_id',
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
		 *
		 * @fixme surcharge... dela méthode du AppModel
		 *
		 * @param string $prefixKeyField
		 * @param string $suffixKeyField
		 * @param string $displayField
		 * @param array $conditions
		 * @param array $modelNames
		 * @return array
		 */
		public function findListPrefixed2( array $conditions = array() ) {
			$query = array(
				'fields' => array(
					'Actionfp93.filierefp93_id',
					'Actionfp93.prestatairefp93_id',
					"{$this->alias}.name",
				),
				'joins' => array(
					$this->join( 'Actionfp93' )
				),
				'contain' => false,
				'conditions' => $conditions,
				'order' => array(
					"{$this->alias}.name ASC",
				),
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $query ) );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find( 'all', $query );

				$results = Hash::combine(
					$results,
					array( '%s_%s', "{n}.Actionfp93.filierefp93_id", "{n}.Actionfp93.prestatairefp93_id" ),
					"{n}.{$this->alias}.{$this->displayField}"
				);

				$modelNames[] = $this->name;
				$modelNames = array_unique( $modelNames );

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, $modelNames );
			}

			return $results;
		}
	}
?>