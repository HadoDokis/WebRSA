<?php
	/**
	 * BasicsTest file
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case
	 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */

	/**
	 * ParentClass class
	 *
	 * @package app.Test.Case
	 */
	class ParentClass
	{
		public function foo() {}
	}

	/**
	 * ChildClass class
	 *
	 * @package app.Test.Case
	 */
	class ChildClass extends ParentClass
	{
		public function foo() {}

		public function bar() {}
	}

	/**
	 * BasicsTest class
	 *
	 * @see http://book.cakephp.org/2.0/en/development/testing.html
	 * @package app.Test.Case
	 */
	class BasicsTest extends CakeTestCase
	{
		/**
		 * Test de la fonction array_filter_keys().
		 *
		 * @return void
		 */
		public function testArrayFilterKeys() {
			$array = array( 'foo' => 1, 'bar' => 2 );

			$result = array_filter_keys( $array, array( 'foo' ), false );
			$expected = array( 'foo' => 1 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = array_filter_keys( $array, array( 'foo' ), true );
			$expected = array( 'bar' => 2 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction recursive_key_value_preg_replace().
		 *
		 * @return void
		 */
		public function testRecursiveKeyValuePregReplace() {
			$array = array( 'foo' => 1, 'bar' => 'foo', 'baz' => array( 'foo' => 'foo' ) );

			$result = recursive_key_value_preg_replace( $array, array( '/foo/' => 'Foo' ) );
			$expected = array( 'Foo' => 1, 'bar' => 'Foo', 'baz' => array( 'Foo' => 'Foo' ) );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction strallpos().
		 *
		 * @return void
		 */
		public function testStrallpos() {
			$string = 'Les chaussettes de l\'archiduchesse sont-elles sèches, archi-sèches ?';

			$result = strallpos( $string, "'" );
			$expected = array( 20 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = strallpos( $string, 'sse' );
			$expected = array( 8, 31 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			strallpos( $string, 'sse', ( strlen( $string ) + 1 ) );
		}

		/**
		 * Test de la fonction model_field().
		 *
		 * @return void
		 */
		public function testModelField() {
			$result = model_field( 'Foo.bar' );
			$expected = array( 'Foo', 'bar' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = model_field( 'Foo.Bar.baz' );
			$expected = array( 'Bar', 'baz' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			model_field( 'Foo' );
		}

		/**
		 * Test de la fonction byteSize().
		 *
		 * @return void
		 */
		public function testByteSize() {
			$result = byteSize( 1024 );
			$expected = '1.00 KB';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = byteSize( 2 * 1024 * 1024 );
			$expected = '2.00 MB';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = byteSize( 3 * 1024 * 1024 * 1024 );
			$expected = '3.00 GB';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction valid_int().
		 *
		 * @return void
		 */
		public function testValidInt() {
			$result = valid_int( 1024 );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = valid_int( '1024' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = valid_int( 'foo' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = valid_int( null );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction date_short().
		 *
		 * @return void
		 */
		public function testDateShort() {
			$result = date_short( '2012-01-02' );
			$expected = '02/01/2012';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction required().
		 *
		 * @return void
		 */
		public function testRequired() {
			$result = required( 'Foo' );
			$expected = 'Foo <abbr class="required" title="Champ obligatoire">*</abbr>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		// TODO: cake_version, readTimeout

		/**
		 * Test de la fonction sec2hms().
		 *
		 * @return void
		 */
		public function testSec2hms() {
			$result = sec2hms( 12 );
			$expected = '0:00:12';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = sec2hms( ( ( 60 * 2 ) + 12 ) );
			$expected = '0:02:12';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = sec2hms( ( ( 3 * 60 * 60 ) + ( 2* 60 ) + 12 ) );
			$expected = '3:02:12';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = sec2hms( ( ( 3 * 60 * 60 ) + ( 2* 60 ) + 12 ), true );
			$expected = '03:02:12';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction suffix().
		 *
		 * @return void
		 */
		public function testSuffix() {
			$result = suffix( '11_4' );
			$expected = 4;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = suffix( '11_4.2', '.' );
			$expected = 2;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_depth().
		 *
		 * @return void
		 */
		public function testArrayDepth() {
			$result = array_depth( array( array( null ) ) );
			$expected = 2;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction dateComplete().
		 *
		 * @return void
		 */
		public function testDateComplete() {
			$data = array( 'User' => array( 'birthday' => array( 'hour' => 10, 'minute' => 20, 'second' => 30 ) ) );

			$result = dateComplete( $data, 'User.birthday' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = dateComplete( $data, 'User.arrival' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction implode_assoc().
		 *
		 * @return void
		 */
		public function testImplodeAssoc() {
			$data = array( 'foo' => 'bar', 'bar' => 'baz', 'baz' => null );

			$result = implode_assoc( '/', ':', $data );
			$expected = 'foo:bar/bar:baz/baz:';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = implode_assoc( '/', ':', $data, false );
			$expected = 'foo:bar/bar:baz';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = implode_assoc( '/', ':', array( 'foo' => array( 'bar', 'baz' ) ) );
			$expected = 'foo[]:bar/foo[]:baz';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_avg().
		 *
		 * @return void
		 */
		public function testArrayAvg() {
			$data = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 );

			$result = array_avg( $data );
			$expected = 5;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_range().
		 *
		 * @return void
		 */
		public function testArrayRange() {
			$result = array_range( 2, 3 );
			$expected = array( 2 => 2, 3 => 3 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = array_range( 2, 5, 2 );
			$expected = array( 2 => 2, 4 => 4 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_intersects().
		 *
		 * @return void
		 */
		public function testArrayIntersects() {
			$haystack = array( 1, 2, 4 );
			$result = array_intersects( array( 1, 2, 3 ), $haystack );
			$expected = array( 1, 2 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_filter_values().
		 *
		 * @return void
		 */
		public function testArrayFilterValues() {
			$data = array( 'foo' => 'bar', 'bar' => 'baz', 'baz' => null );

			$result = array_filter_values( $data, array( 'bar' ) );
			$expected = array( 'foo' => 'bar' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = array_filter_values( $data, array( 'bar' ), true );
			$expected = array( 'bar' => 'baz', 'baz' => null );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction get_this_class_methods().
		 *
		 * @return void
		 */
		public function testGetThisClassMethods() {
			$result = get_this_class_methods( 'ParentClass' );
			$expected = array( 'foo' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = get_this_class_methods( 'ChildClass' );
			$expected = array( 1 => 'bar' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_any_key_exists().
		 *
		 * @return void
		 */
		public function testArrayAnyKeyExists() {
			$data = array( 'foo' => 'bar', 'bar' => 'baz', 'baz' => null );

			$result = array_any_key_exists( array( 'bar', 'mu' ), $data );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = array_any_key_exists( 'mu', $data );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction nullify_empty_values().
		 *
		 * @return void
		 */
		public function testNullifyEmptyValues() {
			$result = nullify_empty_values( array( 'foo' => ' ', 'bar' => '', 'baz' => null ) );
			$expected = array( 'foo' => null, 'bar' => null, 'baz' => null );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = nullify_empty_values( array( 'foo' => ' x ' ) );
			$expected = array( 'foo' => ' x ' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction age().
		 *
		 * @return void
		 */
		public function testAge() {
			$result = age( date( 'Y-m-d', strtotime( '-1 year' ) ) );
			$expected = 1;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = age( date( 'Y-m-d', strtotime( '-33 year -6 months' ) ) );
			$expected = 33;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}


		/**
		 * Test de la fonction cle_nir().
		 *
		 * @return void
		 */
		 public function testCleNir() {
			$result = cle_nir( '179012A001234' );
			$expected = '71';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = cle_nir( '179012B001234' );
			$expected = '01';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			$result = cle_nir( 'B79012B001234' );
		 }

		/**
		 * Test de la fonction valid_nir().
		 *
		 * @return void
		 */
		 public function testValidNir() {
			$result = valid_nir( '179012A00123471' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = valid_nir( 'A79012A00123471' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction array_words_replace().
		 *
		 * @return void
		 */
		 public function testArrayWordsReplace() {
			$result = array_words_replace(
				array( 'Foo.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Foo.bar = Bar.foo' ) ),
				array( 'Foo' => 'Baz' )
			);
			$expected = array( 'Baz.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Baz.bar = Bar.foo' ) );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction php_associative_array_to_js().
		 *
		 * @return void
		 */
		 public function testPhpAssociativeArrayToJs() {
			 $data = array( 'Foo.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Foo.bar = Bar.foo' ) );
			$result = php_associative_array_to_js( $data );
			$expected = '{ "Foo.id" : { "Bar" : "1" }, "Foobar" : { "0" : "Foo.bar = Bar.foo" } }';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		 // TODO: apache_version

		/**
		 * Test de la fonction date_cakephp_to_sql().
		 *
		 * @return void
		 */
		 public function testDateCakephpToSql() {
			$result = date_cakephp_to_sql( array( 'year' => '1979', 'month' => '01', 'day' => '24' ) );
			$expected = '1979-01-24';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = date_cakephp_to_sql( array( 'year' => '1979', 'month' => '01', 'day' => '24', 'minutes' => 50 ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction full_array_diff().
		 *
		 * @return void
		 */
		 public function testFullArrayDiff() {
			$a1 = array( 'foo', 'bar' );
			$a2 = array( 'foo', 'bar', 'baz' );
			$a3 = array( 'bar', 'baz' );

			$result = full_array_diff( $a1, $a1 );
			$expected = array();
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = full_array_diff( $a1, $a2 );
			$expected = array( 4 => 'baz' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = full_array_diff( $a1, $a3 );
			$expected = array( 'foo', 3 => 'baz' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction value().
		 *
		 * @return void
		 */
		 public function testValue() {
			$result = value( array( 'foo' => 'bar', 'bar' => 'baz' ), 'bar' );
			$expected = 'baz';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = value( array( 'foo' => 'bar', 'bar' => 'baz' ), 'baz' );
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction replace_accents().
		 *
		 * @return void
		 */
		 public function testReplaceAccents() {
			$result = replace_accents( 'Âéï' );
			$expected = 'Aei';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction validRib().
		 *
		 * @return void
		 */
		 public function testValidRib() {
			$result = validRib( '20041', '01005', '0500013M026', '06' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = validRib( '00000', '0000000000', '0000000000', '97' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }
	}
?>