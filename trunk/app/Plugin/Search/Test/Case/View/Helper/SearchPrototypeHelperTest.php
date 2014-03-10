<?php
	/**
	 * Code source de la classe SearchPrototypeHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'SearchPrototypeHelper', 'Search.View/Helper' );

	/**
	 * La classe SearchPrototypeHelperTest ...
	 *
	 * @package app.Test.Case.View.Helper
	 */
	class SearchPrototypeHelperTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisées pour les tests.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple',
		);

		/**
		 * Le contrôleur utilisé pour les tests.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Le contrôleur utilisé pour les tests.
		 *
		 * @var View
		 */
		public $View = null;

		/**
		 * Le helper à tester.
		 *
		 * @var SearchPrototype
		 */
		public $SearchPrototype = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$Request = new CakeRequest();
			$this->Controller = new Controller( $Request );
			$this->View = new View( $this->Controller );
			$this->SearchPrototype = new SearchPrototypeHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Controller, $this->View, $this->SearchPrototype );
		}

		/**
		 * Test de la méthode SearchPrototypeHelper::observeDisableFormOnSubmit()
		 */
		public function testObserveDisableFormOnSubmit() {
			// 1. Sans message
			$result = $this->SearchPrototype->observeDisableFormOnSubmit( 'UsersLoginForm' );
			$expected = '<script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFormOnSubmit( \'UsersLoginForm\' ); } );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// 2. Avec message
			$result = $this->SearchPrototype->observeDisableFormOnSubmit( 'UsersLoginForm', 'Connexion en cours' );
			$expected = '<script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFormOnSubmit( \'UsersLoginForm\', \'Connexion en cours\' ); } );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchPrototypeHelper::observeDisableFieldsetOnCheckbox()
		 */
		public function testObserveDisableFieldsetOnCheckbox() {
			// 1. Sans message
			$result = $this->SearchPrototype->observeDisableFieldsetOnCheckbox( 'Search.User.birthday', 'SearchUserBirthdayRange' );
			$expected = '<script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchUserBirthday\', \'SearchUserBirthdayRange\', false, false ); } );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchPrototypeHelper::observeDependantSelect()
		 */
		public function testObserveDependantSelect() {
			// 1. Sans message
			$result = $this->SearchPrototype->observeDependantSelect(
				array(
					'Search.User.continent' => 'Search.User.country',
					'Search.User.country' => 'Search.User.region',
				)
			);
			$expected = '<script type="text/javascript">
//<![CDATA[
document.observe( "dom:loaded", function() { dependantSelect( \'SearchUserCountry\', \'SearchUserContinent\' );
dependantSelect( \'SearchUserRegion\', \'SearchUserCountry\' );
 } );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchPrototypeHelper::observeDisableFieldsOnValue()
		 */
		public function testObserveDisableFieldsOnValue() {
			// 1. Sans message
			$result = $this->SearchPrototype->observeDisableFieldsOnValue(
				'Ficheprescription93.personne_a_integre',
				array(
					'Ficheprescription93.personne_date_integration.day',
					'Ficheprescription93.personne_date_integration.month',
					'Ficheprescription93.personne_date_integration.year',
				),
				array( '', '0' ),
				true
			);
			$expected = '<script type="text/javascript">
//<![CDATA[
document.observe( "dom:loaded", function() { observeDisableFieldsOnValue( \'Ficheprescription93PersonneAIntegre\', [ \'Ficheprescription93PersonneDateIntegrationDay\', \'Ficheprescription93PersonneDateIntegrationMonth\', \'Ficheprescription93PersonneDateIntegrationYear\' ], [ \'\', \'0\' ], true, false );
 } );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>