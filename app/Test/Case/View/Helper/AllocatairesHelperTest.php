<?php
	/**
	 * Code source de la classe AllocatairesHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('Controller', 'Controller');
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'AllocatairesHelper', 'View/Helper' );

	require_once dirname( __FILE__ ). DS.'..'. DS.'..'.DS.'cake_test_select_options.php';

	/**
	 * Classe AllocatairesHelperTest.
	 *
	 * @package app.Test.Case.Model.Behavior
	 */
	class AllocatairesHelperTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Calculdroitrsa',
			'app.Detailcalculdroitrsa',
			'app.Detaildroitrsa',
			'app.Dossier',
			'app.Foyer',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Referent',
			'app.Situationdossierrsa',
			'app.Structurereferente',
		);

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->Allocataires = new AllocatairesHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->Allocataires );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier()
		 */
		public function testBlocDossier() {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			// -----------------------------------------------------------------

			$result = $this->Allocataires->blocDossier( array() );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierDtdemrsa\', $( \'SearchDossierDtdemrsa_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dtdemrsa]" id="SearchDossierDtdemrsa_" value="0"/><input type="checkbox" name="data[Search][Dossier][dtdemrsa]"  value="1" id="SearchDossierDtdemrsa"/><label for="SearchDossierDtdemrsa">Filtrer par date de demande RSA</label></div><fieldset id="SearchDossierDtdemrsa_from_to"><legend>Date de demande RSA</legend><div class="input date"><label for="SearchDossierDtdemrsaFromDay">Du (inclus)</label><select name="data[Search][Dossier][dtdemrsa_from][day]" id="SearchDossierDtdemrsaFromDay">
'.$daysFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][month]" id="SearchDossierDtdemrsaFromMonth">
'.$monthsFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][year]" id="SearchDossierDtdemrsaFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchDossierDtdemrsaToDay">Au (inclus)</label><select name="data[Search][Dossier][dtdemrsa_to][day]" id="SearchDossierDtdemrsaToDay">
'.$daysTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][month]" id="SearchDossierDtdemrsaToMonth">
'.$monthsTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][year]" id="SearchDossierDtdemrsaToYear">
'.$yearsTo.'
</select></div></fieldset><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() sans fieldset.
		 */
		public function testBlocDossierNoFieldset() {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			// -----------------------------------------------------------------

			$result = $this->Allocataires->blocDossier( array( 'fieldset' => false ) );
			$expected = '<div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierDtdemrsa\', $( \'SearchDossierDtdemrsa_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dtdemrsa]" id="SearchDossierDtdemrsa_" value="0"/><input type="checkbox" name="data[Search][Dossier][dtdemrsa]"  value="1" id="SearchDossierDtdemrsa"/><label for="SearchDossierDtdemrsa">Filtrer par date de demande RSA</label></div><fieldset id="SearchDossierDtdemrsa_from_to"><legend>Date de demande RSA</legend><div class="input date"><label for="SearchDossierDtdemrsaFromDay">Du (inclus)</label><select name="data[Search][Dossier][dtdemrsa_from][day]" id="SearchDossierDtdemrsaFromDay">
'.$daysFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][month]" id="SearchDossierDtdemrsaFromMonth">
'.$monthsFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][year]" id="SearchDossierDtdemrsaFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchDossierDtdemrsaToDay">Au (inclus)</label><select name="data[Search][Dossier][dtdemrsa_to][day]" id="SearchDossierDtdemrsaToDay">
'.$daysTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][month]" id="SearchDossierDtdemrsaToMonth">
'.$monthsTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][year]" id="SearchDossierDtdemrsaToYear">
'.$yearsTo.'
</select></div></fieldset><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() avec des options
		 * pour l'etatdosrsa.
		 */
		public function testBlocDossierOptions() {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			// -----------------------------------------------------------------

			$params = array(
				'options' => array(
					'Situationdossierrsa' => array(
						'etatdosrsa' => array( 'Z' => 'Non défini' )
					)
				)
			);

			$result = $this->Allocataires->blocDossier( $params );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchDossierDtdemrsa\', $( \'SearchDossierDtdemrsa_from_to\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dtdemrsa]" id="SearchDossierDtdemrsa_" value="0"/><input type="checkbox" name="data[Search][Dossier][dtdemrsa]"  value="1" id="SearchDossierDtdemrsa"/><label for="SearchDossierDtdemrsa">Filtrer par date de demande RSA</label></div><fieldset id="SearchDossierDtdemrsa_from_to"><legend>Date de demande RSA</legend><div class="input date"><label for="SearchDossierDtdemrsaFromDay">Du (inclus)</label><select name="data[Search][Dossier][dtdemrsa_from][day]" id="SearchDossierDtdemrsaFromDay">
'.$daysFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][month]" id="SearchDossierDtdemrsaFromMonth">
'.$monthsFrom.'
</select>-<select name="data[Search][Dossier][dtdemrsa_from][year]" id="SearchDossierDtdemrsaFromYear">
'.$yearsFrom.'
</select></div><div class="input date"><label for="SearchDossierDtdemrsaToDay">Au (inclus)</label><select name="data[Search][Dossier][dtdemrsa_to][day]" id="SearchDossierDtdemrsaToDay">
'.$daysTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][month]" id="SearchDossierDtdemrsaToMonth">
'.$monthsTo.'
</select>-<select name="data[Search][Dossier][dtdemrsa_to][year]" id="SearchDossierDtdemrsaToYear">
'.$yearsTo.'
</select></div></fieldset><div class="input checkbox"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa_choice]" id="SearchSituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa_choice]"  value="1" id="SearchSituationdossierrsaEtatdosrsaChoice"/><label for="SearchSituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="SearchSituationdossierrsaEtatdosrsaFieldset"><legend>États du dossier</legend><div class="input select required"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa]" value="" id="SearchSituationdossierrsaEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa][]" value="Z" id="SearchSituationdossierrsaEtatdosrsaZ" /><label for="SearchSituationdossierrsaEtatdosrsaZ">Non défini</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchSituationdossierrsaEtatdosrsaChoice\', $( \'SearchSituationdossierrsaEtatdosrsaFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() en ne faisant pas
		 * la plage de date de dtdemrsa.
		 */
		public function testBlocDossierSkip() {
			$params = array(
				'options' => array(
					'Situationdossierrsa' => array(
						'etatdosrsa' => array( 'Z' => 'Non défini' )
					)
				),
				'skip' => array(
					'Search.Dossier.dtdemrsa'
				)
			);

			$result = $this->Allocataires->blocDossier( $params );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="SearchDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Search][Dossier][numdemrsa]" maxlength="11" type="text" id="SearchDossierNumdemrsa"/></div><div class="input text"><label for="SearchDossierMatricule">Numéro CAF</label><input name="data[Search][Dossier][matricule]" maxlength="15" type="text" id="SearchDossierMatricule"/></div><div class="input checkbox"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa_choice]" id="SearchSituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa_choice]"  value="1" id="SearchSituationdossierrsaEtatdosrsaChoice"/><label for="SearchSituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="SearchSituationdossierrsaEtatdosrsaFieldset"><legend>États du dossier</legend><div class="input select required"><input type="hidden" name="data[Search][Situationdossierrsa][etatdosrsa]" value="" id="SearchSituationdossierrsaEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Search][Situationdossierrsa][etatdosrsa][]" value="Z" id="SearchSituationdossierrsaEtatdosrsaZ" /><label for="SearchSituationdossierrsaEtatdosrsaZ">Non défini</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SearchSituationdossierrsaEtatdosrsaChoice\', $( \'SearchSituationdossierrsaEtatdosrsaFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Search][Dossier][dernier]" id="SearchDossierDernier_" value="0"/><input type="checkbox" name="data[Search][Dossier][dernier]"  value="1" id="SearchDossierDernier"/><label for="SearchDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode AllocatairesHelper::blocDossier() en changeant le
		 * préfixe.
		 *
		 * @todo: les traductions ne sont pas faites dans ce cas-là...
		 */
		/*public function testBlocDossierPrefix() {
			$params = array(
				'options' => array(
					'Situationdossierrsa' => array(
						'etatdosrsa' => array( 'Z' => 'Non défini' )
					)
				),
				'skip' => array(
					'Search.Dossier.dtdemrsa'
				),
				'prefix' => 'Filter'
			);

			$result = $this->Allocataires->blocDossier( $params );
			$expected = '<fieldset><legend>Recherche par dossier</legend><div class="input text required"><label for="FilterDossierNumdemrsa">Numéro de dossier RSA</label><input name="data[Filter][Dossier][numdemrsa]" maxlength="11" type="text" id="FilterDossierNumdemrsa"/></div><div class="input text"><label for="FilterDossierMatricule">Numéro CAF</label><input name="data[Filter][Dossier][matricule]" maxlength="15" type="text" id="FilterDossierMatricule"/></div><div class="input checkbox"><input type="hidden" name="data[Filter][Situationdossierrsa][etatdosrsa_choice]" id="FilterSituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Filter][Situationdossierrsa][etatdosrsa_choice]"  value="1" id="FilterSituationdossierrsaEtatdosrsaChoice"/><label for="FilterSituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="FilterSituationdossierrsaEtatdosrsaFieldset"><legend>États du dossier</legend><div class="input select required"><input type="hidden" name="data[Filter][Situationdossierrsa][etatdosrsa]" value="" id="FilterSituationdossierrsaEtatdosrsa"/>

<div class="checkbox"><input type="checkbox" name="data[Filter][Situationdossierrsa][etatdosrsa][]" value="Z" id="FilterSituationdossierrsaEtatdosrsaZ" /><label for="FilterSituationdossierrsaEtatdosrsaZ">Non défini</label></div>
</div></fieldset><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'FilterSituationdossierrsaEtatdosrsaChoice\', $( \'FilterSituationdossierrsaEtatdosrsaFieldset\' ), false ); } );
//]]>
</script><div class="input checkbox"><input type="hidden" name="data[Filter][Dossier][dernier]" id="FilterDossierDernier_" value="0"/><input type="checkbox" name="data[Filter][Dossier][dernier]"  value="1" id="FilterDossierDernier"/><label for="FilterDossierDernier">Uniquement la dernière demande RSA pour un même allocataire</label></div></fieldset>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}*/
	}
?>