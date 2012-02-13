<?php
	require_once( dirname( __FILE__ ).'/../cake_app_lib_test_case.php' );

    class XsetTestCase extends CakeAppLibTestCase
    {
		/**
		*
		*/

        public function testFilterDeep() {
			$input = array(
				'User.id' => 1,
				'User.username' => '',
				'Group.id' => 0,
				'Group.name' => null,
				'Group.id' => false,
			);

			$expected = array(
				'User' => array(
					'id' => 1
				),
			);

			$this->assertEqual( Xset::filterDeep( $input ), $expected );
        }

		/**
		*
		*/

        public function testBump() {
			$input = array(
				'User.id' => 1,
				'User.username' => 'cbuffin',
				'Group.id' => 1,
				'Group.name' => 'Users'
			);
			$expected = array(
				'User' => array(
					'id' => 1,
					'username' => 'cbuffin',
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Users',
				),
			);

			$this->assertEqual( Xset::bump( $input ), $expected );
        }

		/**
		*
		*/

        public function testBumpSeparator() {
			$input = array(
				'User__id' => 1,
				'User__username' => 'cbuffin',
				'Group__id' => 1,
				'Group__name' => 'Users'
			);

			// -----------------------------------------------------------------

			$expected = array(
				'User' => array(
					'id' => 1,
					'username' => 'cbuffin',
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Users',
				),
			);

			$this->assertEqual( Xset::bump( $input, '__' ), $expected );

			// -----------------------------------------------------------------

			$input = array(
				'User.id' => 1,
				'User.username' => 'cbuffin',
				'Group.id' => 1,
				'Group.name' => 'Users'
			);
			$this->assertEqual( Xset::bump( $input, '__' ), $expected );
        }

		/**
		*
		*/

        /*public function testFilterkeys() {
			$haystack = array(
				'User' => array(
					'id' => 1,
					'username' => 'cbuffin',
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Users',
				),
			);

			$expected = array( 'Group' => array( 'id' => 1, 'name' => 'Users', ) );
			$this->assertEqual( Xset::filterkeys( $haystack, array( 'User' ), true ), $expected );

			$expected = array( 'User' => array( 'id' => 1, 'username' => 'cbuffin', ) );
			$this->assertEqual( Xset::filterkeys( $haystack, array( 'User' ), false ), $expected );
        }*/

		/**
		*
		*/

        /*public function testAnykey() {
			$haystack = array(
				'User' => array(
					'id' => 1,
					'username' => 'cbuffin',
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Users',
				),
			);

			$this->assertTrue( Xset::anykey( 'User', $haystack ) );
			$this->assertTrue( Xset::anykey( array( 'User' ), $haystack ) );
			$this->assertTrue( Xset::anykey( array( 'User', 'Foo' ), $haystack ) );

			$this->assertFalse( Xset::anykey( array( 'Foo', 'Bar' ), $haystack ) );
        }*/

		/**
		*
		*/

        /*public function testSearch() {
			$haystack = array(
				'User' => array(
					'id' => 1,
					'username' => 'cbuffin',
				),
				'Group' => array(
					'id' => 1,
					'name' => 'Users',
				),
			);

			$expected = array( 'User.id', 'User.username' );
			$this->assertEqual( Xset::search( $haystack, '/^User\.([^\.]+)$/' ), $expected );

			$expected = array( 'User.id' );
			$this->assertEqual( Xset::search( $haystack, '/^User\.([^\.]+)$/', '/^[0-9]+$/' ), $expected );
        }*/
    }
?>