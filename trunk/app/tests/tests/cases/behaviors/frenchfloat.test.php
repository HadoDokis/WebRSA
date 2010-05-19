<?php
	if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}

	ClassRegistry::config(array('ds' => 'test_suite'));

	class FrenchfloatTest extends CakeTestModel {
			var $name = 'FrenchfloatTest';
			var $useTable = 'frenchfloats';
			var $actsAs = array('Frenchfloat' => array('fields' => array( 'frenchfloat' )));
	}

	class FrenchfloatTestCase extends CakeTestCase
	{
	
		var $fixtures = array( 'frenchfloat' );

		/**
		* Here we instantiate our helper, all other helpers we need,
		* and a View class.
		*/
		public function startTest() {
			$this->Record =& new FrenchfloatTest();

			// Detach all behaviors
			$behaviors = array_values( $this->Record->Behaviors->attached() );
			foreach( $behaviors as $behavior ) {
				$this->Record->Behaviors->detach( $behavior );
			}

			$this->Record->Behaviors->attach( 'Frenchfloat', array('fields' => array( 'frenchfloat' ) ) );
		}	

		function testCreateRecord() {
			// ...
			$data = array('FrenchfloatTest'=>array('id'=>1, 'frenchfloat'=>'26,19'));
			$this->Record->save($data);
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 26.19, Set::classicExtract( $result, 'FrenchfloatTest.frenchfloat' ) );

			// 
			$data = array('FrenchfloatTest'=>array('id'=>1, 'frenchfloat'=>'0,00'));
			$this->Record->save($data);
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 0, Set::classicExtract( $result, 'FrenchfloatTest.frenchfloat' ) );

			// 
			$data = array('FrenchfloatTest'=>array('id'=>1, 'frenchfloat'=> 30));
			$this->Record->save($data);
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 30, Set::classicExtract( $result, 'FrenchfloatTest.frenchfloat' ) );

			// 
			$data = array('FrenchfloatTest'=>array('id'=>1, 'frenchfloat'=> 66.45));
			$this->Record->save($data);
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 66.45, Set::classicExtract( $result, 'FrenchfloatTest.frenchfloat' ) );

			// FIXME: le behavior doit-il lancer une erreur si on essaie d'enregistrer une
			// chaîne de caractères (ex. choucroute) ?
		}
	}
?> 