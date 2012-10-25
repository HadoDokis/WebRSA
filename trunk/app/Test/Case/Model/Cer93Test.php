<?php
	class Cer93Test extends CakeTestCase
	{
		/**
		 * Fixtures utilisées dans ce test unitaire.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Apre',
			'app.Contratinsertion',
			'app.Cer93',
			'app.Compofoyercer93',
			'app.Diplomecer93',
			'app.Dossier',
			'app.Dsp',
			'app.DspRev',
			'app.Expprocer93',
			'app.Foyer',
			'app.Historiqueetatpe',
			'app.Informationpe',
			'app.Pdf',
			'app.Personne',
			'app.Prestation',
			'app.Structurereferente',
			'app.User',
		);

		/**
		 * Méthode exécutée avant chaque test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$this->Cer93 = ClassRegistry::init( 'Cer93' );
		}

		/**
		 * Méthode exécutée après chaque test.
		 *
		 * @return void
		 */
		public function tearDown() {
			unset( $this->Cer93 );
		}

		/**
		 * Test de la méthode Cer93::prepareFormData().
		 *
		 * @group medium
		 * @return void
		 */
		public function testPrepareFormDataSansCerPrecedent() {
			$formData = $this->Cer93->prepareFormData( 1, null, 1  );

			$result = $formData['Cer93'];
			$expected = array (
				'matricule' => '123456700000000',
				'numdemrsa' => '66666666693',
				'rolepers' => 'DEM',
				'dtdemrsa' => '2009-09-01',
				'identifiantpe' => NULL,
				'qual' => 'MR',
				'nom' => 'BUFFIN',
				'nomnai' => 'BUFFIN',
				'prenom' => 'CHRISTIAN',
				'dtnai' => '1979-01-24',
				'adresse' => NULL,
				'codepos' => NULL,
				'locaadr' => NULL,
				'sitfam' => 'CEL',
				'natlog' => NULL,
				'inscritpe' => NULL,
				'nivetu' => NULL,
				'user_id' => 1,
				'nomutilisateur' => NULL,
				'structureutilisateur' => NULL,
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );

			$result = $formData['Compofoyercer93'];
			$expected = array(
				array(
					'qual' => 'MR',
					'nom' => 'BUFFIN',
					'prenom' => 'CHRISTIAN',
					'dtnai' => '1979-01-24',
					'rolepers' => 'DEM',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}


		/**
		 * Test de la méthode Cer93::saveFormulaire().
		 *
		 * @group medium
		 * @return void
		 */
		public function testSaveFormulaire() {
			$data = array(
				'Contratinsertion' => array(
					'id' => '',
					'personne_id' => '1',
					'rg_ci' => '1',
					'structurereferente_id' => '1',
					'referent_id' => '1_1',
					'dd_ci' => array(
						'day' => '01',
						'month' => '11',
						'year' => '2012',
					),
					'df_ci' => array(
						'day' => '28',
						'month' => '02',
						'year' => '2013',
					),
					'date_saisi_ci' => array(
						'day' => '24',
						'month' => '10',
						'year' => '2012',
					),
					'haspiecejointe' => '0',
				),
				'Cer93' => array(
					'id' => '',
					'contratinsertion_id' => '',
					'rolepers' => 'DEM',
					'numdemrsa' => '66666666693',
					'identifiantpe' => null,
					'user_id' => '1',
					'nomutilisateur' => 'DUPONT Robert',
					'structureutilisateur' => '« Projet de Ville RSA d\'Aubervilliers»',
					'matricule' => '123456700000000',
					'dtdemrsa' => '2009-06-01',
					'qual' => 'MR',
					'nom' => 'BUFFIN',
					'nomnai' => 'BUFFIN',
					'prenom' => 'CHRISTIAN',
					'dtnai' => '1979-01-24',
					'adresse' => '66 AVENUE DE LA REPUBLIQUE',
					'codepos' => '93300',
					'locaadr' => 'AUBERVILLIERS',
					'sitfam' => 'CEL',
					'natlog' => '',
					'incoherencesetatcivil' => 'Incohérence',
					'inscritpe' => '1',
					'cmu' => 'oui',
					'cmuc' => 'oui',
					'nivetu' => '1205',
					'autresexps' => 'Autre exp.',
					'isemploitrouv' => 'O',
					'secteuracti_id' => '1',
					'metierexerce_id' => '2',
					'dureehebdo' => '35',
					'naturecontrat_id' => '1',
					'dureecdd' => 'DT2',
					'bilancerpcd' => 'Bilan cer pcd.',
					'duree' => '9',
					'pointparcours' => 'aladate',
					'datepointparcours' => array(
						'day' => '01',
						'month' => '02',
						'year' => '2013',
					),
					'pourlecomptede' => 'AAAPDVAUBERVILLIERS',
					'observpro' => 'Observations',
				),
				'Personne' => array(
					'sexe' => '1',
				),
				'Compofoyercer93' => array(
					array(
						'id' => '',
						'cer93_id' => '',
						'qual' => 'MR',
						'nom' => 'BUFFIN',
						'prenom' => 'CHRISTIAN',
						'dtnai' => '1979-01-24',
						'rolepers' => 'DEM',
					),
				),
				'Diplomecer93' => array(
					array(
						'id' => '',
						'cer93_id' => '',
						'name' => 'Diplôme d\'informatique',
						'annee' => '2000',
					),
				),
				'Expprocer93' => array(
					array(
						'id' => '',
						'cer93_id' => '',
						'metierexerce_id' => '1',
						'secteuracti_id' => '1',
						'anneedeb' => '2010',
						'duree' => '3 mois',
					),
				),
			);

			$result = $this->Cer93->saveFormulaire( $data );
			$expected = true;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>