<?php
	/**
	 * SearchHelperTest file
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'View', 'View' );
	App::uses( 'SearchHelper', 'View/Helper' );

	/**
	 * SearchHelperTest class
	 *
	 * @package app.Test.Case.View.Helper
	 */
	class SearchHelperTest extends CakeTestCase
	{
		/**
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Detailcalculdroitrsa',
			'app.Dossierpcg66',
			'app.Personne',
		);

		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			Configure::write( 'CG.cantons', false );
			$controller = null;
			$this->View = new View( $controller );
			$this->Search = new SearchHelper( $this->View );
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			unset( $this->View, $this->Search );
			parent::tearDown();
		}

		/**
		 * testEtatdosrsa method
		 *
		 * @return void
		 */
		public function testEtatdosrsa() {
			$result = $this->Search->etatdosrsa( array( '1' => 'One' ) );
			$expected = '<script type=\'text/javascript\'>document.observe(\'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'SituationdossierrsaEtatdosrsaChoice\', $( \'SituationdossierrsaEtatdosrsa\' ), false ); });</script><div class="input checkbox"><input type="hidden" name="data[Situationdossierrsa][etatdosrsa_choice]" id="SituationdossierrsaEtatdosrsaChoice_" value="0"/><input type="checkbox" name="data[Situationdossierrsa][etatdosrsa_choice]" value="1" id="SituationdossierrsaEtatdosrsaChoice"/><label for="SituationdossierrsaEtatdosrsaChoice">Filtrer par état du dossier</label></div><fieldset id="SituationdossierrsaEtatdosrsa"><legend>État du dossier RSA</legend><div class="input select required"><input type="hidden" name="data[Situationdossierrsa][etatdosrsa]" value="" id="SituationdossierrsaEtatdosrsa"/> <div class="checkbox"><input type="checkbox" name="data[Situationdossierrsa][etatdosrsa][]" checked="checked" value="1" id="SituationdossierrsaEtatdosrsa1" /><label for="SituationdossierrsaEtatdosrsa1" class="selected">One</label></div> </div></fieldset>';
			$expected = preg_replace( '/[[:space:]]+/m', ' ', $expected );
			$result = preg_replace( '/[[:space:]]+/m', ' ', $result );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * testNatpf method
		 *
		 * @return void
		 */
		public function testNatpf() {
			$result = $this->Search->natpf( array( '1' => 'One' ) );
			$expected = '<script type=\'text/javascript\'>document.observe(\'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'DetailcalculdroitrsaNatpfChoice\', $( \'DetailcalculdroitrsaNatpf\' ), false ); });</script><div class="input checkbox"><input type="hidden" name="data[Detailcalculdroitrsa][natpf_choice]" id="DetailcalculdroitrsaNatpfChoice_" value="0"/><input type="checkbox" name="data[Detailcalculdroitrsa][natpf_choice]" value="1" id="DetailcalculdroitrsaNatpfChoice"/><label for="DetailcalculdroitrsaNatpfChoice">Filtrer par nature de prestation</label></div><fieldset id="DetailcalculdroitrsaNatpf"><legend>Nature de la prestation</legend><div class="input select"><input type="hidden" name="data[Detailcalculdroitrsa][natpf]" value="" id="DetailcalculdroitrsaNatpf"/> <div class="checkbox"><input type="checkbox" name="data[Detailcalculdroitrsa][natpf][]" checked="checked" value="1" id="DetailcalculdroitrsaNatpf1" /><label for="DetailcalculdroitrsaNatpf1" class="selected">One</label></div> </div></fieldset>';
			$expected = preg_replace( '/[[:space:]]+/m', ' ', $expected );
			$result = preg_replace( '/[[:space:]]+/m', ' ', $result );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * testEtatDossierPCG66 method
		 *
		 * @return void
		 */
		public function testEtatDossierPCG66() {
			$result = $this->Search->etatDossierPCG66( array( '1' => 'One' ) );
			$expected = '<script type=\'text/javascript\'>document.observe(\'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'Dossierpcg66EtatdossierpcgChoice\', $( \'Dossierpcg66Etatdossierpcg\' ), false ); });</script><div class="input checkbox"><input type="hidden" name="data[Dossierpcg66][etatdossierpcg_choice]" id="Dossierpcg66EtatdossierpcgChoice_" value="0"/><input type="checkbox" name="data[Dossierpcg66][etatdossierpcg_choice]" value="1" id="Dossierpcg66EtatdossierpcgChoice"/><label for="Dossierpcg66EtatdossierpcgChoice">Filtrer par état du dossier PCG</label></div><fieldset id="Dossierpcg66Etatdossierpcg"><legend>État du dossier PCG</legend><div class="input select"><input type="hidden" name="data[Dossierpcg66][etatdossierpcg]" value="" id="Dossierpcg66Etatdossierpcg"/> <div class="checkbox"><input type="checkbox" name="data[Dossierpcg66][etatdossierpcg][]" value="1" id="Dossierpcg66Etatdossierpcg1" /><label for="Dossierpcg66Etatdossierpcg1">One</label></div> </div></fieldset>';
			$expected = preg_replace( '/[[:space:]]+/m', ' ', $expected );
			$result = preg_replace( '/[[:space:]]+/m', ' ', $result );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * testEtatDossierPCG66 method
		 *
		 * @return void
		 */
		public function testBlocAdresse() {
			$result = $this->Search->blocAdresse( array( '1' => 'One' ), array( '2' => 'Two' ) );
			$expected = '<fieldset><legend>Recherche par Adresse</legend><div class="input text required"><label for="AdresseNomvoie">Nom de voie de l\'allocataire </label><input name="data[Adresse][nomvoie]" type="text" id="AdresseNomvoie"/></div><div class="input text required"><label for="AdresseNomcom">Commune de l\'allocataire </label><input name="data[Adresse][nomcom]" type="text" id="AdresseNomcom"/></div><div class="input select"><label for="AdresseNumcom">Numéro de commune au sens INSEE</label><select name="data[Adresse][numcom]" id="AdresseNumcom"> <option value=""></option> <option value="1">One</option> </select></div></fieldset>';
			$expected = preg_replace( '/[[:space:]]+/m', ' ', $expected );
			$result = preg_replace( '/[[:space:]]+/m', ' ', $result );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			Configure::write( 'CG.cantons', true );
			$result = $this->Search->blocAdresse( array( '1' => 'One' ), array( '2' => 'Two' ) );
			$expected = '<fieldset><legend>Recherche par Adresse</legend><div class="input text required"><label for="AdresseNomvoie">Nom de voie de l\'allocataire </label><input name="data[Adresse][nomvoie]" type="text" id="AdresseNomvoie"/></div><div class="input text required"><label for="AdresseNomcom">Commune de l\'allocataire </label><input name="data[Adresse][nomcom]" type="text" id="AdresseNomcom"/></div><div class="input select"><label for="AdresseNumcom">Numéro de commune au sens INSEE</label><select name="data[Adresse][numcom]" id="AdresseNumcom"> <option value=""></option> <option value="1">One</option> </select></div><div class="input select required"><label for="CantonCanton">Canton</label><select name="data[Canton][canton]" id="CantonCanton"> <option value=""></option> <option value="2">Two</option> </select></div></fieldset>';
			$expected = preg_replace( '/[[:space:]]+/m', ' ', $expected );
			$result = preg_replace( '/[[:space:]]+/m', ' ', $result );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		protected function _normalizeHtml( $html ) {
			$html = trim( $html );
			$html = preg_replace( '/[[:space:]]+/m', ' ', $html );
			$html = str_replace( '> <', '><', $html );
			return $html;
		}

		/**
		 * testBlocAllocataire method
		 *
		 * @return void
		 */
		public function testBlocAllocataire() {
			$result = $this->Search->blocAllocataire( array( '1' => 'One' ) );
			// TODO: langue, ... changent suivant l'environnement
			$years = array();
			for( $year = date( 'Y' ) ; $year >= date( 'Y' ) - 120 ; $year-- ) {
				$years[] = "<option value=\"{$year}\">{$year}</option>";
			}

			$expected = '
				<fieldset>
					<legend>Recherche par allocataire</legend>
					<div class="input date required">
						<label for="PersonneDtnaiDay">Date de naissance</label>
						<select name="data[Personne][dtnai][day]" id="PersonneDtnaiDay">
							<option value=""></option>
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
</select>-<select name="data[Personne][dtnai][month]" id="PersonneDtnaiMonth">
<option value=""></option>
<option value="01">janvier</option>
<option value="02">février</option>
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
</select>-<select name="data[Personne][dtnai][year]" id="PersonneDtnaiYear">
<option value=""></option>
'.implode( "\n", $years ).'
</select></div><div class="input text required"><label for="PersonneNom">Nom</label>
<input name="data[Personne][nom]" maxlength="50" type="text" id="PersonneNom"/></div><div class="input text"><label for="PersonneNomnai">Nom de naissance</label><input name="data[Personne][nomnai]" maxlength="50" type="text" id="PersonneNomnai"/></div><div class="input text required"><label for="PersonnePrenom">Prénom</label><input name="data[Personne][prenom]" maxlength="50" type="text" id="PersonnePrenom"/></div><div class="input text"><label for="PersonneNir">NIR</label><input name="data[Personne][nir]" maxlength="15" type="text" id="PersonneNir"/></div><div class="input select"><label for="PersonneTrancheage">Tranche d\'âge</label><select name="data[Personne][trancheage]" id="PersonneTrancheage">
<option value=""></option>
<option value="1">One</option> </select></div></fieldset>';
			$result = $this->_normalizeHtml( $result );
			$expected = $this->_normalizeHtml( $expected );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>