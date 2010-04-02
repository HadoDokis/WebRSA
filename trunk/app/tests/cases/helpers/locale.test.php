<?php
	// ajout de la classe de test contenant les fonctions startTest et tearDown
	require_once( dirname( __FILE__ ).'/../cake_app_helper_test_case.php' );

	//Import the helper to be tested.
	//If the tested helper were using some other helper, like Html, 
	//it should be impoorted in this line, and instantialized in startTest().
	App::import('Helper', 'Time');
	App::import('Helper', 'Locale');
	
	class LocaleTestCase extends CakeAppHelperTestCase {
		public $Locale = null;

		public $fixtures = array();

		public function testDate() {
			$this->assertEqual(null, $this->Locale->date("%A,%B,%Y",null));
			$this->assertEqual(null, $this->Locale->date("%d,%m,%Y",123456789));
			$this->assertEqual("17,03,2010", $this->Locale->date("%d,%m,%Y","17-03-2010"));
			$this->assertEqual('jeudi,janvier,1970', $this->Locale->date("%A,%B,%Y","01-01-1970"));
		}

		public function testMoney() {
			$this->assertError( $this->Locale->money("abc") );
			$this->assertEqual( null, $this->Locale->money("") );
			$this->assertEqual("0,00 €", $this->Locale->money("0") );
			$this->assertEqual("0,00 €", $this->Locale->money(0) );
			$this->assertEqual( null, $this->Locale->money(null) );
			$this->assertEqual("1 000,00 €", $this->Locale->money("1000"));
 			$this->assertEqual("876,49 €", $this->Locale->money("876.49"));
 			$this->assertError($this->Locale->money("123,45"));
			$this->assertEqual("25,00 €", $this->Locale->money(25));
			$this->assertEqual("37,98 €", $this->Locale->money(37.98));
		}

		public function testNumber() {
			$this->assertError($this->Locale->number("abc",0));
			$this->assertError($this->Locale->number(10,"abc"));
			$this->assertEqual("38", $this->Locale->number(38,0));
			$this->assertEqual("25,1", $this->Locale->number(25.12,1));
			$this->assertEqual("98,76000", $this->Locale->number(98.76,5));
			$this->assertError($this->Locale->number("54,12",2));
		}
	}
?>