<?php
	/**
	 * Code source de la classe MenuHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('CakeSession', 'Model/Datasource');
	App::uses('Controller', 'Controller');
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'MenuHelper', 'View/Helper' );
	App::uses( 'PermissionsHelper', 'View/Helper' );
	App::uses( 'SessionHelper', 'View/Helper' );

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
			WebrsaPermissions::$sessionPermissionsKey = 'Auth.Permissions';
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
		 * Test de la méthode MenuHelper::make()
		 *
		 * INFO: si on utilise des contrôleurs de l'application, on risque de ne
		 * pas avoir de bons résultats à cause de attributs $commeDroit et
		 * $aucunDroit.
		 *
		 * @return void
		 */
		public function testMake() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			CakeSession::write(
				'Auth.Permissions',
				array(
					'Apples:index' => true,
					'Apples:view' => false,
					'Worms:index' => true,
				)
			);

			$items = array(
				'Panier' => array(
					'url' => array( 'controller' => 'apples', 'action' => 'index', 1 ),
					'Pomme Granny' => array(
						'url' => array( 'controller' => 'apples', 'action' => 'view', 2 ),
						'Vers' => array(
							'url' => array( 'controller' => 'worms', 'action' => 'index', 2 )
						)
					)
				)
			);
			$result = $this->Menu->make( $items );
			$expected = '<ul><li class="branch"><a href="/apples/index/1">Panier</a><ul><li class="branch"><span>Pomme Granny</span><ul><li class="leaf"><a href="/worms/index/2">Vers</a></li></ul></li></ul></li></ul>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode MenuHelper::make2()
		 *
		 * INFO: si on utilise des contrôleurs de l'application, on risque de ne
		 * pas avoir de bons résultats à cause de attributs $commeDroit et
		 * $aucunDroit.
		 *
		 * @return void
		 */
		public function testMake2() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			CakeSession::write(
				'Auth.Permissions',
				array(
					'Apples:index' => true,
					'Apples:view' => false,
					'Worms:index' => true,
					'Pips:index' => true,
				)
			);

			$items = array(
				'Panier' => array(
					'url' => array( 'controller' => 'apples', 'action' => 'index', 1 ),
					'Pomme Granny' => array(
						'url' => array( 'controller' => 'apples', 'action' => 'view', 2 ),
						'Vers' => array(
							'disabled' => true,
							'url' => array( 'controller' => 'worms', 'action' => 'index', 2 ),
						),
						'Pépins' => array(
							'disabled' => false,
							'url' => array( 'controller' => 'pips', 'action' => 'index', 2 ),
							'title' => 'Des pépins pour replanter'
						),
					)
				)
			);
			$result = $this->Menu->make2( $items, 'a' );
			$expected = '<ul><li class="branch"><a href="/apples/index/1">Panier</a><ul><li class="branch"><a href="#">Pomme Granny</a><ul><li class="leaf"><a href="/pips/index/2" title="Des pépins pour replanter">Pépins</a></li></ul></li></ul></li></ul>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>