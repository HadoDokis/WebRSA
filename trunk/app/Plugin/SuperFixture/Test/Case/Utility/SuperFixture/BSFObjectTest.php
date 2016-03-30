<?php
	/**
	 * SuperFixtureWithFixtureTest file
	 *
	 * PHP 5.3
	 *
	 * @package SuperFixture
	 * @subpackage Test.Case.Utility.SuperFixture
	 */

	App::uses('BSFObject', 'SuperFixture.Utility');
	
	class TestObject extends BSFObject
	{
		protected $_notSettable;
		
		public function getNotSettableValue() {
			return $this->_notSettable;
		}
	}

	/**
	 * BSFObjectTest class
	 *
	 * @package SuperFixture
	 * @subpackage Test.Case.Utility.SuperFixture
	 */
	class BSFObjectTest extends CakeTestCase
	{	
		/**
		 * Test de la méthode BSFObject::__call('setMaVar', $arg);
		 */
		public function testSetters() {
			$Object = new TestObject();
			
			$Object->setRuns(2)->set_runs(3);
			$this->assertEquals($Object->runs, 3, var_export($Object->runs, true));
			
			$Object->setModelName("User");
			$this->assertEquals($Object->modelName, "User", var_export($Object->modelName, true));
			
			$Object->setFields(array('name' => array('auto' => true)));
			$this->assertEquals($Object->fields, array('name' => array('auto' => true)), var_export($Object->fields, true));
			
			$Object->set__notSettable('test');
			$this->assertNotEquals($Object->getNotSettableValue(), 'test', var_export($Object->getNotSettableValue(), true));
		}
		
		/**
		 * Test de la méthode BSFObject::__call('setMaVar', $arg);
		 * 
		 * @expectedException PHPUnit_Framework_Error
		 */
		public function testSetOnProtected() {
			$Object = new TestObject();
			$Object->unknowFunction('test');
		}
	}
?>