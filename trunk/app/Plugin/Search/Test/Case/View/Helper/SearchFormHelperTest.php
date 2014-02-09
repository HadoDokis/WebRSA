<?php
	/**
	 * Code source de la classe SearchFormHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Search
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'SearchFormHelper', 'Search.View/Helper' );

	/**
	 * La classe SearchFormHelperTest ...
	 *
	 * @package Search
	 * @subpackage Test.Case.View.Helper
	 */
	class SearchFormHelperTest extends CakeTestCase
	{
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
		 * @var SearchForm
		 */
		public $SearchForm = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$Request = new CakeRequest();
			$this->Controller = new Controller( $Request );
			$this->View = new View( $this->Controller );
			$this->SearchForm = new SearchFormHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Controller, $this->View, $this->SearchForm );
			parent::tearDown();
		}

		/**
		 * Test de la méthode SearchFormHelper::dependantCheckboxes()
		 *
		 * @medium
		 */
		public function testDependantCheckboxes() {
			$this->Controller->request->addParams(
				array(
					'controller' => 'users',
					'action' => 'index',
					'pass' => array( ),
					'named' => array( )
				)
			);
			$options = array( '2' => 'Ouvert', '6' => 'Clos' );

			$result = $this->SearchForm->dependantCheckboxes( 'Search.Dossier.etatdosrsa', $options );
			$expected = '<div class="input checkbox"><input type="hidden" name="data[Search][Dossier][etatdosrsa_choice]" id="SearchDossierEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Search][Dossier][etatdosrsa_choice]"  value="1" id="SearchDossierEtatdosrsaChoice"/><label for="SearchDossierEtatdosrsaChoice">Search.Dossier.etatdosrsa_choice</label></div><fieldset id="SearchDossierEtatdosrsa"><legend>Search.Dossier.etatdosrsa</legend><div class="input select"><input type="hidden" name="data[Search][Dossier][etatdosrsa]" value="" id="SearchDossierEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Dossier][etatdosrsa][]" value="2" id="SearchDossierEtatdosrsa2" /><label for="SearchDossierEtatdosrsa2">Ouvert</label></div>
<div class="checkbox"><input type="checkbox" name="data[Search][Dossier][etatdosrsa][]" value="6" id="SearchDossierEtatdosrsa6" /><label for="SearchDossierEtatdosrsa6">Clos</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierEtatdosrsaChoice\', $( \'SearchDossierEtatdosrsa\' ), false ); } );
//]]>
</script>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>