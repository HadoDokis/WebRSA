<?php
	class RendezvousTest extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Dossierep',
			'app.Foyer',
			'app.Passagecommissionep',
			'app.Personne',
			'app.Rendezvous',
		);

		/**
		 * Méthode exécutée avant chaque test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Rendezvous = ClassRegistry::init( 'Rendezvous' );
		}

		/**
		 * Méthode exécutée après chaque test.
		 */
		public function tearDown() {
			unset( $this->Rendezvous );
		}

		/**
		 * Test de la méthode Rendezvous::dossierId().
		 *
		 * @return void
		 */
		public function testDossierId() {
			$result = $this->Rendezvous->dossierId( 1 );
			$expected = 1;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Rendezvous->dossierId( 666 );
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>