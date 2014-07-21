<?php
	/**
	 * Code source de la classe DefaultCsvHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'Helper', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'CsvHelper', 'View/Helper' );
	App::uses( 'DefaultCsvHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultCsvHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultCsvHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple'
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			$this->Apple = ClassRegistry::init( 'Apple' );

			$controller = null;
			$this->View = new View( $controller );
			$this->DefaultCsv = new DefaultCsvHelper( $this->View );
			$this->DefaultCsv->Csv = new CsvTestHelper( $this->View );

			$this->DefaultCsv->request = new CakeRequest( null, false );
			$this->DefaultCsv->request->addParams( array( 'controller' => 'apples', 'action' => 'index' ) );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->DefaultCsv, $this->Apple );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render()
		 */
		public function testRender() {
			$apples = $this->Apple->find( 'all' );

			$result = $this->DefaultCsv->render(
				$apples,
				array(
					'Apple.id',
					'Apple.color',
					'Apple.date',
					'Apple.created',
					'Apple.mytime',
				)
			);

			$expected = 'Apple.id,Apple.color,Apple.date,Apple.created,Apple.mytime
1,"Red 1",04/01/1951,"22/11/2006 à 10:38:58",22:57:17
2,"Bright Red 1",01/01/2014,"22/11/2006 à 10:43:13",22:57:17
3,"blue green",25/12/2006,"25/12/2006 à 05:13:36",22:57:17
4,"Blue Green",25/12/2006,"25/12/2006 à 05:23:36",22:57:17
5,Green,25/12/2006,"25/12/2006 à 05:24:06",22:57:17
6,"My new appleOrange",25/12/2006,"25/12/2006 à 05:29:39",22:57:17
7,"Some wierd color",25/12/2006,"25/12/2006 à 05:34:21",22:57:17
';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render() avec des valeurs null
		 */
		public function testRenderNullValues() {
			$apples = $this->Apple->find( 'all' );

			$result = $this->DefaultCsv->render(
				$apples,
				array(
					'Apple.id',
					'Apple.color',
					'Apple.unknown_field',
					'Apple.created',
				)
			);
			$expected = 'Apple.id,Apple.color,Apple.unknown_field,Apple.created
1,"Red 1",,"22/11/2006 à 10:38:58"
2,"Bright Red 1",,"22/11/2006 à 10:43:13"
3,"blue green",,"25/12/2006 à 05:13:36"
4,"Blue Green",,"25/12/2006 à 05:23:36"
5,Green,,"25/12/2006 à 05:24:06"
6,"My new appleOrange",,"25/12/2006 à 05:29:39"
7,"Some wierd color",,"25/12/2006 à 05:34:21"
';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render() avec des options pour
		 * les traductions.
		 */
		public function testRenderOptions() {
			$apples = $this->Apple->find( 'all' );

			$result = $this->DefaultCsv->render(
				$apples,
				array(
					'Apple.id',
					'Apple.color',
					'Apple.date',
					'Apple.created',
					'Apple.mytime',
				),
				array(
					'options' => array(
						'Apple' => array(
							'color' => array(
								'Red 1' => 'New Red 1',
								'blue green' => 'New blue green',
							)
						)
					)
				)
			);

			$expected = 'Apple.id,Apple.color,Apple.date,Apple.created,Apple.mytime
1,"New Red 1",04/01/1951,"22/11/2006 à 10:38:58",22:57:17
2,"Bright Red 1",01/01/2014,"22/11/2006 à 10:43:13",22:57:17
3,"New blue green",25/12/2006,"25/12/2006 à 05:13:36",22:57:17
4,"Blue Green",25/12/2006,"25/12/2006 à 05:23:36",22:57:17
5,Green,25/12/2006,"25/12/2006 à 05:24:06",22:57:17
6,"My new appleOrange",25/12/2006,"25/12/2006 à 05:29:39",22:57:17
7,"Some wierd color",25/12/2006,"25/12/2006 à 05:34:21",22:57:17
';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render() sans les en-têtes
		 */
		public function testRenderNoHeaders() {
			$apples = $this->Apple->find( 'all' );

			$result = $this->DefaultCsv->render(
				$apples,
				array(
					'Apple.id',
					'Apple.color',
					'Apple.date',
					'Apple.created',
					'Apple.mytime',
				),
				array(
					'headers' => false
				)
			);

			$expected = '1,"Red 1",04/01/1951,"22/11/2006 à 10:38:58",22:57:17
2,"Bright Red 1",01/01/2014,"22/11/2006 à 10:43:13",22:57:17
3,"blue green",25/12/2006,"25/12/2006 à 05:13:36",22:57:17
4,"Blue Green",25/12/2006,"25/12/2006 à 05:23:36",22:57:17
5,Green,25/12/2006,"25/12/2006 à 05:24:06",22:57:17
6,"My new appleOrange",25/12/2006,"25/12/2006 à 05:29:39",22:57:17
7,"Some wierd color",25/12/2006,"25/12/2006 à 05:34:21",22:57:17
';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render() avec des données vides.
		 */
		public function testRenderEmptyData() {
			$result = $this->DefaultCsv->render(
				array(),
				array(
					'Apple.id',
					'Apple.color',
					'Apple.date',
					'Apple.created',
					'Apple.mytime',
				)
			);

			$expected = "Apple.id,Apple.color,Apple.date,Apple.created,Apple.mytime\n";
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render() avec des champs vides.
		 */
		public function testRenderEmptyFields() {
			$result = $this->DefaultCsv->render(
				array(),
				array()
			);

			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultCsvHelper::render() pour vérification du nom
		 * de fichier.
		 */
		public function testRenderFilename() {
			// Nom de fichier généré par défaut
			$this->DefaultCsv->render(
				array(),
				array(
					'Apple.id',
					'Apple.color',
					'Apple.date',
					'Apple.created',
					'Apple.mytime',
				)
			);

			$result = $this->DefaultCsv->Csv->filename;
			$expected = '/apples\-index\-[0-9]{8}\-[0-9]{6}\.csv/';
			$this->assertPattern( $expected, $result, var_export( $result, true ) );

			// Nom de fichier spécifié
			$this->DefaultCsv->render(
				array(),
				array(
					'Apple.id',
					'Apple.color',
					'Apple.date',
					'Apple.created',
					'Apple.mytime',
				),
				array(
					'filename' => 'foo'
				)
			);

			$result = $this->DefaultCsv->Csv->filename;
			$expected = '/foo\.csv/';
			$this->assertPattern( $expected, $result, var_export( $result, true ) );
		}
	}
?>