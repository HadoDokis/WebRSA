<?php
	/**
	 * Code source de la classe Dossierpcg66Test.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Dossierpcg66', 'Model' );

	/**
	 * La classe Dossierpcg66Test réalise les tests unitaires de la classe Dossierpcg66.
	 *
	 * @package app.Test.Case.Model
	 */
	class Dossierpcg66Test extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Dossierpcg66',
		);

		/**
		 * Le modèle à tester.
		 *
		 * @var Dossierpcg66
		 */
		public $Dossierpcg66 = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Dossierpcg66 = ClassRegistry::init( 'Dossierpcg66' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Dossierpcg66 );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Dossierpcg66::etatDossierPcg66()
		 */
		public function testEtatDossierPcg66() {
			// 1. Tout à vide
			$params = array(
				'typepdo_id' => null,
				'user_id' => null,
				'decisionpdoId' => null,
				'instrencours' => null,
				'avistechnique' => null,
				'validationavis' => null,
				'retouravistechnique' => null,
				'vuavistechnique' => null,
				'etatdossierpcg' => null,
			);
			$result = call_user_func_array( array( $this->Dossierpcg66, 'etatDossierPcg66' ), $params );
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. En attente d'affectation
			$params['typepdo_id'] = 1;
			$result = call_user_func_array( array( $this->Dossierpcg66, 'etatDossierPcg66' ), $params );
			$expected = 'attaffect';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. En attente d'instruction
			$params['user_id'] = 1;
			$params['etatdossierpcg'] = 'attaffect';
			$result = call_user_func_array( array( $this->Dossierpcg66, 'etatDossierPcg66' ), $params );
			$expected = 'attinstr';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Instruction en cours
			$params['decisionpdoId'] = 1;
			$params['instrencours'] = '1';
			$params['etatdossierpcg'] = null;
			$result = call_user_func_array( array( $this->Dossierpcg66, 'etatDossierPcg66' ), $params );
			$expected = 'instrencours';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 4. En attente d'avis technique
			$params['instrencours'] = null;
			$result = call_user_func_array( array( $this->Dossierpcg66, 'etatDossierPcg66' ), $params );
			$expected = 'attavistech';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
