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
		 * Test de la fonction app_version().
		 */
		public function testAppVersion() {
			$result = app_version();
			$this->assertPattern( '/^[0-9]+\.[0-9]+/', $result );
		}

		/**
		 * Test de la fonction array_filter_keys().
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
		 */
		public function testRecursiveKeyValuePregReplace() {
			$array = array( 'foo' => 1, 'bar' => 'foo', 'baz' => array( 'foo' => 'foo' ) );

			$result = recursive_key_value_preg_replace( $array, array( '/foo/' => 'Foo' ) );
			$expected = array( 'Foo' => 1, 'bar' => 'Foo', 'baz' => array( 'Foo' => 'Foo' ) );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction strallpos().
		 */
		public function testStrallpos() {
			$string = 'Les chaussettes de l\'archiduchesse sont-elles sèches, archi-sèches ?';

			$result = strallpos( $string, "'" );
			$expected = array( 20 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = strallpos( $string, 'sse' );
			$expected = array( 8, 31 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction strallpos() avec une erreur.
		 */
		public function testStrallposError() {
			$string = 'Les chaussettes de l\'archiduchesse sont-elles sèches, archi-sèches ?';

			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			strallpos( $string, 'sse', ( strlen( $string ) + 1 ) );
		}

		/**
		 * Test de la fonction model_field().
		 */
		public function testModelField() {
			$result = model_field( 'Foo.bar' );
			$expected = array( 'Foo', 'bar' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = model_field( 'Foo.Bar.baz' );
			$expected = array( 'Bar', 'baz' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction model_field() avec une erreur.
		 */
		public function testModelFieldWithError1() {
			$result = @model_field( 'Foo' );
			$this->assertEqual( $result, null, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction model_field() avec une erreur.
		 */
		public function testModelFieldWithError2() {
			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			model_field( 'Foo' );
		}

		/**
		 * Test de la fonction byteSize().
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
		 */
		public function testDateShort() {
			$result = date_short( '2012-01-02' );
			$expected = '02/01/2012';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = date_short( '2012-02-28 11:05:33' );
			$expected = '28/02/2012';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = date_short( null );
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction required().
		 */
		public function testRequired() {
			$result = required( 'Foo' );
			$expected = 'Foo <abbr class="required" title="Champ obligatoire">*</abbr>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction readTimeout().
		 */
		public function testReadTimeout() {
			$saved = Configure::read( 'Session.save' );

			Configure::write( 'Session.save', null );
			$result = readTimeout();
			$this->assertPattern( '/^[0-9]+$/', (string)$result );

			Configure::write( 'Session.save', 'cake' );
			$result = readTimeout();
			$this->assertPattern( '/^[0-9]+$/', (string)$result );

			Configure::write( 'Session.save', $saved );
		}

		/**
		 * Test de la fonction sec2hms().
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
		 */
		public function testSuffix() {
			$result = suffix( '11_4' );
			$expected = 4;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = suffix( '11_4.2', '.' );
			$expected = 2;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = suffix( '11+4', '+' );
			$expected = '4';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = suffix( '12+-+5', '+-+' );
			$expected = '5';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction prefix().
		 */
		public function testPrefix() {
			$result = prefix( '11_4' );
			$expected = 11;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = prefix( '11_4.2', '.' );
			$expected = '11_4';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = prefix( '11+4', '+' );
			$expected = '11';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = prefix( '12+-+5', '+-+' );
			$expected = '12';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_depth().
		 */
		public function testArrayDepth() {
			$result = array_depth( array( array( null ) ) );
			$expected = 2;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction dateComplete().
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
		 */
		public function testArrayAvg() {
			$data = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 );
			$result = array_avg( $data );
			$expected = 5;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = array_avg( array() );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_range().
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
		 */
		public function testArrayIntersects() {
			$haystack = array( 1, 2, 4 );

			$result = array_intersects( array( 1, 2, 3 ), $haystack );
			$expected = array( 1, 2 );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = array_intersects( array( 3, 5 ), $haystack );
			$expected = array();
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction array_filter_values().
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
		 */
		public function testAge() {
			$result = age( null );
			$expected = null;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = age( date( 'Y-m-d', strtotime( '-1 year' ) ) );
			$expected = 1;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = age( date( 'Y-m-d', strtotime( '-33 year -6 months' ) ) );
			$expected = 33;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = age( date( 'Y-m-d', strtotime( '1979-01-24' ) ), date( 'Y-m-d', strtotime( '2013-07-02' ) ) );
			$expected = 34;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction cle_nir().
		 */
		 public function testCleNir() {
			$result = cle_nir( '179012A001234' );
			$expected = '71';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = cle_nir( '179012B001234' );
			$expected = '01';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction cle_nir() avec une erreur.
		 */
		 public function testCleNirError1() {
			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			$result = cle_nir( 'B79012B001234' );
		 }

		/**
		 * Test de la fonction cle_nir() avec une erreur.
		 */
		 public function testCleNirError2() {
			$this->expectError( 'PHPUnit_Framework_Error_Warning' );
			$result = cle_nir( '179013400123456' );
		 }

		/**
		 * Test de la fonction valid_nir().
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
		 */
		 public function testPhpAssociativeArrayToJs() {
			 $data = array( 'Foo.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Foo.bar = Bar.foo' ) );
			$result = php_associative_array_to_js( $data );
			$expected = '{ "Foo.id" : { "Bar" : "1" }, "Foobar" : { "0" : "Foo.bar = Bar.foo" } }';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction apache_bin().
		 */
		 public function testApacheBin() {
			 $saved = Configure::read( 'apache_bin' );

			 $result = apache_bin();
			 $this->assertPattern( '/^\/[^\/]+/', $result );

			 Configure::write( 'apache_bin', null );
			 $result = apache_bin();
			 $this->assertPattern( '/^\/[^\/]+/', $result );

			 Configure::write( 'apache_bin', $saved );
		 }

		/**
		 * Test de la fonction apache_version().
		 */
		 public function testApacheVersion() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			 $result = apache_version();
			 $this->assertPattern( '/^[0-9]+\.[0-9]+/', $result );
		 }

		/**
		 * Test de la fonction apache_modules().
		 */
		 public function testApacheModules() {
			if( defined( 'CAKEPHP_SHELL' ) && CAKEPHP_SHELL ) {
				$this->markTestSkipped( 'Ce test ne peux être exécuté que dans un navigateur.' );
			}

			 $result = (array)apache_modules();
			 $intersect = array_intersect( $result, array( 'mod_expires', 'mod_php5', 'mod_rewrite' ) );
			 $this->assertTrue( !empty( $intersect ) );
		 }

		/**
		 * Test de la fonction date_cakephp_to_sql().
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
		 * Test de la fonction time_cakephp_to_sql().
		 */
		 public function testTimeCakephpToSql() {
			$result = time_cakephp_to_sql( array( 'hour' => '15', 'min' => '01' ) );
			$expected = '15:01:00';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = time_cakephp_to_sql( array( 'hour' => '15', 'min' => '01', 'sec' => '30' ) );
			$expected = '15:01:30';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = time_cakephp_to_sql( array( 'hour' => '15', 'min' => '01', 'sec' => '30', 'year' => '2012' ) );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction full_array_diff().
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
		 */
		 public function testReplaceAccents() {
			$result = replace_accents( 'Âéï' );
			$expected = 'Aei';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction noaccents_upper().
		 */
		 public function testNoaccentsUpper() {
			$result = noaccents_upper( 'Âéï' );
			$expected = 'AEI';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction domId().
		 */
		 public function testDomId() {
			$result = domId( 'Foo.bar_id' );
			$expected = 'FooBarId';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction validRib().
		 */
		 public function testValidRib() {
			$result = validRib( '20041', '01005', '0500013M026', '06' );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = validRib( '00000', '0000000000', '0000000000', '97' );
			$expected = false;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction vfListeToArray().
		 */
		 public function testVfListeToArray() {
			$result =  vfListeToArray( "- CAF\n\r- MSA" );
			$expected = array( ' CAF', ' MSA' );
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		/**
		 * Test de la fonction js_escape().
		 */
		 public function testJsEscape() {
			$result =  js_escape( "Bonjour Monsieur \"Auzolat\"\nvous devez vous présenter ..." );
			$expected = 'Bonjour Monsieur \\"Auzolat\\"\\nvous devez vous présenter ...';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		 }

		 /**
		  * Test de la fonction array_remove()
		  */
		 public function testArrayRemove() {
			 // 1. Pas de paramètre supplémentaire
			 $array = array( 1, 2, '3', '4', 5 );
			 array_remove( $array, 4 );

			 $expected = array( 0 => 1, 1 => 2, 2 => 3, 4 => 5 );
			 $this->assertEqual( $array, $expected, var_export( $array, true ) );

			 // 2. Paramètre strict
			 $array = array( 1, 2, '3', '4', 5 );
			 array_remove( $array, 4, true );

			 $expected = array( 1, 2, '3', '4', 5 );
			 $this->assertEqual( $array, $expected, var_export( $array, true ) );

			 // 3. Paramètre strict
			 $array = array( 1, 2, '3', 4, 5 );
			 array_remove( $array, 4, true );

			 $expected = array( 0 => 1, 1 => 2, 2 => 3, 4 => 5 );
			 $this->assertEqual( $array, $expected, var_export( $array, true ) );

			 // 4. Paramètre reorder
			 $array = array( 1, 2, '3', 4, 5 );
			 array_remove( $array, 4, false, true );

			 $expected = array( 1, 2, 3, 5 );
			 $this->assertEqual( $array, $expected, var_export( $array, true ) );
		}

		/**
		 * Test de la fonction trim_mixed()
		 */
		public function testTrimMixed() {
			// 1. Avec une chaîne de caractères
			$string = ' "Foo, bar" ';
			$result = trim_mixed( $string );
			$expected = 'Foo, bar';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Avec un array à une dimension
			$array = array(
				0 => "\tBaz",
				'foo' => ' "Foo, bar" '
			);
			$result = trim_mixed( $array );
			$expected = array(
				0 => 'Baz',
				'foo' => 'Foo, bar',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 3. Avec un array à plusieurs dimensions
			$array = array(
				0 => "\tBaz",
				'foo' => ' "Foo, bar" ',
				1 => array(
					2 => ' "Bar" ',
				)
			);
			$result = trim_mixed( $array );
			$expected = array(
				0 => 'Baz',
				'foo' => 'Foo, bar',
				1 => array(
					2 => 'Bar',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction parse_csv_line()()
		 */
		public function testParseCsvLine() {
			// 1. Séparateur et délimiteur par défaut
			$line = '"Prescription professionnelle","Accompagnement a la creation d activite",,"ADIE   Association pour le Droit a l Initiative Economique   ","Micro credit professionnel, Pret d honneur, Accompagnement a la creation d entreprise","Metiers divers","0149331833","113   115 rue Daniele Casanova","93200","Saint   Denis"';
			$result = parse_csv_line( $line );
			$expected = array(
				'Prescription professionnelle',
				'Accompagnement a la creation d activite',
				NULL,
				'ADIE   Association pour le Droit a l Initiative Economique',
				'Micro credit professionnel, Pret d honneur, Accompagnement a la creation d entreprise',
				'Metiers divers',
				'0149331833',
				'113   115 rue Daniele Casanova',
				'93200',
				'Saint   Denis',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Séparateur et délimiteur spécifiés
			$line = "Foo;' Bar; ';'\'Baz'";
			$result = parse_csv_line( $line, ';', '\'' );
			$expected = array(
				'Foo',
				'Bar;',
				'\\\'Baz',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction hash_keys()
		 */
		public function testHashKeys() {
			$data = array(
				'Foo' => array(
					'Bar' => array(
						'Baz' => 1
					)
				),
				'Bar' => array(
					'Baz' => 1
				),
				'Baz' => 1
			);

			$result = hash_keys( $data );
			$expected = array(
				'Foo.Bar',
				'Bar.Baz',
				'Baz',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la fonction preg_test().
		 */
		public function testPregTest() {
			// 1. La chaîne vide n'est pas une expression régulière correcte
			$result = preg_test( '' );
			$this->assertFalse( $result );

			// 2. Expression correcte
			$result = preg_test( '/Foo/i' );
			$this->assertTrue( $result );

			// 3. Expression incorrecte
			$result = preg_test( '/[Foo/i' );
			$this->assertFalse( $result );
		}

		/**
		 * Test de la fonction departement_uses_class().
		 */
		public function testDepartementUsesClass() {
			Configure::write( 'Cg.departement', 93 );

			$this->assertTrue( departement_uses_class( 'Orientstruct' ) );
			$this->assertTrue( departement_uses_class( 'Cer93' ) );
			$this->assertTrue( departement_uses_class( 'Orientstruct2' ) );

			$this->assertFalse( departement_uses_class( 'Apre66' ) );
			$this->assertFalse( departement_uses_class( 'Apre66' ) );
			$this->assertFalse( departement_uses_class( 'Reorientationep976' ) );
		}
	}
?>