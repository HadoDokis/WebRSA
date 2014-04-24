<?php
	/**
	 * Code source de la classe Ficheprescription93Test.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Ficheprescription93', 'Model' );

	/**
	 * La classe Ficheprescription93Test réalise les tests unitaires de la classe Ficheprescription93.
	 *
	 * @package app.Test.Case.Model
	 */
	class Ficheprescription93Test extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Actionfp93',
			'app.Adresse',
			'app.Adressefoyer',
			'app.Calculdroitrsa',
			'app.Categoriefp93',
			'app.Cer93',
			'app.Contratinsertion',
			'app.Detailcalculdroitrsa',
			'app.Detaildroitrsa',
			'app.Documentbeneffp93',
			'app.Dsp',
			'app.DspRev',
			'app.Dossier',
			'app.Ficheprescription93',
			'app.Filierefp93',
			'app.Foyer',
			'app.Historiqueetatpe',
			'app.Informationpe',
			'app.Instantanedonneesfp93',
			'app.Modtransmfp93',
			'app.Motifnonintegrationfp93',
			'app.Motifnonreceptionfp93',
			'app.Motifnonretenuefp93',
			'app.Motifnonsouhaitfp93',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestatairefp93',
			'app.Prestation',
			'app.Referent',
			'app.Situationdossierrsa',
			'app.Structurereferente',
			'app.Thematiquefp93',
		);

		/**
		 * Le modèle à tester.
		 *
		 * @var Ficheprescription93
		 */
		public $Ficheprescription93 = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			unset( $this->Ficheprescription93 );
			parent::tearDown();
		}

		/**
		 * Test de la méthode Ficheprescription93::search()
		 *
		 * @medium
		 */
		public function testSearch() {
			$result = $this->Ficheprescription93->search();
			$result = Hash::combine( $result, 'joins.{n}.alias', 'joins.{n}.type' );
			$expected = array(
				'Calculdroitrsa' => 'LEFT OUTER',
				'Foyer' => 'INNER',
				'Prestation' => 'INNER',
				'Adressefoyer' => 'INNER',
				'Dossier' => 'INNER',
				'Adresse' => 'INNER',
				'Situationdossierrsa' => 'INNER',
				'Detaildroitrsa' => 'INNER',
				'PersonneReferent' => 'LEFT OUTER',
				'Referentparcours' => 'LEFT OUTER',
				'Structurereferenteparcours' => 'LEFT OUTER',
				'Ficheprescription93' => 'LEFT OUTER',
				'Actionfp93' => 'LEFT OUTER',
				'Referent' => 'LEFT OUTER',
				'Filierefp93' => 'LEFT OUTER',
				'Prestatairefp93' => 'LEFT OUTER',
				'Categoriefp93' => 'LEFT OUTER',
				'Thematiquefp93' => 'LEFT OUTER',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Ficheprescription93::searchConditions()
		 */
		public function testSearchConditions() {
			$query = array(
				'conditions' => array()
			);
			$search = array(
				'Actionfp93' => array(
					'numconvention' => '007'
				),
				'Ficheprescription93' => array(
					'exists' => '1',
					'typethematiquefp93_id' => 'pdi',
					'statut' => '03transmise_partenaire',
					'has_date_bilan_final' => '1',
				)
			);
			$result = $this->Ficheprescription93->searchConditions( $query, $search );
			$expected = array(
				'conditions' => array(
					'Ficheprescription93.id IS NOT NULL',
					'Thematiquefp93.type' => 'pdi',
					'UPPER( Actionfp93.numconvention ) LIKE' => '007%',
					'Ficheprescription93.statut' => '03transmise_partenaire',
					'Ficheprescription93.date_bilan_final IS NOT NULL',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Ficheprescription93::options()
		 *
		 * @medium
		 */
		public function testOptions() {
			$options = $this->Ficheprescription93->options( array( 'find' => true, 'autre' => true ) );
			$result = array();
			foreach( array_keys( $options ) as $modelName ) {
				foreach( array_keys( $options[$modelName] ) as $fieldName ) {
					$result[] = "{$modelName}.{$fieldName}";
				}
			}

			$expected = array(
				'Adresse.pays',
				'Adresse.typeres',
				'Adresse.typevoie',
				'Adressefoyer.rgadr',
				'Adressefoyer.typeadr',
				'Calculdroitrsa.toppersdrodevorsa',
				'Detailcalculdroitrsa.natpf',
				'Detaildroitrsa.oridemrsa',
				'Detaildroitrsa.topfoydrodevorsa',
				'Detaildroitrsa.topsansdomfixe',
				'Dossier.fonorgcedmut',
				'Dossier.fonorgprenmut',
				'Dossier.numorg',
				'Dossier.statudemrsa',
				'Dossier.typeparte',
				'Foyer.sitfam',
				'Foyer.typeocclog',
				'Personne.pieecpres',
				'Personne.qual',
				'Personne.sexe',
				'Personne.typedtnai',
				'Prestation.rolepers',
				'Referentparcours.qual',
				'Structurereferenteparcours.type_voie',
				'Situationdossierrsa.etatdosrsa',
				'Situationdossierrsa.moticlorsa',
				'Ficheprescription93.statut',
				'Ficheprescription93.benef_retour_presente',
				'Ficheprescription93.personne_recue',
				'Ficheprescription93.personne_retenue',
				'Ficheprescription93.personne_souhaite_integrer',
				'Ficheprescription93.personne_a_integre',
				'Ficheprescription93.exists',
				'Ficheprescription93.typethematiquefp93_id',
				'Ficheprescription93.motifnonreceptionfp93_id',
				'Ficheprescription93.motifnonretenuefp93_id',
				'Ficheprescription93.motifnonsouhaitfp93_id',
				'Ficheprescription93.motifnonintegrationfp93_id',
				'Ficheprescription93.documentbeneffp93_id',
				'Actionfp93.actif',
				'Thematiquefp93.type',
				'Instantanedonneesfp93.benef_inscritpe',
				'Instantanedonneesfp93.benef_natpf_socle',
				'Instantanedonneesfp93.benef_natpf_majore',
				'Instantanedonneesfp93.benef_natpf_activite',
				'Instantanedonneesfp93.benef_nivetu',
				'Instantanedonneesfp93.benef_dip_ce',
				'Instantanedonneesfp93.benef_etatdosrsa',
				'Instantanedonneesfp93.benef_toppersdrodevorsa',
				'Instantanedonneesfp93.benef_positioncer',
				'Instantanedonneesfp93.benef_natpf',
				'Modtransmfp93.Modtransmfp93',
				'Documentbeneffp93.Documentbeneffp93',
				'Autre.Ficheprescription93',
			);

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Ficheprescription93::prepareFormDataAddEdit()
		 *
		 * @medium
		 */
		public function testPrepareFormDataAddEdit() {
			$result = $this->Ficheprescription93->prepareFormDataAddEdit( 1 );
			$expected = array(
				'Instantanedonneesfp93' => array(
					'benef_qual' => 'MR',
					'benef_nom' => 'BUFFIN',
					'benef_prenom' => 'CHRISTIAN',
					'benef_dtnai' => '1979-01-24',
					'benef_tel_fixe' => NULL,
					'benef_tel_port' => NULL,
					'benef_email' => NULL,
					'benef_numvoie' => '66',
					'benef_typevoie' => 'AV',
					'benef_nomvoie' => 'DE LA REPUBLIQUE',
					'benef_complideadr' => NULL,
					'benef_compladr' => NULL,
					'benef_numcomptt' => '93001',
					'benef_numcomrat' => '93001',
					'benef_codepos' => '93300',
					'benef_locaadr' => 'AUBERVILLIERS',
					'benef_matricule' => '123456700000000',
					'benef_natpf_activite' => '0',
					'benef_natpf_majore' => '0',
					'benef_natpf_socle' => '0',
					'benef_etatdosrsa' => '2',
					'benef_toppersdrodevorsa' => '1',
					'benef_dd_ci' => '2011-03-01',
					'benef_df_ci' => '2011-05-31',
					'benef_positioncer' => 'validationpdv',
					'benef_identifiantpe' => '0609065370Y',
					'benef_inscritpe' => '1',
					'benef_nivetu' => '1202',
					'benef_natpf' => 'NC',
					'benef_adresse' => '66 Avenue DE LA REPUBLIQUE',
				),
				'Ficheprescription93' => array(
					'personne_id' => 1,
					'statut' => '01renseignee',
				),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>
