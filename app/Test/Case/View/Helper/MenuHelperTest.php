<?php
	/**
	 * Code source de la classe MenuHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'CakeSession', 'Model/Datasource' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'MenuHelper', 'View/Helper' );

	/**
	 * Classe MenuHelperTest.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class MenuHelperTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
		);

		/**
		 * Préparation du test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->Menu = new MenuHelper( $this->View );
			CakeSession::delete( 'Auth.Permissions' );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->Menu );
		}

		/**
		 * Test de la méthode MenuHelper::method()
		 *
		 * @return void
		 */
		public function testMenu() {
			CakeSession::write(
				'Auth.Permissions',
				array(
					'Personnes:index' => true,
					'Personnes:view' => false,
					'Memos:index' => true,
				)
			);

			$items = array(
				'Composition du foyer' => array(
					'url' => array( 'controller' => 'personnes', 'action' => 'index', 1 ),
					'M. BUFFIN Christian' => array(
						'url' => array( 'controller' => 'personnes', 'action' => 'view', 2 ),
						'Mémos' => array(
							'url' => array( 'controller' => 'memos', 'action' => 'index', 2 )
						)
					)
				)
			);
			$result = $this->Menu->make( $items );
			$expected = '<ul><li class="branch"><a href="/personnes/index/1">Composition du foyer</a><ul><li class="branch"><span>M. BUFFIN Christian</span><ul><li class="leaf"><a href="/memos/index/2">Mémos</a></li></ul></li></ul></li></ul>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>