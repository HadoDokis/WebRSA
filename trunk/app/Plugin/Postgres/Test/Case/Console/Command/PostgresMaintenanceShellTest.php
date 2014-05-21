<?php
	/**
	 * Code source de la classe PostgresMaintenanceShell.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConsoleOutput', 'Console' );
	App::uses( 'ConsoleInput', 'Console' );
	App::uses( 'ShellDispatcher', 'Console' );
	App::uses( 'Shell', 'Console' );
	App::uses( 'PostgresMaintenanceShell', 'Postgres.Console/Command' );

	/**
	 * PostgresMaintenanceShellTest class
	 *
	 * @package Postgres
	 * @subpackage Test.Case.Console.Command
	 */
	class PostgresMaintenanceShellTest extends CakeTestCase
	{
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
			parent::setUp();

			$out = $this->getMock( 'ConsoleOutput', array( ), array( ), '', false );
			$in = $this->getMock( 'ConsoleInput', array( ), array( ), '', false );

//			$this->Shell = new PostgresMaintenanceShell( $out, $out, $in );
			$this->Shell = $this->getMock(
				'PostgresMaintenanceShell',
				array( 'out', 'err', '_stop' ),
				array( $out, $out, $in )
			);

			$this->Shell->params['connection'] = 'test';
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Shell );
			parent::tearDown();
		}


		/**
		 * Test de la méthode PostgresMaintenanceShell::help().
		 *
		 * @large
		 */
//		public function testHelp() {
//			$this->Shell->expects( $this->once() )->method( '_stop' )->with( 0 );
//
//			$this->Shell->params['help'] = true;
//			$this->Shell->startup();
//		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::startup().
		 *
		 * @large
		 */
		public function testStartup() {
			$this->Shell->expects( $this->once() )->method( '_stop' )->with( 1 );

			$this->Shell->params['connection'] = 'foo';
			$this->Shell->startup();
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::all().
		 *
		 * @large
		 */
		public function testAll() {
			$this->Shell->expects( $this->once() )->method( '_stop' )->with( 0 );

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'all';
			$this->Shell->all();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			$expected = array(
				'BEGIN',
				'SELECT table_name AS "Model__table",
						column_name	AS "Model__column",
						column_default AS "Model__sequence"
						FROM information_schema.columns
						WHERE table_schema = \'public\'
							AND column_default LIKE \'nextval(%::regclass)\'
						ORDER BY table_name, column_name',
				'SELECT setval(\'postgres_groups_id_seq\', max(id)) FROM postgres_groups;',
				'SELECT setval(\'postgres_users_id_seq\', max(id)) FROM postgres_users;',
				'COMMIT',
				'VACUUM ANALYZE;',
				"REINDEX DATABASE {$this->Shell->Dbo->config['database']};"
			);
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::reindex().
		 *
		 * @large
		 */
		public function testReindex() {
			$this->Shell->expects( $this->once() )->method( '_stop' )->with( 0 );

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'reindex';
			$this->Shell->reindex();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			$expected = array(
				"REINDEX DATABASE {$this->Shell->Dbo->config['database']};"
			);
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::sequences().
		 *
		 * @large
		 */
		public function testSequences() {
			$this->Shell->expects( $this->once() )->method( '_stop' )->with( 0 );

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'sequences';
			$this->Shell->sequences();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			// INFO: les commandes query( 'SELECT ...' ) sont cachées par DboSource
			$expected = array(
				'BEGIN',
				'COMMIT',
			);
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode PostgresMaintenanceShell::vacuum().
		 *
		 * @large
		 */
		public function testVacuum() {
			$this->Shell->expects( $this->once() )->method( '_stop' )->with( 0 );

			$this->Shell->startup();
			$this->Shell->Dbo->getLog( false, true );
			$this->Shell->command = 'vacuum';
			$this->Shell->vacuum();

			$result = Hash::extract( $this->Shell->Dbo->getLog( false, false ), 'log.{n}.query' );
			$expected = array(
				'VACUUM ANALYZE;'
			);
			$this->assertEqual( $result, $expected );
		}
	}
?>