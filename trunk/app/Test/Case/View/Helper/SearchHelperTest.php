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
			$expected = '<script type=\'text/javascript\'>document.observe(\'dom:loaded\', function() { observeDisableFieldsetOnCheckbox( \'DetailcalculdroitrsaNatpfChoice\', $( \'DetailcalculdroitrsaNatpf\' ), false ); });</script><div class="input checkbox"><input type="hidden" name="data[Detailcalculdroitrsa][natpf_choice]" id="DetailcalculdroitrsaNatpfChoice_" value="0"/><input type="checkbox" name="data[Detailcalculdroitrsa][natpf_choice]" value="1" id="DetailcalculdroitrsaNatpfChoice"/><label for="DetailcalculdroitrsaNatpfChoice">Filtrer par nature de prestation (RSA Socle)</label></div><fieldset id="DetailcalculdroitrsaNatpf"><legend>Nature de la prestation</legend><div class="input select"><input type="hidden" name="data[Detailcalculdroitrsa][natpf]" value="" id="DetailcalculdroitrsaNatpf"/> <div class="checkbox"><input type="checkbox" name="data[Detailcalculdroitrsa][natpf][]" checked="checked" value="1" id="DetailcalculdroitrsaNatpf1" /><label for="DetailcalculdroitrsaNatpf1" class="selected">One</label></div> </div></fieldset>';
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
			$expected = '<fieldset><legend>Recherche par Adresse</legend><div class="input text required"><label for="AdresseLocaadr">Commune de l\'allocataire </label><input name="data[Adresse][locaadr]" type="text" id="AdresseLocaadr"/></div><div class="input select required"><label for="AdresseNumcomptt">Numéro de commune au sens INSEE</label><select name="data[Adresse][numcomptt]" id="AdresseNumcomptt"> <option value=""></option> <option value="1">One</option> </select></div></fieldset>';
			$expected = preg_replace( '/[[:space:]]+/m', ' ', $expected );
			$result = preg_replace( '/[[:space:]]+/m', ' ', $result );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			Configure::write( 'CG.cantons', true );
			$result = $this->Search->blocAdresse( array( '1' => 'One' ), array( '2' => 'Two' ) );
			$expected = '<fieldset><legend>Recherche par Adresse</legend><div class="input text required"><label for="AdresseLocaadr">Commune de l\'allocataire </label><input name="data[Adresse][locaadr]" type="text" id="AdresseLocaadr"/></div><div class="input select required"><label for="AdresseNumcomptt">Numéro de commune au sens INSEE</label><select name="data[Adresse][numcomptt]" id="AdresseNumcomptt"> <option value=""></option> <option value="1">One</option> </select></div><div class="input select required"><label for="CantonCanton">Canton</label><select name="data[Canton][canton]" id="CantonCanton"> <option value=""></option> <option value="2">Two</option> </select></div></fieldset>';
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
			// FIXME: années, langue, ... changent suivant l'environnement
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
<option value="2012">2012</option>
<option value="2011">2011</option>
<option value="2010">2010</option>
<option value="2009">2009</option>
<option value="2008">2008</option>
<option value="2007">2007</option>
<option value="2006">2006</option>
<option value="2005">2005</option>
<option value="2004">2004</option>
<option value="2003">2003</option>
<option value="2002">2002</option>
<option value="2001">2001</option>
<option value="2000">2000</option>
<option value="1999">1999</option>
<option value="1998">1998</option>
<option value="1997">1997</option>
<option value="1996">1996</option>
<option value="1995">1995</option>
<option value="1994">1994</option>
<option value="1993">1993</option>
<option value="1992">1992</option>
<option value="1991">1991</option>
<option value="1990">1990</option>
<option value="1989">1989</option>
<option value="1988">1988</option>
<option value="1987">1987</option>
<option value="1986">1986</option>
<option value="1985">1985</option>
<option value="1984">1984</option>
<option value="1983">1983</option>
<option value="1982">1982</option>
<option value="1981">1981</option>
<option value="1980">1980</option>
<option value="1979">1979</option>
<option value="1978">1978</option>
<option value="1977">1977</option>
<option value="1976">1976</option>
<option value="1975">1975</option>
<option value="1974">1974</option>
<option value="1973">1973</option>
<option value="1972">1972</option>
<option value="1971">1971</option>
<option value="1970">1970</option>
<option value="1969">1969</option>
<option value="1968">1968</option>
<option value="1967">1967</option>
<option value="1966">1966</option>
<option value="1965">1965</option>
<option value="1964">1964</option>
<option value="1963">1963</option>
<option value="1962">1962</option>
<option value="1961">1961</option>
<option value="1960">1960</option>
<option value="1959">1959</option>
<option value="1958">1958</option>
<option value="1957">1957</option>
<option value="1956">1956</option>
<option value="1955">1955</option>
<option value="1954">1954</option>
<option value="1953">1953</option>
<option value="1952">1952</option>
<option value="1951">1951</option>
<option value="1950">1950</option>
<option value="1949">1949</option>
<option value="1948">1948</option>
<option value="1947">1947</option>
<option value="1946">1946</option>
<option value="1945">1945</option>
<option value="1944">1944</option>
<option value="1943">1943</option>
<option value="1942">1942</option>
<option value="1941">1941</option>
<option value="1940">1940</option>
<option value="1939">1939</option>
<option value="1938">1938</option>
<option value="1937">1937</option>
<option value="1936">1936</option>
<option value="1935">1935</option>
<option value="1934">1934</option>
<option value="1933">1933</option>
<option value="1932">1932</option>
<option value="1931">1931</option>
<option value="1930">1930</option>
<option value="1929">1929</option>
<option value="1928">1928</option>
<option value="1927">1927</option>
<option value="1926">1926</option>
<option value="1925">1925</option>
<option value="1924">1924</option>
<option value="1923">1923</option>
<option value="1922">1922</option>
<option value="1921">1921</option>
<option value="1920">1920</option>
<option value="1919">1919</option>
<option value="1918">1918</option>
<option value="1917">1917</option>
<option value="1916">1916</option>
<option value="1915">1915</option>
<option value="1914">1914</option>
<option value="1913">1913</option>
<option value="1912">1912</option>
<option value="1911">1911</option>
<option value="1910">1910</option>
<option value="1909">1909</option>
<option value="1908">1908</option>
<option value="1907">1907</option>
<option value="1906">1906</option>
<option value="1905">1905</option>
<option value="1904">1904</option>
<option value="1903">1903</option>
<option value="1902">1902</option>
<option value="1901">1901</option>
<option value="1900">1900</option>
<option value="1899">1899</option>
<option value="1898">1898</option>
<option value="1897">1897</option>
<option value="1896">1896</option>
<option value="1895">1895</option>
<option value="1894">1894</option>
<option value="1893">1893</option>
<option value="1892">1892</option>
</select></div><div class="input text required"><label for="PersonneNom">Nom</label>
<input name="data[Personne][nom]" maxlength="50" type="text" id="PersonneNom"/></div><div class="input text"><label for="PersonneNomnai">Nom de jeune fille</label><input name="data[Personne][nomnai]" maxlength="50" type="text" id="PersonneNomnai"/></div><div class="input text required"><label for="PersonnePrenom">Prénom</label><input name="data[Personne][prenom]" maxlength="50" type="text" id="PersonnePrenom"/></div><div class="input text"><label for="PersonneNir">NIR</label><input name="data[Personne][nir]" maxlength="15" type="text" id="PersonneNir"/></div><div class="input select"><label for="PersonneTrancheage">Tranche d\'âge</label><select name="data[Personne][trancheage]" id="PersonneTrancheage">
<option value=""></option>
<option value="1">One</option> </select></div></fieldset>';
			$result = $this->_normalizeHtml( $result );
			$expected = $this->_normalizeHtml( $expected );
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>