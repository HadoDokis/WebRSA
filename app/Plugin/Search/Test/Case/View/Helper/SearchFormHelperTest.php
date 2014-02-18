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
		 * Fixtures utilisées.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple'
		);

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
			$expected = '<div class="input checkbox"><input type="hidden" name="data[Search][Dossier][etatdosrsa_choice]" id="SearchDossierEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Search][Dossier][etatdosrsa_choice]"  value="1" id="SearchDossierEtatdosrsaChoice"/><label for="SearchDossierEtatdosrsaChoice">Search.Dossier.etatdosrsa_choice</label></div><fieldset id="SearchDossierEtatdosrsaFieldset"><legend>Search.Dossier.etatdosrsa</legend><div class="input select"><input type="hidden" name="data[Search][Dossier][etatdosrsa]" value="" id="SearchDossierEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Dossier][etatdosrsa][]" value="2" id="SearchDossierEtatdosrsa2" /><label for="SearchDossierEtatdosrsa2">Ouvert</label></div>
<div class="checkbox"><input type="checkbox" name="data[Search][Dossier][etatdosrsa][]" value="6" id="SearchDossierEtatdosrsa6" /><label for="SearchDossierEtatdosrsa6">Clos</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierEtatdosrsaChoice\', $( \'SearchDossierEtatdosrsaFieldset\' ), false ); } );
//]]>
</script>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchFormHelper::dateRange()
		 */
		public function testDateRange() {
			$result = $this->SearchForm->dateRange( 'Search.Apple.date' );

			// On peut factoriser
			$thisYear = date( 'Y' );

			$years = range( $thisYear, $thisYear - 120, -1 );
			foreach( $years as $i => $year ) {
				$selected = ( ( $year == $thisYear ) ? ' selected="selected"' : '' );
				$years[$i] = "<option value=\"{$year}\"{$selected}>{$year}</option>";
			}
			$yearsFrom = implode( "\n", $years );

			$years = range( $thisYear + 5, $thisYear - 120, -1 );
			foreach( $years as $i => $year ) {
				$selected = ( ( $year == $thisYear ) ? ' selected="selected"' : '' );
				$years[$i] = "<option value=\"{$year}\"{$selected}>{$year}</option>";
			}
			$yearsTo = implode( "\n", $years );

			$expected = '<script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchAppleDate\', $( \'SearchAppleDate_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Apple][date]" id="SearchAppleDate_" value="0"/><input type="checkbox" name="data[Search][Apple][date]"  value="1" id="SearchAppleDate"/><label for="SearchAppleDate">Filtrer par apple.date</label></div><fieldset id="SearchAppleDate_from_to"><legend>Apple.date</legend><div class="input date"><label for="SearchAppleDateFromDay">Du (inclus)</label><select name="data[Search][Apple][date_from][day]" id="SearchAppleDateFromDay">
<option value="01">1</option>
<option value="02">2</option>
<option value="03">3</option>
<option value="04">4</option>
<option value="05">5</option>
<option value="06">6</option>
<option value="07">7</option>
<option value="08">8</option>
<option value="09">9</option>
<option value="10">10</option>
<option value="11" selected="selected">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>-<select name="data[Search][Apple][date_from][month]" id="SearchAppleDateFromMonth">
<option value="01">janvier</option>
<option value="02" selected="selected">février</option>
<option value="03">mars</option>
<option value="04">avril</option>
<option value="05">mai</option>
<option value="06">juin</option>
<option value="07">juillet</option>
<option value="08">août</option>
<option value="09">septembre</option>
<option value="10">octobre</option>
<option value="11">novembre</option>
<option value="12">décembre</option>
</select>-<select name="data[Search][Apple][date_from][year]" id="SearchAppleDateFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchAppleDateToDay">Au (inclus)</label><select name="data[Search][Apple][date_to][day]" id="SearchAppleDateToDay">
<option value="01">1</option>
<option value="02">2</option>
<option value="03">3</option>
<option value="04">4</option>
<option value="05">5</option>
<option value="06">6</option>
<option value="07">7</option>
<option value="08">8</option>
<option value="09">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18" selected="selected">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>-<select name="data[Search][Apple][date_to][month]" id="SearchAppleDateToMonth">
<option value="01">janvier</option>
<option value="02" selected="selected">février</option>
<option value="03">mars</option>
<option value="04">avril</option>
<option value="05">mai</option>
<option value="06">juin</option>
<option value="07">juillet</option>
<option value="08">août</option>
<option value="09">septembre</option>
<option value="10">octobre</option>
<option value="11">novembre</option>
<option value="12">décembre</option>
</select>-<select name="data[Search][Apple][date_to][year]" id="SearchAppleDateToYear">
'.$yearsTo.'
</select></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode SearchForm::observeDisableFormOnSubmit()
		 */
		public function testObserveDisableFormOnSubmit() {
			// Sans message à l'utilisateur
			$result = $this->SearchForm->observeDisableFormOnSubmit( 'UsersEditForm' );
			$expected = '<script type=\'text/javascript\'>document.observe( \'dom:loaded\', function() {
					observeDisableFormOnSubmit( \'UsersEditForm\' );
				} );</script>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Avec message à l'utilisateur
			$result = $this->SearchForm->observeDisableFormOnSubmit( 'UsersEditForm', 'Merci de patienter' );
			$expected = '<script type=\'text/javascript\'>document.observe( \'dom:loaded\', function() {
					observeDisableFormOnSubmit( \'UsersEditForm\', \'Merci de patienter\' );
				} );</script>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>