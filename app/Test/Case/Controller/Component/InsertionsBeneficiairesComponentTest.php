<?php
	/**
	 * Code source de la classe InsertionsBeneficiairesComponentTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'InsertionsBeneficiairesComponent', 'Controller/Component' );
	App::uses( 'CakeTestSession', 'CakeTest.Model/Datasource' );

	/**
	 * InsertionsBeneficiairesTestsController class
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class InsertionsBeneficiairesTestsController extends AppController
	{

		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'InsertionsBeneficiairesTestsController';

		/**
		 * uses property
		 *
		 * @var mixed null
		 */
		public $uses = array( 'Apple' );

		/**
		 * components property
		 *
		 * @var array
		 */
		public $components = array(
			'InsertionsBeneficiaires'
		);

	}
	/**
	 * La classe InsertionsBeneficiairesComponentTest réalise les tests de la
	 * classe InsertionsBeneficiairesComponent
	 *
	 * @todo pour les méthodes structuresreferentes et referents, tester avec les
	 *	conditions
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class InsertionsBeneficiairesComponentTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Referent',
			'app.Structurereferente',
			'app.StructurereferenteZonegeographique',
			'app.Typeorient',
			'app.Zonegeographique',
		);

		/**
		 * Controller property
		 *
		 * @var InsertionsBeneficiairesComponent
		 */
		public $Controller;


		/**
		 * test case startup
		 *
		 * @return void
		 */
		public static function setupBeforeClass() {
			CakeTestSession::setupBeforeClass();
		}

		/**
		 * cleanup after test case.
		 *
		 * @return void
		 */
		public static function teardownAfterClass() {
			CakeTestSession::teardownAfterClass();
		}

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			$Request = new CakeRequest( 'apples/index', false );
			$Request->addParams( array( 'controller' => 'apples', 'action' => 'index' ) );

			$this->Controller = new InsertionsBeneficiairesTestsController( $Request );
			$this->Controller->Components->init( $this->Controller );
			$this->Controller->InsertionsBeneficiaires->initialize( $this->Controller );

			CakeTestSession::start();
			CakeTestSession::delete( 'Auth' );
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			CakeTestSession::destroy();
			parent::tearDown();
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::sessionKey()
		 */
		public function testSessionKey() {
			$result = $this->Controller->InsertionsBeneficiaires->sessionKey( 'typesorients', array() );
			$expected = 'Auth.InsertionsBeneficiaires.typesorients.8739602554c7f3241958e3cc9b57fdecb474d508';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $this->Controller->InsertionsBeneficiaires->sessionKey( 'structuresreferentes', array( 'conditions' => array() ) );
			$expected = 'Auth.InsertionsBeneficiaires.structuresreferentes.44e853563ed46d4e94cbfa397ba4ddee622ffb2b';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::typesorients()
		 */
		public function testTypesorientsCg93() {
			Configure::write( 'Cg.departement', 93 );

			// 1. Sans spécifier d'option
			$options = array();
			$result = $this->Controller->InsertionsBeneficiaires->typesorients( $options );
			$expected = array (
				3 => 'Emploi',
				2 => 'Social',
				1 => 'Socioprofessionnelle'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. En spécifiant des conditions
			$options = array( 'conditions' => array( 'Typeorient.actif' => 'N' ) );
			$result = $this->Controller->InsertionsBeneficiaires->typesorients( $options );
			$expected = array (
				4 => 'Foo'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. En spécifiant l'ajout de la valeur vide
			$options = array( 'empty' => true );
			$result = $this->Controller->InsertionsBeneficiaires->typesorients( $options );
			$expected = array (
				0 => 'Non orienté',
				3 => 'Emploi',
				2 => 'Social',
				1 => 'Socioprofessionnelle',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::typesorients()
		 * pour le CG 66 lorsque l'utilisateur est un "externe_ci".
		 */
		public function testTypesorientsCg66() {
			Configure::write( 'Cg.departement', 66 );
			CakeTestSession::write('Auth.User.type', 'externe_ci' );

			$result = $this->Controller->InsertionsBeneficiaires->typesorients();
			$expected = array (
				1 => 'Socioprofessionnelle',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::structuresreferentes()
		 * pour les CG 58 et 93, ainsi qu'au CG 66 lorsque l'utilisateur n'est
		 * pas un "externe_ci".
		 */
		public function testStructuresreferentes() {
			Configure::write( 'Cg.departement', 93 );
			CakeTestSession::write( 'Auth.User.filtre_zone_geo', true );
			CakeTestSession::write( 'Auth.Zonegeographique', array( 1 => 93001 ) );

			// 1. Par défaut, liste simple avec préfixe du type d'orientation
			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes();
			$expected = array(
				'1_1' => '« Projet de Ville RSA d\'Aubervilliers»'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Liste simple sans préfixe
			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes( array( 'prefix' => false ) );
			$expected = array(
				1 => '« Projet de Ville RSA d\'Aubervilliers»'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Liste d'ids avec préfixe du type d'orientation
			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes( array( 'type' => InsertionsBeneficiairesComponent::TYPE_IDS ) );
			$expected = array(
				'1_1' => 1
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 4. Liste d'ids sans préfixe
			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes( array( 'type' => InsertionsBeneficiairesComponent::TYPE_IDS, 'prefix' => false ) );
			$expected = array(
				1 => 1
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 5. Liste d'optgroup avec préfixe du type d'orientation
			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes( array( 'type' => InsertionsBeneficiairesComponent::TYPE_OPTGROUP ) );
			$expected = array(
				'Socioprofessionnelle' => array(
					'1_1' => '« Projet de Ville RSA d\'Aubervilliers»',
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 6. Liste d'optgroup sans préfixe
			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes( array( 'type' => InsertionsBeneficiairesComponent::TYPE_OPTGROUP, 'prefix' => false ) );
			$expected = array(
				'Socioprofessionnelle' => array(
					1 => '« Projet de Ville RSA d\'Aubervilliers»',
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::structuresreferentes()
		 * pour le CG 66 lorsque l'utilisateur est un "externe_ci".
		 */
		public function testStructuresreferentesExterneCi66() {
			Configure::write( 'Cg.departement', 66 );
			CakeTestSession::write( 'Auth.User.type', 'externe_ci' );
			CakeTestSession::write( 'Auth.User.structurereferente_id', 1 );

			$result = $this->Controller->InsertionsBeneficiaires->structuresreferentes();
			$expected = array(
				'1_1' => '« Projet de Ville RSA d\'Aubervilliers»',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::referents()
		 * pour tous les CG, lorsque l'utilisateur n'est pas un "externe_ci".
		 */
		public function testReferents() {
			Configure::write( 'Cg.departement', 93 );
			CakeTestSession::write( 'Auth.User.type', 'cg' );
			CakeTestSession::write( 'Auth.User.filtre_zone_geo', false );

			// 1. Par défaut, liste simple avec préfixe de la structure référente
			$result = $this->Controller->InsertionsBeneficiaires->referents();
			$expected = array(
				'1_1' => 'MR Dupont Martin'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Liste simple sans préfixe
			$result = $this->Controller->InsertionsBeneficiaires->referents( array( 'prefix' => false ) );
			$expected = array(
				1 => 'MR Dupont Martin'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Liste d'ids avec préfixe
			$result = $this->Controller->InsertionsBeneficiaires->referents( array( 'type' => InsertionsBeneficiairesComponent::TYPE_IDS ) );
			$expected = array(
				'1_1' => 1
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 4. Liste d'ids sans préfixe
			$result = $this->Controller->InsertionsBeneficiaires->referents( array( 'type' => InsertionsBeneficiairesComponent::TYPE_IDS, 'prefix' => false ) );
			$expected = array(
				1 => 1
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 5. Liste d'optgroup avec préfixe
			$result = $this->Controller->InsertionsBeneficiaires->referents( array( 'type' => InsertionsBeneficiairesComponent::TYPE_OPTGROUP ) );
			$expected = array(
				'« Projet de Ville RSA d\'Aubervilliers»' =>
				array(
					'1_1' => 'MR Dupont Martin'
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 6. Liste d'optgroup sans préfixe
			$result = $this->Controller->InsertionsBeneficiaires->referents( array( 'type' => InsertionsBeneficiairesComponent::TYPE_OPTGROUP, 'prefix' => false ) );
			$expected = array(
				'« Projet de Ville RSA d\'Aubervilliers»' =>
				array(
					1 => 'MR Dupont Martin'
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::referents()
		 * pour le CG 93 lorsque l'utilisateur est un "externe_ci".
		 */
		public function testReferentsExterneCi93() {
			Configure::write( 'Cg.departement', 93 );
			CakeTestSession::write( 'Auth.User.type', 'externe_ci' );
			CakeTestSession::write( 'Auth.Zonegeographique', array( 1 => 93001 ) );
			CakeTestSession::write( 'Auth.User.filtre_zone_geo', true );

			$result = $this->Controller->InsertionsBeneficiaires->referents();
			$expected = array(
				'1_1' => 'MR Dupont Martin',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode InsertionsBeneficiairesComponent::completeOptionsWithCurrentReferent()
		 */
		public function testCompleteOptionsWithCurrentReferent() {
			$result = $this->Controller->InsertionsBeneficiaires->completeOptionsWithCurrentReferent(
				array(
					'structurereferente_id' => array(
						'Social' => array(
							2 => 'ADEPT',
						)
					),
					'referent_id' => array(
						'2_3' => 'MME Nom Prénom',
					)
				),
				array(
					'structurereferente_id' => 1,
					'referent_id' => 1
				)
			);
			$expected = array(
				'structurereferente_id' => array(
					'Social' => array(
						2 => 'ADEPT',
					),
					'Socioprofessionnelle' => array(
						1 => '« Projet de Ville RSA d\'Aubervilliers»',
					)
				),
				'referent_id' => array(
					'2_3' => 'MME Nom Prénom',
					'1_1' => 'MR Dupont Martin',
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>