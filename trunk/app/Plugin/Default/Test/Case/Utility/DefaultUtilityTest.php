<?php
	/**
	 * Code source de la classe DefaultUtilityTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUtility', 'Default.Utility' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );
	App::uses( 'Router', 'Routing' );

	/**
	 * La classe DefaultUtilityTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.Utility
	 */
	class DefaultUtilityTest extends DefaultAbstractTestCase
	{
		/**
		 * Données utilisées pour l'évaluation.
		 *
		 * @var array
		 */
		public $data = array(
			'User' => array(
				'id' => 6,
				'username' => 'foo',
				'lastname' => 'bar',
			)
		);

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();

			$request = new CakeRequest();
			$request->addParams(
				array(
					'action' => 'index',
					'plugin' => null,
					'controller' => 'subscribe',
					'admin' => true
				)
			);
			$request->base = '/magazine';
			$request->here = '/magazine';
			$request->webroot = '/magazine/';
			Router::setRequestInfo( $request );
		}

		/**
		 * Test de la méthode DefaultUtility::evaluateString()
		 *
		 * @return void
		 */
		public function testEvaluateString() {
			$result = DefaultUtility::evaluateString( $this->data, '#User.username#' );
			$expected = 'foo';
			$this->assertEqual( $result, $expected, $result );

			$result = DefaultUtility::evaluateString( $this->data, '#User.username# is a #User.lastname#' );
			$expected = 'foo is a bar';
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::evaluate()
		 *
		 * @return void
		 */
		public function testEvaluate() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			$result = DefaultUtility::evaluate( $this->data, $evaluated );
			$expected = array(
				'fuubarbaz' => array(
					'6' => array(
						'foo.bar'
					)
				)
			);
			$this->assertEqual( $result, $expected, $result );
		}

		/**
		 * Test de la méthode DefaultUtility::linkParams()
		 *
		 * @return void
		 */
		public function testLinkParams() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			// TODO: en faire 3 fonctions (avec un cache), dans une classe utilitaire séparée, genre DefaultUrlConverter
			$result = DefaultUtility::linkParams( '/AclUtilities.Users/admin_edit/#User.id##content', array( 'title' => true, 'confirm' => true ), $this->data );
			$expected = array(
				'/AclUtilities.Users/admin_edit',
				array(
					'plugin' => 'acl_utilities',
					'controller' => 'users',
					'action' => 'edit',
					'6',
					'prefix' => 'admin',
					'admin' => true,
					'#' => 'content'
				),
				array(
					'title' => '/AclUtilities.Users/admin_edit/6#content',
					'confirm' => '/AclUtilities.Users/admin_edit/6#content ?',
				),
			);
			$this->assertEqual( $result, $expected );

			// Essai en surchargeant la valeur de msgid
			$result = DefaultUtility::linkParams( '/Users/view/#User.id#', array( 'msgid' => 'Mon msgid' ), $this->data );
			$expected = array(
				'Mon msgid',
				array(
					'plugin' => '',
					'controller' => 'users',
					'action' => 'view',
					0 => '6'
				),
				array()
			);
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultUtility::linkParams() avec des URL définies
		 * dans $data
		 *
		 * @return void
		 */
		public function testLinkParamsUrlData() {
			$evaluated = array(
				'fuu#User.lastname#baz' => array(
					'#User.id#' => array(
						'#User.username#.#User.lastname#'
					)
				)
			);

			$result = DefaultUtility::linkParams(
				'/#Actions.view_url#',
				array( 'title' => true, 'confirm' => true ),
				Hash::merge(
					$this->data,
					array(
						'Actions' => array(
							'view_url' => '/Commissionseps/view/59#dossiers,nonorientationproep58'
						)
					)
				)
			);
			$expected = array(
				'/Commissionseps/view',
				array(
					'plugin' => NULL,
					'controller' => 'commissionseps',
					'action' => 'view',
					'59',
					'#' => 'dossiers,nonorientationproep58'
				),
				array(
					'title' => '/Commissionseps/view/59#dossiers,nonorientationproep58',
					'confirm' => '/Commissionseps/view/59#dossiers,nonorientationproep58 ?'
				)
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultUtility::msgid()
		 *
		 * @return void
		 */
		public function testMsgid() {
			$url = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'index',
			);

			$result = DefaultUtility::msgid( $url );
			$expected = '/Users/index';
			$this->assertEqual( $result, $expected );

			$url = array(
				'plugin' => 'acl_extras',
				'controller' => 'users',
				'action' => 'view',
				0 => '1',
				'prefix' => 'admin',
				'admin' => true,
			);

			$result = DefaultUtility::msgid( $url );
			$expected = '/AclExtras.Users/admin_view';
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultUtility::attributes()
		 *
		 * @return void
		 */
		public function testAttributes() {
			$url = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'view',
				0 => '#User.id#',
			);

			$result = DefaultUtility::attributes( $url, array( 'title' => true, 'confirm' => true ) );
			$expected = array(
				'title' => '/Users/view/#User.id#/:title',
				'confirm' => '/Users/view/#User.id# ?'
			);
			$this->assertEqual( $result, $expected );

			$url = array(
				'plugin' => 'acl_extras',
				'controller' => 'users',
				'action' => 'view',
				0 => '1',
				'prefix' => 'admin',
				'admin' => true,
			);

			$result = DefaultUtility::attributes( $url );
			$expected = array();
			$this->assertEqual( $result, $expected );

			$url = array(
				'plugin' => null,
				'controller' => 'tableauxsuivispdvs93',
				'action' => 'tableaud1'
			);

			$result = DefaultUtility::attributes( $url, array( 'title' => true, 'confirm' => true ) );
			$expected = array(
				'title' => '/Tableauxsuivispdvs93/tableaud1/:title', // FIXME ?
				'confirm' => '/Tableauxsuivispdvs93/tableaud1 ?'
			);
			$this->assertEqual( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultUtility::domain()
		 *
		 * @return void
		 */
		public function testDomain() {
			$url = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'view',
				0 => '#User.id#',
			);

			$result = DefaultUtility::domain( $url, array( 'domain' => 'vibrations' ) );
			$expected = 'vibrations';
			$this->assertEqual( $result, $expected );

			$result = DefaultUtility::domain( $url );
			$expected = 'users';
			$this->assertEqual( $result, $expected );

			$url = array(
				'plugin' => 'acl_extras',
				'controller' => 'users',
				'action' => 'view',
				0 => '1',
				'prefix' => 'admin',
				'admin' => true,
			);

			$result = DefaultUtility::domain( $url );
			$expected = 'users';
			$this->assertEqual( $result, $expected );
		}
	}
?>