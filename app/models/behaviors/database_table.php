<?php
	/**
	* DatabaseTable behavior class.
	*
	* Behavior class adding methods to perform database operations on a CakePHP
	* model class.
	*
	* PHP version 5
	*
	* @package		app
	* @subpackage	app.models.behaviors
	*/

	/**
	* Behavior class adding methods to perform database operations on a CakePHP
	* model class.
	*
	* @package		app
	* @subpackage	app.model.behaviors
	*/

	class DatabaseTableBehavior extends ModelBehavior
	{
		/**
		* Transforme les $querydata d'un appel "find all" en requête SQL,
		* ce qui permet de faire des sous-requêtes moins dépendantes du SGBD.
		*
		* Les fields sont échappés.
		*
		* INFO: http://book.cakephp.org/view/74/Complex-Find-Conditions (Sub-queries)
		*
		* @param AppModel $model
		* @param array $querydata
		* @return string
		*/

		public function sq( &$model, $querydata ) {
			if( $model->useTable === false ) {
				throw new Exception( "Cannot generate a subquery for model \"{$model->alias}\" since it does not use a table." );
				return array();
			}

			$dbo = $model->getDataSource( $model->useTable );
			$fullTableName = $dbo->fullTableName( $model, true );

			$defaults = array(
				'fields' => null,
				'order' => null,
				'group' => null,
				'limit' => null,
				'table' => $fullTableName,
				'alias' => $model->alias,
				'conditions' => array(),
			);

			$querydata = Set::merge( $defaults, Set::filter( $querydata ) );
			if( empty( $querydata['fields'] ) ) {
				$querydata['fields'] = $dbo->fields( $model );
			}
			else {
				$querydata['fields'] = $dbo->fields( $model, null, $querydata['fields'] );
			}

			return $dbo->buildStatement( $querydata, $model );
		}

		/**
		* Merges a mixed set of string/array conditions
		*
		* @return array
		*/

		protected function _mergeConditions( $query, $assoc ) {
			if( empty( $assoc ) ) {
				return $query;
			}

			if (is_array($query)) {
				return array_merge((array)$assoc, $query);
			}

			if (!empty($query)) {
				$query = array($query);
				if (is_array($assoc)) {
					$query = array_merge($query, $assoc);
				} else {
					$query[] = $assoc;
				}
				return $query;
			}

			return $assoc;
		}

		/**
		*
		*/

		protected function _whichHabtmModel( &$model, $needleModelName ) {
			foreach( $model->hasAndBelongsToMany as $habtmModel => $habtmAssoc ) {
				if( $habtmAssoc['with'] == $needleModelName ) {
					return $habtmModel;
				}
			}
		}

		/**
		*
		*/

		public function join( &$model, $assoc, $params = array(/* 'type' => 'INNER' */) ) {
			// Is the assoc model really associated ?
			if( !isset( $model->{$assoc} ) ) {
				throw new Exception( "Unknown association \"{$assoc}\" for model \"{$model->alias}\"" );
				return array();
			}

			if( $model->useTable === false ) {
				throw new Exception( "Cannot generate a join from model \"{$model->alias}\" since it does not use a table." );
				return array();
			}

			if( $model->{$assoc}->useTable === false ) {
				throw new Exception( "Cannot generate a join to model \"{$model->{$assoc}->alias}\" since it does not use a table." );
				return array();
			}

			// Is the assoc model using the same DbConfig as the model's ?
			if( $model->useDbConfig != $model->{$assoc}->useDbConfig ) {
				throw new Exception( "Database configuration differs: \"{$model->alias}\" ({$model->useDbConfig}) and \"{$assoc}\" ({$model->{$assoc}->useDbConfig})" );
				return array();
			}

			$dbo = $model->getDataSource( $model->useTable );

			// hasOne, belongsTo: OK
			$assocData = $model->getAssociated( $assoc );
			$assocData = Set::merge( $assocData, $params );

			// hasMany
			if( isset( $assocData['association'] ) && $assocData['association'] == 'hasMany' ) {
				$assocData['association'] = 'hasOne';
			}
			// hasAndBelongsToMany
			else if( !isset( $assocData['association'] ) ) {
				$whichHabtmModel = $this->_whichHabtmModel( $model, $assoc );

				if( !empty( $whichHabtmModel ) ) {
					$habtmAssoc = $model->hasAndBelongsToMany[$whichHabtmModel];
					$newAssocData = array(
						'className' => $habtmAssoc['with'],
						'foreignKey' => $habtmAssoc['foreignKey'],
						'conditions' => $habtmAssoc['conditions'],
// 							'fields' => '',
// 							'order' => '',
// 							'limit' => '',
// 							'offset' => '',
// 							'exclusive' => '',
// 							'finderQuery' => '',
// 							'counterQuery' => '',
						'association' => 'hasOne'
					);

					$assocData = Set::merge( $newAssocData, $assocData );
				}
			}

			return array(
				'table' => $dbo->fullTableName( $model->{$assoc} ),
				'alias' => $assoc,
				'type' => isset($assocData['type']) ? $assocData['type'] : 'LEFT',
				'conditions' => trim(
					$dbo->conditions(
							$this->_mergeConditions(
							@$assocData['conditions'],
								$dbo->getConstraint(
									@$assocData['association'],
									$model,
									$model->{$assoc},
									$assoc,
									$assocData
								)
							),
						true,
						false,
						$model
					)
				)
			);
		}

		/**
		* Retourne la liste des champs du modèle.
		*
		* @param AppModel $model
		*/

		public function fields( &$model, $virtualFields = false ) {
			if( $model->useTable === false ) {
				throw new Exception( "Cannot get fields for model \"{$model->alias}\" since it does not use a table." );
				return array();
			}

			$fields = array();
			foreach( array_keys( $model->schema( $virtualFields ) ) as $field ) {
				$fields[] = "{$model->alias}.{$field}";
			}

			return $fields;
		}
	}
?>