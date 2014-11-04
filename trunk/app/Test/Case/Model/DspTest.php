<?php
	/**
	 * Code source de la classe DspTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Dsp', 'Model' );

	/**
	 * La classe DspTest réalise les tests unitaires de la classe Dsp.
	 *
	 * @package app.Test.Case.Model
	 */
	class DspTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Dsp',
			'app.DspRev',
			'app.Personne',
			'app.Detaildifsoc',
			'app.Detailaccosocfam',
			'app.Detailaccosocindi',
			'app.Detaildifdisp',
			'app.Detailnatmob',
			'app.Detaildiflog',
			'app.Detailmoytrans',
			'app.Detaildifsocpro',
			'app.Detailprojpro',
			'app.Detailfreinform',
			'app.Detailconfort',
			'app.DetaildifsocRev',
			'app.DetailaccosocfamRev',
			'app.DetailaccosocindiRev',
			'app.DetaildifdispRev',
			'app.DetailnatmobRev',
			'app.DetaildiflogRev',
			'app.DetailmoytransRev',
			'app.DetaildifsocproRev',
			'app.DetailprojproRev',
			'app.DetailfreinformRev',
			'app.DetailconfortRev',
			'app.Familleromev3',
			'app.Domaineromev3',
			'app.Metierromev3',
			'app.Appellationromev3',
			'app.Foyer',
			'app.Dossier',
			'app.Adressefoyer',
			'app.Adresse',
			'app.Prestation',
		);

		/**
		 * Le modèle à tester.
		 *
		 * @var Dsp
		 */
		public $Dsp = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Dsp = ClassRegistry::init( 'Dsp' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Dsp );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Dsp::updateDerniereDsp() avec une nouvelle version
		 * des DspRev.
		 *
		 * @medium
		 */
		public function testUpdateDerniereDspNouvelleDspRev() {
			$data = array(
				'Dsp' => array(
					'nivetu' => '1203',
					'natlog' => '0908'
				)
			);
			$result = $this->Dsp->updateDerniereDsp( 1, $data );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// On s'assure qu'il n'y a qu'une création de DspRev
			$this->assertEqual( $this->Dsp->id, false, var_export( $this->Dsp->id, true ) );
			$this->assertEqual( $this->Dsp->DspRev->id, 2, var_export( $this->Dsp->DspRev->id, true ) );
		}

		/**
		 * Test de la méthode Dsp::updateDerniereDsp() avec la création d'une DspRev.
		 *
		 * @medium
		 */
		public function testUpdateDerniereDspCreationDspRev() {
			$data = array(
				'Dsp' => array(
					'nivetu' => '1203',
					'natlog' => '0908'
				)
			);
			$result = $this->Dsp->updateDerniereDsp( 2, $data );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// On s'assure qu'il n'y a qu'une création de DspRev
			$this->assertEqual( $this->Dsp->id, false, var_export( $this->Dsp->id, true ) );
			$this->assertEqual( $this->Dsp->DspRev->id, 2, var_export( $this->Dsp->DspRev->id, true ) );
		}

		/**
		 * Test de la méthode Dsp::updateDerniereDsp() avec la création d'une Dsp.
		 *
		 * @medium
		 */
		public function testUpdateDerniereDspCreationDsp() {
			$data = array(
				'Dsp' => array(
					'nivetu' => '1203',
					'natlog' => '0908'
				)
			);
			$result = $this->Dsp->updateDerniereDsp( 3, $data );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			// On s'assure qu'il n'y a qu'une création de Dsp
			$this->assertEqual( $this->Dsp->id, 3, var_export( $this->Dsp->id, true ) );
			$this->assertEqual( $this->Dsp->DspRev->id, false, var_export( $this->Dsp->DspRev->id, true ) );
		}

		/**
		 * Test de la méthode Dsp::searchQuery().
		 */
		public function testSearchQuery() {
			$result = $this->Dsp->searchQuery();
			$result = Hash::combine( $result, 'joins.{n}.alias', 'joins.{n}.type' );

			$expected = array(
				'Foyer' => 'INNER',
				'Dossier' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'INNER',
				'Adresse' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				'DspRev' => 'LEFT OUTER',
				'Dsp' => 'LEFT OUTER',
				'Deractfamilleromev3' => 'LEFT OUTER',
				'Deractfamilleromev3Rev' => 'LEFT OUTER',
				'Deractdomaineromev3' => 'LEFT OUTER',
				'Deractdomaineromev3Rev' => 'LEFT OUTER',
				'Deractmetierromev3' => 'LEFT OUTER',
				'Deractmetierromev3Rev' => 'LEFT OUTER',
				'Deractappellationromev3' => 'LEFT OUTER',
				'Deractappellationromev3Rev' => 'LEFT OUTER',
				'Deractdomifamilleromev3' => 'LEFT OUTER',
				'Deractdomifamilleromev3Rev' => 'LEFT OUTER',
				'Deractdomidomaineromev3' => 'LEFT OUTER',
				'Deractdomidomaineromev3Rev' => 'LEFT OUTER',
				'Deractdomimetierromev3' => 'LEFT OUTER',
				'Deractdomimetierromev3Rev' => 'LEFT OUTER',
				'Deractdomiappellationromev3' => 'LEFT OUTER',
				'Deractdomiappellationromev3Rev' => 'LEFT OUTER',
				'Actrechfamilleromev3' => 'LEFT OUTER',
				'Actrechfamilleromev3Rev' => 'LEFT OUTER',
				'Actrechdomaineromev3' => 'LEFT OUTER',
				'Actrechdomaineromev3Rev' => 'LEFT OUTER',
				'Actrechmetierromev3' => 'LEFT OUTER',
				'Actrechmetierromev3Rev' => 'LEFT OUTER',
				'Actrechappellationromev3' => 'LEFT OUTER',
				'Actrechappellationromev3Rev' => 'LEFT OUTER'
			);

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
