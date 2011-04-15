<?php
	/*if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}

	ClassRegistry::config(array('ds' => 'test_suite'));

	class SimpleItemModel extends CakeTestModel {
			var $name = 'SimpleItemModel';
			var $useTable = 'frenchfloats';
			var $actsAs = array('Frenchfloat' => array('fields' => array( 'frenchfloat' )));
	}*/

	define( 'TESTS_DATASOURCE', 'test_suite' );
	ClassRegistry::config( array( 'ds' => TESTS_DATASOURCE ) );

	App::import( 'Core', array( 'Model' ) );
 	require_once( APP.'app_model.php' );

	/**
	*
	*/

	class SimpleItemModel extends AppModel
	{
		public $name = 'Item';
		public $alias = 'SimpleItemModel';
		public $useDbConfig = TESTS_DATASOURCE;
	}

	class FrenchfloatTestCase extends CakeTestCase
	{

		public $fixtures = array( 'item'/*, 'jeton', 'user'*/ );

		/**
		* Here we instantiate our helper, all other helpers we need,
		* and a View class.
		*/
		public function startTest() {
			$this->Record =& new SimpleItemModel();

			// Detach all behaviors
			$behaviors = array_values( $this->Record->Behaviors->attached() );
			foreach( $behaviors as $behavior ) {
				$this->Record->Behaviors->detach( $behavior );
			}

			$this->Record->Behaviors->attach( 'Frenchfloat', array('fields' => array( 'montant' ) ) );
		}

		function testCreateRecord() {
			// ...
			$data = $this->Record->read( null, 1 );
			$data['SimpleItemModel']['montant'] = '26,19';
			$this->Record->create($data);
			$this->assertTrue( $this->Record->save() );
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 26.19, Set::classicExtract( $result, 'SimpleItemModel.montant' ) );

			//
			$data = $this->Record->read( null, 1 );
			$data['SimpleItemModel']['montant'] = '0,00';
			$this->Record->create($data);
			$this->assertTrue( $this->Record->save() );
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 0, Set::classicExtract( $result, 'SimpleItemModel.montant' ) );

			//
			$data = $this->Record->read( null, 1 );
			$data['SimpleItemModel']['montant'] = 30;
			$this->Record->create($data);
			$this->assertTrue( $this->Record->save() );
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 30, Set::classicExtract( $result, 'SimpleItemModel.montant' ) );

			//
			$data = $this->Record->read( null, 1 );
			$data['SimpleItemModel']['montant'] = 66.45;
			$this->Record->create($data);
			$this->assertTrue( $this->Record->save() );
			$result = $this->Record->read(null, 1);
			$this->assertEqual( 66.45, Set::classicExtract( $result, 'SimpleItemModel.montant' ) );

			// FIXME: le behavior doit-il lancer une erreur si on essaie d'enregistrer une
			// chaîne de caractères (ex. choucroute) ?
		}
	}
?>