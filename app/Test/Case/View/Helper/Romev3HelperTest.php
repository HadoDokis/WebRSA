<?php
	/**
	 * Code source de la classe Romev3HelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'Romev3Helper', 'View/Helper' );

	/**
	 * La classe Romev3HelperTest ...
	 *
	 * @package app.Test.Case.View.Helper
	 */
	class Romev3HelperTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisées pour les tests.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Dsp'
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
		 * @var Romev3
		 */
		public $Romev3 = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$Request = new CakeRequest();
			$this->Controller = new Controller( $Request );
			$this->View = new View( $this->Controller );
			$this->Romev3 = new Romev3Helper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Controller, $this->View, $this->Romev3 );
		}

		/**
		 * Test de la méthode Romev3Helper::fieldset()
		 *
		 * @medium
		 */
		public function testFieldset() {
			$this->Controller->request->addParams(
				array(
					'controller' => 'dsps',
					'action' => 'add',
					'pass' => array(),
					'named' => array()
				)
			);

			$params = array(
				'prefix' => 'deract',
				'options' => array(
					'Dsp' => array(
						'deractfamilleromev3_id' => array(
							'1' => 'A - AGRICULTURE ET PÊCHE, ESPACES NATURELS ET ESPACES VERTS, SOINS AUX ANIMAUX'
						),
						'deractdomaineromev3_id' => array(
							'1_1' => 'A11 - Engins agricoles et forestiers'
						),
						'deractmetierromev3_id' => array(
							'1_1' => 'A1101 - Conduite d\'engins d\'exploitation agricole et forestière'
						),
						'deractappellationromev3_id' => array(
							'1_1' => 'Conducteur / Conductrice d\'engins de débardage'
						)
					)
				)
			);
			$result = $this->Romev3->fieldset( $params );
			$expected = '<fieldset><legend>Dernière activité</legend><div class="input select"><label for="DspDeractfamilleromev3Id">Code famille de la dernière activité</label><select name="data[Dsp][deractfamilleromev3_id]" id="DspDeractfamilleromev3Id">
<option value=""></option>
<option value="1">A - AGRICULTURE ET PÊCHE, ESPACES NATURELS ET ESPACES VERTS, SOINS AUX ANIMAUX</option>
</select></div><div class="input select"><label for="DspDeractdomaineromev3Id">Code domaine de la dernière activité</label><select name="data[Dsp][deractdomaineromev3_id]" id="DspDeractdomaineromev3Id">
<option value=""></option>
<option value="1_1">A11 - Engins agricoles et forestiers</option>
</select></div><div class="input select"><label for="DspDeractmetierromev3Id">Code métier de la dernière activité</label><select name="data[Dsp][deractmetierromev3_id]" id="DspDeractmetierromev3Id">
<option value=""></option>
<option value="1_1">A1101 - Conduite d&#039;engins d&#039;exploitation agricole et forestière</option>
</select></div><div class="input select"><label for="DspDeractappellationromev3Id">Appellation de la dernière activité</label><select name="data[Dsp][deractappellationromev3_id]" id="DspDeractappellationromev3Id">
<option value=""></option>
<option value="1_1">Conducteur / Conductrice d&#039;engins de débardage</option>
</select></div><script type="text/javascript">
//<![CDATA[
document.observe( \'dom:loaded\', function() { dependantSelect( \'DspDeractdomaineromev3Id\', \'DspDeractfamilleromev3Id\' );
dependantSelect( \'DspDeractmetierromev3Id\', \'DspDeractdomaineromev3Id\' );
dependantSelect( \'DspDeractappellationromev3Id\', \'DspDeractmetierromev3Id\' );
 } );
//]]>
</script></fieldset>';
			$this->assertEquals( $result, $expected );
		}
	}
?>