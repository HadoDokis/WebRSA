<?php
	/**
	 * Code source de la classe PostgresAutovalidateCopyFixture.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Cette classe permet d'ajouter des contraintes de CHECK aux tables créées
	 * pour les fixtures à partir des contraintes de CHECK faisant appel à des
	 * fonctions cakephp_validate_ trouvées dans les tables de reférence.
	 *
	 * Cette classe est uniquement destinée à être sous-classée et ne fonctionne
	 * qu'avec le driver PostgreSQL.
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 */
	abstract class PostgresAutovalidateCopyFixture extends CakeTestFixture
	{
		/**
		 * Source de données "maître"
		 *
		 * @var DataSource
		 */
		public $masterDb = null;

		/**
		 * Source de données utilisée pour les tests unitaires
		 *
		 * @var DataSource
		 */
		public $testDb = null;

		/**
		 * Retourne la liste des contraintes de CHECK fisant appel à des fonctions
		 * cakephp_validate_
		 *
		 * TODO: prefixes
		 *
		 * @param DataSource $conn
		 * @param string $tableName
		 * @return array
		 */
		protected function _getTableContraints( $conn, $tableName ) {
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
					istc.table_catalog = '{$conn->config['database']}'
					AND istc.table_schema = '{$conn->config['schema']}'
					AND istc.table_name = '".$tableName."'
					AND istc.constraint_type = 'CHECK'
					AND iscc.check_clause ~ 'cakephp_validate_.*(.*)';";

			return $conn->query( $sql, array(), false );
		}

		/**
		 * Création du langage PlPostgres ainsi que des fonctions qui seront utilisées
		 * dans les CHECK.
		 *
		 * TODO: plus fin -> les fonctions existent déjà, on en a d'autres dans master, ...
		 *
		 * @param DataSource $conn
		 */
		protected function _createContraintFunctions( $conn ) {
			$functions = array(
				"CREATE OR REPLACE FUNCTION public.create_plpgsql_language ()
					RETURNS TEXT
					AS $$
						CREATE LANGUAGE plpgsql;
						SELECT 'language plpgsql created'::TEXT;
					$$
				LANGUAGE 'sql';",

				"SELECT CASE WHEN
					( SELECT true::BOOLEAN FROM pg_language WHERE lanname='plpgsql')
				THEN
					(SELECT 'language already installed'::TEXT)
				ELSE
					(SELECT public.create_plpgsql_language())
				END;",
				"CREATE OR REPLACE FUNCTION cakephp_validate_in_list( text, text[] ) RETURNS boolean AS
				$$
					SELECT $1 IS NULL OR ( ARRAY[CAST($1 AS TEXT)] <@ CAST($2 AS TEXT[]) );
				$$
				LANGUAGE sql IMMUTABLE;",
				"CREATE OR REPLACE FUNCTION cakephp_validate_in_list( integer, integer[] ) RETURNS boolean AS
				$$
					SELECT $1 IS NULL OR ( ARRAY[CAST($1 AS TEXT)] <@ CAST($2 AS TEXT[]) );
				$$
				LANGUAGE sql IMMUTABLE;",
				"CREATE OR REPLACE FUNCTION cakephp_validate_range( p_check float, p_lower float, p_upper float ) RETURNS boolean AS
				$$
					BEGIN
						RETURN p_check IS NULL
							OR p_lower IS NULL
							OR p_upper IS NULL
							OR(
								p_check > p_lower
								AND p_check < p_upper
							);
					END;
				$$
				LANGUAGE plpgsql IMMUTABLE;",
				"CREATE OR REPLACE FUNCTION cakephp_validate_inclusive_range( p_check float, p_lower float, p_upper float ) RETURNS boolean AS
				$$
					BEGIN
						RETURN p_check IS NULL
							OR p_lower IS NULL
							OR p_upper IS NULL
							OR(
								p_check >= p_lower
								AND p_check <= p_upper
							);
					END;
				$$
				LANGUAGE plpgsql IMMUTABLE;"
			);

			foreach( $functions as $sql ) {
				$conn->query( $sql, array(), false );
			}
		}

		/**
		 * Création de la table.
		 *
		 * @param object $db
		 * @return boolean
		 */
		public function create( $db ) {
			$return = parent::create( $db );

			$this->testDb = $db;
			$this->masterDb = ConnectionManager::getDataSource( 'default' );

			$masterContraints = $this->_getTableContraints( $this->masterDb, $this->table );
			$testContraints = $this->_getTableContraints( $this->testDb, $this->table );
			if( !empty( $masterContraints ) && empty( $testContraints ) ) {
				$this->_createContraintFunctions( $this->testDb );
				foreach( $masterContraints as $constraint ) {
					$constraint = $constraint[0];
					// TODO: prefixes
					$sql = "ALTER TABLE {$this->table} ADD CONSTRAINT {$constraint['constraint_name']} CHECK ( {$constraint['check_clause']} );";
					$this->testDb->query( $sql );
				}
			}

			return $return;
		}
	}
?>
