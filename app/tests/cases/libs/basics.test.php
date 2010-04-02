<?php
	require_once( dirname( __FILE__ ).'/../cake_app_lib_test_case.php' );

    class BasicsTestCase extends CakeAppLibTestCase
    {

		/**
		*
		*/
        public function testArrayFilterKeys() {
			$array = array(
				'foo' => 'bar',
				'bar' => 'baz'
			);

			$result = array_filter_keys( $array, array( 'foo' ) );
			$expected = array( 'foo' => 'bar' );
			$this->assertEqual( $result, $expected );

			$result = array_filter_keys( $array, array( 'foo' ), false );
			$expected = array( 'foo' => 'bar' );
			$this->assertEqual( $result, $expected );

			$result = array_filter_keys( $array, array( 'foo' ), true );
			$expected = array( 'bar' => 'baz' );
			$this->assertEqual( $result, $expected );
        }

		/**
		*
		*/
        public function test__Translate() {
			$values = array(
				'true' => true,
				'false' => false,
				'NULL' => null,
				'1' => 1,
				'\'string\'' => 'string',
				'12.345' => 12.345,
			);

			foreach( $values as $expected => $value ) {
				$this->assertEqual( __translate( $value ), $expected );
			}
        }

		/**
		*
		*/
		public function testRecursiveKeyValuePregReplace() {
			$array = array(
				'Model.field1' => '1',
				'Model.field2' => '(Model.field)'
			);

			$replacements = array(
				'/(?<!\w)(Model\.){0,1}field(?!\w)/' => 'Model__field'
			);

			$result = recursive_key_value_preg_replace( $array, $replacements );

			$expected = array(
				'Model.field1' => '1',
				'Model.field2' => '(Model__field)'
			);

			$this->assertEqual( $result, $expected );
		}

		/**
		*
		*/
		public function testStrallpos() {
			$result = strallpos( 'alalallala', 'al' );
			$expected = array( 0, 2, 4, 7 );
			$this->assertEqual( $result, $expected );
		}

		/**
		*
		*/
		public function testModelField() {
			$expected = array( 'User', 'username' );

			$result = model_field( 'User.username' );
			$this->assertEqual( $result, $expected );

			$result = model_field( 'User.0.username' );
			$this->assertEqual( $result, $expected );

			$result = model_field( '0.User.username' );
			$this->assertEqual( $result, $expected );

			$result = model_field( 'Search.User.username' );
			$this->assertEqual( $result, $expected );
		}
    }
?>