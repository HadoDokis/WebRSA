<?php
	/**
	 * Code source de la classe PostgresPostgresTest.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Datasource.Database
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once dirname( dirname( dirname( __FILE__ ) ) ).DS.'models.php';

	/**
	 * La classe PostgresPostgresTest ...
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Model.Datasource.Database
	 */
	class PostgresPostgresTest extends CakeTestCase
	{
		/**
		 *
		 * @var User
		 */
		public $User = null;

		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.Postgres.PostgresGroup',
			'plugin.Postgres.PostgresUser',
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			Configure::write( 'Cache.disable', true );
//			$this->Dbo = ConnectionManager::getDataSource( 'test' );
			$this->User = ClassRegistry::init(
				array(
					'class' => 'Postgres.PostgresUser',
					'alias' => 'User',
					'ds' => 'test',
				)
			);
			$this->Dbo = $this->User->getDatasource();
			$this->skipIf( !($this->Dbo instanceof PostgresPostgres) );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Dbo, $this->User );
			parent::tearDown();
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresVersion()
		 */
		public function testGetPostgresVersion() {
			$result = $this->Dbo->getPostgresVersion();
			$this->assertPattern( '/^[0-9]+\.[0-9]+/', $result );

			$result = $this->Dbo->getPostgresVersion( true );
			$this->assertPattern( '/^PostgreSQL [0-9]+\.[0-9]+/', $result );
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresFunctions()
		 */
		public function testGetPostgresFunctions() {
			$result = $this->Dbo->getPostgresFunctions( array( "pg_proc.proname ~ '^cakephp_validate_'" ) );
			$expected = array(
				0 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate__ipv4',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				1 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate__ipv6',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				2 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_alpha_numeric',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				3 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_between',
						'result' => 'boolean',
						'arguments' => 'text, integer, integer',
					),
				),
				4 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_blank',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				5 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				6 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text, text',
					),
				),
				7 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text, text, boolean',
					),
				),
				8 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text, text, boolean, text',
					),
				),
				9 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text, text[]',
					),
				),
				10 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text, text[], boolean',
					),
				),
				11 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_cc',
						'result' => 'boolean',
						'arguments' => 'text, text[], boolean, text',
					),
				),
				12 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_comparison',
						'result' => 'boolean',
						'arguments' => 'double precision, text, double precision',
					),
				),
				13 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_decimal',
						'result' => 'boolean',
						'arguments' => 'double precision',
					),
				),
				14 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_decimal',
						'result' => 'boolean',
						'arguments' => 'double precision, integer',
					),
				),
				15 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_decimal',
						'result' => 'boolean',
						'arguments' => 'double precision, integer, text',
					),
				),
				16 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_email',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				17 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_email',
						'result' => 'boolean',
						'arguments' => 'text, boolean',
					),
				),
				18 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_email',
						'result' => 'boolean',
						'arguments' => 'text, boolean, text',
					),
				),
				19 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_in_list',
						'result' => 'boolean',
						'arguments' => 'integer, integer[]',
					),
				),
				20 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_in_list',
						'result' => 'boolean',
						'arguments' => 'text, text[]',
					),
				),
				21 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_inclusive_range',
						'result' => 'boolean',
						'arguments' => 'double precision, double precision, double precision',
					),
				),
				22 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_ip',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				23 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_ip',
						'result' => 'boolean',
						'arguments' => 'text, text',
					),
				),
				24 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_luhn',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				25 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_luhn',
						'result' => 'boolean',
						'arguments' => 'text, boolean',
					),
				),
				26 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_max_length',
						'result' => 'boolean',
						'arguments' => 'text, integer',
					),
				),
				27 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_min_length',
						'result' => 'boolean',
						'arguments' => 'text, integer',
					),
				),
				28 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_not_empty',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				29 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_phone',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				),
				30 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_phone',
						'result' => 'boolean',
						'arguments' => 'text, text',
					),
				),
				31 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_phone',
						'result' => 'boolean',
						'arguments' => 'text, text, text',
					),
				),
				32 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_range',
						'result' => 'boolean',
						'arguments' => 'double precision, double precision, double precision',
					),
				),
				33 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_ssn',
						'result' => 'boolean',
						'arguments' => 'text, text, text',
					),
				),
				34 =>
				array(
					'Function' =>
					array(
						'schema' => 'public',
						'name' => 'cakephp_validate_uuid',
						'result' => 'boolean',
						'arguments' => 'text',
					),
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::checkPostgresSqlSyntax()
		 */
		public function testCheckPostgresSqlSyntax() {
			// 1. Succès
			$sql = "SELECT NOW() + interval '4 DAY 1 MONTH'";
			$result = $this->Dbo->checkPostgresSqlSyntax( $sql );
			$expected = array(
				'success' => true,
				'message' => null,
				'value' => 'SELECT NOW() + interval \'4 DAY 1 MONTH\'',
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Erreur
			$sql = "SELECT NOW() + interval '4 DBY 1 MONTH'";
			$result = $this->Dbo->checkPostgresSqlSyntax( $sql );
			$expected = array(
				'success' => false,
				'message' => '7: ERROR:  invalid input syntax for type interval: "4 DBY 1 MONTH"',
				'value' => 'SELECT NOW() + interval \'4 DBY 1 MONTH\'',
			);
			$expected['message'] = ( strpos( $result['message'], $expected['message'] ) === 0 ? $result['message'] : $expected['message'] );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::checkPostgresIntervalSyntax()
		 */
		public function testCheckPostgresIntervalSyntax() {
			// 1. Succès
			$interval = '4 DAY 1 MONTH';
			$result = $this->Dbo->checkPostgresIntervalSyntax( $interval );
			$expected = array(
				'value' => $interval,
				'success' => true,
				'message' => null
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2. Erreur
			$interval = '4 DBY 1 MONTH';
			$result = $this->Dbo->checkPostgresIntervalSyntax( $interval );
			$expected = array(
				'value' => $interval,
				'success' => false,
				'message' => '7: ERROR:  invalid input syntax for type interval: "4 DBY 1 MONTH"'
			);
			$expected['message'] = ( strpos( $result['message'], $expected['message'] ) === 0 ? $result['message'] : $expected['message'] );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresForeignKeys()
		 */
		public function testGetPostgresForeignKeys() {
			$result = $this->Dbo->getPostgresForeignKeys();
			$expected = array(
				array(
					'Foreignkey' => array(
						'name' => 'postgres_users_group_id_fk',
						'onupdate' => 'NO ACTION',
						'ondelete' => 'NO ACTION'
					),
					'From' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'column' => 'group_id',
						'nullable' => false,
						'unique' => false
					),
					'To' => array(
						'schema' => 'public',
						'table' => 'postgres_groups',
						'column' => 'id',
						'nullable' => false,
						'unique' => false
					)
				)
			);
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode PostgresPostgres::getPostgresCheckConstraints()
		 */
		public function testGetPostgresCheckConstraints() {
			$result = $this->Dbo->getPostgresCheckConstraints();
			$expected = array(
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_active_in_list_chk',
						'clause' => 'cakephp_validate_in_list(active, ARRAY[0, 1])'
					)
				),
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_phone_phone_chk',
						'clause' => 'cakephp_validate_phone((phone)::text, NULL::text, \'fr\'::text)'
					)
				),
				array(
					'Constraint' => array(
						'schema' => 'public',
						'table' => 'postgres_users',
						'name' => 'postgres_users_popularity_inclusive_range_chk',
						'clause' => 'cakephp_validate_inclusive_range((popularity)::double precision, (0)::double precision, (10)::double precision)'
					)
				)
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>