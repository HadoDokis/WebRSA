<?php
	/**
	 * SuperFixtureWithFixtureTest file
	 *
	 * PHP 5.3
	 *
	 * @package MultiDomainsTranslator
	 * @subpackage Test.Case.Utility.MultiDomainsTranslator
	 */

	App::uses('SuperFixture', 'SuperFixture.Utility');
	require_once 'SuperFixtureTestParent.php';

	/**
	 * SuperFixtureWithFixtureTest class
	 *
	 * @package MultiDomainsTranslator
	 * @subpackage Test.Case.Utility.MultiDomainsTranslator
	 */
	class SuperFixtureWithFixtureTest extends SuperFixtureTestParent
	{
		/**
		 * Fixtures pour le test
		 * ATTENTION : Utile pour la comparaison With/Without fixtures qui a deja 
		 * provoqué des bugs en cas d'absence des fixtures dans la classe de test unitaire
		 * 
		 * @var array
		 */
		public $fixtures = array(
			'plugin.SuperFixture.SuperFixtureBaz', // Si modifications, changez la methode testUnload()
		);
		
		/**
		 * Test de la méthode SuperFixture::load();
		 */
		public function testLoad() {
			SuperFixture::load($this, 'FooBaz'); // Ne pas confondre avec FooBar qui contien une fixture
			
			$result = ClassRegistry::init('SuperFixtureFoo')->find('all', $this->_query);
			$expected = array(
				(int) 0 => array(
					'SuperFixtureFoo' => array(
						'name' => 'Foo 1'
					),
					'SuperFixtureBar' => array(
						'name' => 'Bar'
					),
					'SuperFixtureBaz' => array(
						'name' => 'Baz'
					)
				),
				(int) 1 => array(
					'SuperFixtureFoo' => array(
						'name' => 'Foo 2'
					),
					'SuperFixtureBar' => array(
						'name' => 'Bar'
					),
					'SuperFixtureBaz' => array(
						'name' => 'Baz'
					)
				)
			);
			
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
		
		/**
		 * Test de la méthode SuperFixture::load();
		 * 
		 * @expectedException MissingTableException
		 */
		public function testNotLoad() {
			$result = ClassRegistry::init('SuperFixtureFoo')->find('all', $this->_query);
		}

		/**
		 * Test de la méthode SuperFixture::load();
		 * 
		 * @expectedException NotFoundException
		 */
		public function testLoadSuperFixtureNotFound() {
			SuperFixture::load($this, 'FooBarBaz');
		}

		/**
		 * Test de la méthode SuperFixture::load();
		 * 
		 * @expectedException NotFoundException
		 */
		public function testLoadInnerNotFound() {
			SuperFixture::load($this, 'BadFoo');
		}

		/**
		 * Test de la méthode SuperFixture::load();
		 */
		public function testUnLoad() {
			SuperFixture::load($this, 'FooBaz');
			$this->fixtureManager->unload($this);
			
			$this->assertEquals( $this->fixtures, array('plugin.SuperFixture.SuperFixtureBaz'), var_export( $this->fixtures, true ) );
		}

		/**
		 * Test de la méthode SuperFixture::load();
		 * 
		 * @expectedException NotFoundException
		 */
		public function testLoadFixtureNotFound() {
			debug('test');
			SuperFixture::load($this, 'BadBar');
		}
	}
?>