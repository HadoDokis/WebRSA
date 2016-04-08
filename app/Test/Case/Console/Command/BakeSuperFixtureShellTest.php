<?php
	/**
	 * Code source de la classe BakeSuperFixtureShellTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'ConsoleOutput', 'Console' );
	App::uses( 'ConsoleInput', 'Console' );
	App::uses( 'ShellDispatcher', 'Console' );
	App::uses( 'Shell', 'Console' );
	App::uses( 'AppShell', 'Console/Command' );
	App::uses( 'BakeSuperFixtureShell', 'SuperFixture.Console/Command' );
	App::uses('SuperFixture', 'SuperFixture.Utility');

	/**
	 * BakeSuperFixtureShellTest class
	 *
	 * @package app.Test.Case.Console.Command
	 */
	class BakeSuperFixtureShellTest extends CakeTestCase
	{	
		/**
		 *
		 * @var AppShell
		 */
		public $Shell = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			
			$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
			$in = $this->getMock('ConsoleInput', array(), array(), '', false);

			$this->Shell = $this->getMock(
				'BakeSuperFixtureShell',
				array('out', 'err', '_stop'),
				array($out, $out, $in)
			);
			
			$this->Shell->params = array(
				'help' => false,
				'verbose' => false,
				'quiet' => false,
				'connection' => 'test',
				'log' => false,
				'headers' => 'true',
				'separator' => ',',
				'delimiter' => '"',
			);
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Shell );
			parent::tearDown();
		}

		/**
		 * Test de la méthode BakeSuperFixtureShell::main()
		 *
		 * @large
		 */
		public function testMainDossier() {
			SuperFixture::load($this, 'TestBakeDossier');
			
			Faker\Factory::create('fr_FR')->seed(1234);
			
			$className = 'TestUnitaireShellSuperFixture';
			$path = APP.'Test'.DS.'SuperFixture'.DS;
			
			$this->Shell->args[0] = APP.'Vendor'.DS.'BakeSuperFixture'.DS.'DossierBaker.php';
			$this->Shell->args[1] = $path.$className.'.php';

			$this->Shell->initialize();
			$this->Shell->loadTasks();
			$this->Shell->startup();
			$this->Shell->main();
			
			$this->Shell->expects($this->any())->method('_stop')->with(0);

			require_once $path.$className.'.php';
			$obj = new $className();
			$data = $obj->getData();
			unlink(APP.'Test'.DS.'SuperFixture'.DS.'TestUnitaireShellSuperFixture.php');
			
			$signatures = array();
			foreach ($data as $key => $value) {
				$signatures[$key] = md5(json_encode($value));
			}
			
			$expected = array(
				'Serviceinstructeur' => '64175a694efc343428a71047cf8bca05',
				'Group' => '5968ad446939137413baf41b99fae3bd',
				'User' => 'a932691409a0e79611be9be3e14b422f',
				'Typeorient' => 'c2d8eb808fa2fe981c53bf003371d7ac',
				'Structurereferente' => '689fe106291131f8f829069af311c402',
				'Referent' => 'cefaf6afaaa1ddea60f8b40b7d251cfe',
				'Adresse' => '6a917ed34d4c2c222db361884ea52397',
				'Dossier' => '2eac3fc295edda1846443f0d62ab24fe',
				'Situationdossierrsa' => 'ac93cd99fecce8a357a17efdc7f0f88b',
				'Detaildroitrsa' => 'ae20cad7171c05b2b5e6537332403ba8',
				'Detailcalculdroitrsa' => '21df803fe12b1859c165b04edd416c03',
				'Foyer' => '2eb106efced2fbf97f13866a38a141c1',
				'Adressefoyer' => '702b4a3986dd3b2086831e6b432096b0',
				'Personne' => '358c3faa4d680f872e2fe96f3cf3116d',
				'Prestation' => '7ab1bda52914bed0f5343d9cfd6cab7c',
				'Calculdroitrsa' => 'f8fcd700d9d331f48d9541ded137b4e0',
				'Orientstruct' => 'dcaffe7459de31718d7991f130f4f588'
			)
;
			
			$this->assertEqual($signatures, $expected, "Signature des données différentes");
		}

		/**
		 * Test de la méthode BakeSuperFixtureShell::main()
		 *
		 * @large
		 */
		public function testMainCui() {
			Faker\Factory::create('fr_FR')->seed(1234);
			Configure::write('Cui.Numeroconvention', '0661300001');
			
			$className = 'TestUnitaireShellSuperFixture2';
			$path = APP.'Test'.DS.'SuperFixture'.DS;
			
			SuperFixture::load($this, 'TestBakeDossier');
			
			$this->Shell->args[0] = APP.'Vendor'.DS.'BakeSuperFixture'.DS.'CuiBaker.php';
			$this->Shell->args[1] = $path.$className.'.php';

			$this->Shell->initialize();
			$this->Shell->loadTasks();
			$this->Shell->startup();
			$this->Shell->main();
			
			$this->Shell->expects($this->any())->method('_stop')->with(0);

			require_once $path.$className.'.php';
			$obj = new $className();
			$data = $obj->getData();
			unlink(APP.'Test'.DS.'SuperFixture'.DS.'TestUnitaireShellSuperFixture2.php');
			
			$signatures = array();
			foreach ($data as $key => $value) {
				// Suppression des dates dans le CUI car change selon l'année en cours
				if ($key === 'Cui') {
					unset(
						$value['dateembauche'], 
						$value['findecontrat'], 
						$value['effetpriseencharge'], 
						$value['finpriseencharge'], 
						$value['decisionpriseencharge'], 
						$value['faitle'],
						$value['signaturele'],
						$value['created'],
						$value['modified']
					);
				}
				$signatures[$key] = md5(json_encode($value));
			}
			
			$expected = array(
				'Serviceinstructeur' => '0f4ad5b7b212d8c30bb0e91ba25d5b82',
				'Group' => '551e2561e1bf29888302c5638b6fa02c',
				'User' => 'f8c49b654414c5c3f3334531fa3e11d0',
				'Typeorient' => 'e4ea23f01a3b46be7c718e15021e5040',
				'Structurereferente' => 'cef1f48a4b887c681226b69ac42b3685',
				'Referent' => 'cefaf6afaaa1ddea60f8b40b7d251cfe',
				'Adresse' => '18fe4887c452625b00748f78b9ce9546',
				'Dossier' => '8d0cd6a4ffb93c45e1695a2981d561b6',
				'Situationdossierrsa' => '0c79b7476e0a841f43d1e2011d3d4ceb',
				'Detaildroitrsa' => 'fcd2c0dcc296cca4a84bd0af30607095',
				'Detailcalculdroitrsa' => 'f8b9e7ac6d527d09ab3cfc0663cf102c',
				'Foyer' => '733c4501317c580e05e8f426a826b936',
				'Adressefoyer' => 'b8b49708a20aece9fc46aa8699b4f6d5',
				'Personne' => 'c3b7140ac21b48f36ed5873132a3dd0b',
				'Prestation' => '9d79b32e613691c8992d26aea9482870',
				'Calculdroitrsa' => 'ef252cebe9459910cfe53767e607e7a9',
				'Orientstruct' => '2df77ee36fb89aec00a1a65d9952136f',
				'Cui' => '2e15b6f2889607b281dbe9471c1fc519'
			);
			
			$this->assertEqual($signatures, $expected, "Signature des données différentes");
		}
	}
?>