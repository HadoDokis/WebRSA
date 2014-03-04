<?php
	/**
	 * Code source de la classe Actionfp93.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Actionfp93 ...
	 *
	 * @package app.Model
	 */
	class Actionfp93 extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Actionfp93';

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
		 * Associations "Belongs to".
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Filierefp93' => array(
				'className' => 'Filierefp93',
				'foreignKey' => 'filierefp93_id',
				'conditions' => null,
				'type' => null,
				'fields' => null,
				'order' => null,
				'counterCache' => null
			),
			'Prestatairefp93' => array(
				'className' => 'Prestatairefp93',
				'foreignKey' => 'prestatairefp93_id',
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
			'Ficheprescription93' => array(
				'className' => 'Ficheprescription93',
				'foreignKey' => 'actionfp93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
			),
		);

		public function jsonNumconvention( array $data = array() ) {
			$prefix = Hash::get( $data, 'prefix' );
			$path = Hash::get( $data, 'path' );
			$domId = Inflector::camelize( str_replace( '.', '_', $path ) );
			$value = Hash::get( $data, $path );

			$query = array(
				'fields' => array(
					'UPPER( "Actionfp93"."numconvention" ) AS "Actionfp93__numconvention"',
					'( UPPER( "Actionfp93"."numconvention" ) || \': \' || "Actionfp93"."name" ) AS "Actionfp93__name"',
					'Actionfp93.id',
					'Actionfp93.prestatairefp93_id',
					'Actionfp93.filierefp93_id',
					'Filierefp93.categoriefp93_id',
					'Categoriefp93.thematiquefp93_id',
					'Thematiquefp93.type',
				),
				'joins' => array(
					$this->join( 'Filierefp93', array( 'type' => 'INNER' ) ),
					$this->join( 'Prestatairefp93', array( 'type' => 'INNER' ) ),
					$this->Filierefp93->join( 'Categoriefp93', array( 'type' => 'INNER' ) ),
					$this->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array(
					'UPPER( "Actionfp93"."numconvention" ) LIKE' => '%'.strtoupper( $value ).'%'
				),
				'order' => array(
					'Actionfp93.numconvention ASC'
				)
			);

			$results = $this->find( 'all', $query );
$this->log( var_export( $results, true ), LOG_DEBUG );
			$return = array();
			if( !empty( $results ) ) {
				foreach( $results as $result ) {
					$return[] = array(
						'name' => $result['Actionfp93']['name'],
						"{$domId}" => $result['Actionfp93']['numconvention'],
						// -----------------------------------------------------
						'values' => array(
							"{$prefix}Thematiquefp93Type" => $result['Thematiquefp93']['type'],
							"{$prefix}Categoriefp93Thematiquefp93Id" => "{$result['Thematiquefp93']['type']}_{$result['Categoriefp93']['thematiquefp93_id']}",
							"{$prefix}Filierefp93Categoriefp93Id" => "{$result['Categoriefp93']['thematiquefp93_id']}_{$result['Filierefp93']['categoriefp93_id']}",
							"{$prefix}Actionfp93Filierefp93Id" => "{$result['Filierefp93']['categoriefp93_id']}_{$result['Actionfp93']['filierefp93_id']}",
							"{$prefix}Actionfp93Prestatairefp93Id" => "{$result['Actionfp93']['filierefp93_id']}_{$result['Actionfp93']['prestatairefp93_id']}",
							// FIXME
							"{$prefix}Ficheprescription93Actionfp93Id" => "{$result['Actionfp93']['filierefp93_id']}_{$result['Actionfp93']['prestatairefp93_id']}_{$result['Actionfp93']['id']}",
						)
					);
				}
			}

			return $return;
		}
	}
?>