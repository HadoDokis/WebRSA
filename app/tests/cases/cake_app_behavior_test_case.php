<?php
	if( !defined( 'CAKEPHP_UNIT_TEST_EXECUTION' ) ) {
		define( 'CAKEPHP_UNIT_TEST_EXECUTION', 1 );
	}
	
	ClassRegistry::config(array('ds' => 'test_suite'));

	class CakeAppBehaviorTestCase extends CakeTestCase
	{
	
		public $fixtures = array( 'item' );

		/**
		* Here we instantiate our helper, all other helpers we need,
		* and a View class.
		*/
        function startTest() {
			$this->Item = ClassRegistry::init( 'Item' );

			// Detach all behaviors
			$behaviors = array_values( $this->Item->Behaviors->attached() );
			foreach( $behaviors as $behavior ) {
				$this->Item->Behaviors->detach( $behavior );
			}

			// Attach only the one we're testing
			$settings = array(
			);
			$this->Item->validate = array();
			$behaviorName = preg_replace( '/BehaviorTest$/', '', get_class( $this ) );
			$this->Item->Behaviors->attach( $behaviorName, $settings );
        }
        
		/**
		* Exécuté après chaque test.
		*/
        function tearDown() {
            ClassRegistry::flush();
            unset( $this->Item );
        }
	}
?>
