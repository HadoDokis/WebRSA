<?php
	/**
	 * Code source de la classe WebrsaStructurereferentelieeBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	/**
	 * La classe WebrsaStructurereferentelieeBehavior ...
	 *
	 * @package app.Model.Behavior
	 */
	class WebrsaStructurereferentelieeBehavior extends ModelBehavior
	{

		/**
		 * Contains configuration settings for use with individual model objects. This
		 * is used because if multiple models use this Behavior, each will use the same
		 * object instance. Individual model settings should be stored as an
		 * associative array, keyed off of the model name.
		 *
		 * @var array
		 * @see Model::$alias
		 */
		public $settings = array();

		/**
		 * "Live" cache pour les clés étrangères.
		 *
		 * @var array
		 */
		protected $_cache = array();

		/**
		 * Setup this behavior with the specified configuration settings.
		 *
		 * @param Model $Model Model using this behavior
		 * @param array $config Configuration settings for $model
		 * @return void
		 */
		public function setup( Model $Model, $config = array() ) {
			parent::setup( $Model, $config );
		}

		public function structurereferenteHorszone( Model $Model, $fieldName, array $structuresreferentes_ids, array $params = array() ) {
			$params += array(
				'alias' => 'Referent.horszone'
			);

			if( false === empty( $structuresreferentes_ids ) ) {
				list( $modelName, $fieldName ) = model_field( $fieldName );
				$Dbo = $Model->getDataSource();

				$result = $Dbo->conditions(
					array(
						'NOT' => array( "{$modelName}.{$fieldName}" => $structuresreferentes_ids )
					),
					true,
					false,
					$Model
				);
			}
			else {
				$result = 'FALSE';
			}

			if( false === empty( $params['alias'] ) ) {
				list( $modelName, $fieldName ) = model_field( $params['alias'] );
				return "( {$result} ) AS \"{$modelName}__{$fieldName}\"";
			}

			return $result;
		}

		public function referentHorszone( Model $Model, $fieldName, array $structuresreferentes_ids, array $params = array() ) {
			$params += array(
				'alias' => 'Referent.horszone'
			);

			if( false === empty( $structuresreferentes_ids ) ) {
				list( $modelName, $fieldName ) = model_field( $fieldName );
				$Dbo = $Model->getDataSource();
				$Referent = ClassRegistry::init( 'Referent' );

				$subQuery = array(
					'fields' => array( 'Referent.id' ),
					'conditions' => array(
						"Referent.id = {$modelName}.{$fieldName}",
						'Referent.structurereferente_id' => $structuresreferentes_ids,
					),
					'contain' => false
				);
				$sql = words_replace( $Referent->sq( $subQuery ), array( 'Referent' => 'referents' ) );

				$result = "\"{$modelName}\".\"{$fieldName}\" IN ( {$sql} )";
			}
			else {
				$result = 'FALSE';
			}

			if( false === empty( $params['alias'] ) ) {
				list( $modelName, $fieldName ) = model_field( $params['alias'] );
				return "( {$result} ) AS \"{$modelName}__{$fieldName}\"";
			}

			return $result;
		}

		public function completeQueryHorsZone( Model $Model, array $query, $structuresreferentes_ids = null, array $params = array() ) {
			$structuresreferentes_ids = (array)$structuresreferentes_ids;
			$params += array(
				'structurereferente_id' => null,
				'referent_id' => null,
				'alias' => 'Referent.horszone'
			);

			if( true !== empty( $params['structurereferente_id'] ) ) {
				$query['fields'][] = $this->structurereferenteHorszone( $Model, "{$Model->alias}.{$params['structurereferente_id']}", $structuresreferentes_ids, $params );
			}
			else if( true !== empty( $params['referent_id'] ) ) {
				$query['fields'][] = $this->referentHorszone( $Model, "{$Model->alias}.{$params['referent_id']}", $structuresreferentes_ids, $params );
			}
			else if( false === empty( $params['alias'] ) ) {
				list( $modelName, $fieldName ) = model_field( $params['alias'] );
				$query['fields'][] = "NULL AS \"{$modelName}__{$fieldName}\"";
			}

			return $query;
		}

		// Clés étrangères vers les tables structuresreferentes et referents ?
		// FIXME: liens indirects, comme Questionnaired1pdv93 / Questionnaired2pdv93
		public function links( Model $Model ) {
			if( false === isset( $this->_cache[$Model->name][__FUNCTION__] ) ) {
				if( false === $Model->Behaviors->attached( 'Postgres.PostgresTable' ) ) {
					$Model->Behaviors->attach( 'Postgres.PostgresTable' );
				}

				$foreignKeys = $Model->getPostgresForeignKeysFrom();
				$links = Hash::combine( $foreignKeys, '{s}.From.column', '{s}.To.table' );

				$this->_cache[$Model->name][__FUNCTION__] = array(
					'structurereferente_id' => array_search( 'structuresreferentes', $links ),
					'referent_id' => array_search( 'referents', $links )
				);
			}

			return $this->_cache[$Model->name][__FUNCTION__];
		}
	}
?>