<?php
	/**
	 * Code source de la classe PostgresAutovalidateBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Model.Behavior
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Validation2AutovalidateBehavior', 'Validation2.Model/Behavior' );

	/**
	 * La classe PostgresAutovalidateBehavior ajoute aux fonctionnalités de la
	 * classe Validation2AutovalidateBehavior la possibilité de lire des
	 * règles de validation à partir de contraintes postgresql.
	 *
	 * Ces contraintes doivent porter un nom commençant par cakephp_validate_
	 * pour être automatiquement ajoutées aux contraintes du modèle.
	 *
	 * @package Postgres
	 * @subpackage Model.Behavior
	 */
	class PostgresAutovalidateBehavior extends Validation2AutovalidateBehavior
	{
		/**
		 * Liste des règles cakephp_validate_ groupées par alias du modèle.
		 *
		 * @var array
		 */
		protected $_checkRules = array();

		/**
		 * Lecture des contraintes dont le nom commence par cakephp_validate_.
		 *
		 * @param Model $Model
		 */
		protected function _readTableConstraints( Model $Model ) {
			$cacheKey = $this->methodCacheKey( $Model, __CLASS__, __FUNCTION__ );
			$this->_checkRules[$Model->alias] = Cache::read( $cacheKey );

			if( $this->_checkRules[$Model->alias] === false ) {
				$Dbo = $Model->getDataSource();

				$sql = "SELECT
								istc.table_catalog,
								istc.table_schema,
								istc.table_name,
								istc.constraint_name,
								iscc.check_clause
							FROM information_schema.check_constraints AS iscc
								INNER JOIN information_schema.table_constraints AS istc ON (
									istc.constraint_name = iscc.constraint_name
								)
							WHERE
								istc.table_catalog = '{$Dbo->config['database']}'
								AND istc.table_schema = '{$Dbo->config['schema']}'
								AND istc.table_name = '".$Dbo->fullTableName( $Model, false, false )."'
								AND istc.constraint_type = 'CHECK'
								AND iscc.check_clause ~ 'cakephp_validate_.*(.*)';";

				$checks = $Model->query( $sql );

				$this->_checkRules[$Model->alias] = array();
				foreach( $checks as $check ) {
					$this->_checkRules[$Model->alias] = $this->_addGuessedPostgresConstraint( $Model, $this->_checkRules[$Model->alias], $check[0]['check_clause'] );
				}

				Cache::write( $cacheKey, $this->_checkRules[$Model->alias] );
			}
		}

		/**
		 * Configuration du behavior.
		 *
		 * @param Model $Model Le modèle qui utilise ce behavior
		 * @param array $config La configuration à appliquer
		 */
		public function setup( Model $Model, $config = array() ) {
			if( Hash::get( $Model->getDataSource()->config, 'datasource' ) === 'Database/Postgres' ) {
				$this->defaultConfig['rules']['postgres_constraints'] = true;
			}

			$this->_readTableConstraints( $Model );
			parent::setup( $Model, $config );
		}

		/**
		 * Déduction des règles de validation pour un champ d'un modèle donné.
		 *
		 * @param Model $Model
		 * @param string $field
		 * @param array $params
		 * @param array $indexes
		 * @return array
		 */
		public function deduceFieldValidationRules( Model $Model, $field, $params, $indexes = array() ) {
			$rules = parent::deduceFieldValidationRules( $Model, $field, $params, $indexes );

			if( Hash::get( $this->settings, "{$Model->alias}.rules.postgres_constraints" ) ) {
				if( isset( $this->_checkRules[$Model->alias][$field] ) && !empty( $this->_checkRules[$Model->alias][$field] ) ) {
					foreach( $this->_checkRules[$Model->alias][$field] as $rule ) {
						$rules[$rule['rule'][0]] = $rule;
					}
				}
			}

			return $rules;
		}

		/**
		 * Lecture des paramètres de la contrainte postgresql.
		 *
		 * @param array $parameters
		 * @return array
		 */
		protected function _extractPostgresParams( array $parameters ) {
			$params = array();

			if( isset( $parameters['params'] ) ) {
				if( preg_match( '/^ARRAY\[(.*)\]$/', $parameters['params'], $matches ) ) {
//					if( preg_match_all( '/\'([^\']+)\'/U', $matches[1], $values ) ) {
//						$params = array( $values[1] );
//					}
//					else
					if( preg_match_all( '/([^, ]+)/', $matches[1], $values ) ) {
						$params = array( $values[1] );
					}
				}
				else if( preg_match_all( '/([^, ]+),{0,1}/', $parameters['params'], $matches ) ) {
					$params = $matches[1];
				}
			}

			foreach( $params as $i => $param ) {
				if( is_string( $param ) && strtolower( $param ) == 'null' ) {
					$params[$i] = null;
				}
			}

			return $params;
		}

		/**
		 * Complète les $règles avec les règles déduites des contraintes postgresql.
		 *
		 * @param Model $Model
		 * @param array $rules
		 * @param string $code
		 * @return array
		 */
		/*protected function _addGuessedPostgresConstraint( Model $Model, array $rules, $code ) {
			// INFO: IIF the check is "xx()" or "xx() AND xx()" etc.
			// Remove extra parenthesis
			$code = preg_replace( '/^( *\(+ *)? *(.+) *(?(1) *\)+ *)$/', '\2', $code );
			// Transform '.*'::text
			$code = preg_replace( '/\'([^\']+)\'::[^,\)\]]+/', '\1', $code );
			// Transform (0)::numeric
			$code = preg_replace( '/\(([^\(\)]+)\)::[^,\)\]]+/', '\1', $code );
			// Transform NULL::character varying
			$code = preg_replace( '/NULL::[^,\)]+/', 'NULL', $code );

			if( preg_match_all( '/cakephp_validate_.*\((\(.+\).*|.+)\)/U', $code, $matches, PREG_PATTERN_ORDER ) ) {
				foreach( $matches[0] as $rule ) {
					// INFO: '.*'::text and (0)::numeric are tramsformed above
					// if( preg_match( '/^cakephp_validate_(?<function>[^\(]+)\((?<field>\(.*\)::\w+|\w+)(, *(?<params>.*)){0,1}\)$/', $rule, $parameters ) ) {
					if( preg_match( '/^cakephp_validate_(?<function>[^\(]+)\((?<field>\(.*\)|\w+)(, *(?<params>.*)){0,1}\)$/', $rule, $parameters ) ) {
						$ruleName = Inflector::camelize( $parameters['function'] );
						$ruleName[0] = strtolower( $ruleName[0] );

						$field = trim( $parameters['field'] );
						$params = $this->_extractPostgresParams( $parameters );

						$rules[$field][$ruleName] = $this->normalizeValidationRule( $Model, array( 'rule' => array_merge( array( $ruleName ), $params ), 'allowEmpty' => true ) );
					}
				}
			}

			return $rules;
		}*/

		/**
		 * Complète les $règles avec les règles déduites des contraintes postgresql.
		 *
		 * @param Model $Model
		 * @param array $rules
		 * @param string $code
		 * @return array
		 */
		protected function _addGuessedPostgresConstraint( Model $Model, array $rules, $code ) {
			// INFO: IIF the check is "xx()" or "xx() AND xx()" etc.
			// Remove extra parenthesis
			$code = preg_replace( '/^( *\(+ *)? *(.+) *(?(1) *\)+ *)$/', '\2', $code );
			// Transform '.*'::text
			$code = preg_replace( '/\'([^\']+)\'::[^,\)\]]+/', '\1', $code );
			// Transform (0)::numeric
			$code = preg_replace( '/\(([^\(\)]+)\)::[^,\)\]]+/', '\1', $code );
			// Transform ((-1))::double precision
			$code = preg_replace( '/\(\(([^\(\)]+)\)\)::[^,\)\]]+/', '\1', $code );
			// Transform NULL::character varying
			$code = preg_replace( '/NULL::[^,\)]+/', 'NULL', $code );

			if( preg_match_all( '/cakephp_validate_.*\((\(.+\).*|.+)\)/U', $code, $matches, PREG_PATTERN_ORDER ) ) {
				foreach( $matches[0] as $rule ) {
					// INFO: '.*'::text, (0)::numeric and ((-1))::double precision are transformed above
					// if( preg_match( '/^cakephp_validate_(?<function>[^\(]+)\((?<field>\(.*\)::\w+|\w+)(, *(?<params>.*)){0,1}\)$/', $rule, $parameters ) ) {
					if( preg_match( '/^cakephp_validate_(?<function>[^\(]+)\((?<field>\(.*\)|\w+)(, *(?<params>.*)){0,1}\)$/', $rule, $parameters ) ) {
						$ruleName = Inflector::camelize( $parameters['function'] );
						$ruleName[0] = strtolower( $ruleName[0] );

						$field = trim( $parameters['field'] );
						$params = $this->_extractPostgresParams( $parameters );

						$rules[$field][$ruleName] = $this->normalizeValidationRule( $Model, array( 'rule' => array_merge( array( $ruleName ), $params ), 'allowEmpty' => true ) );
					}
				}
			}

			return $rules;
		}
	}
?>