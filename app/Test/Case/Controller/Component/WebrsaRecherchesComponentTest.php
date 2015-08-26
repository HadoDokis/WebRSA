<?php
	/**
	 * Code source de la classe WebrsaRecherchesComponentTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'Component', 'Controller' );
	App::uses( 'WebrsaRecherchesComponent', 'Controller/Component' );

	class WebrsaRecherchesOrientsstructsComponent extends WebrsaRecherchesComponent
	{
	}

	/**
	 * WebrsaRecherchesOrientsstructsTestsController class
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebrsaRecherchesOrientsstructsTestsController extends AppController
	{
		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'Orientsstructs';

		/**
		 * uses property
		 *
		 * @var mixed null
		 */
		public $uses = array( 'Orientstruct', 'User' );

		/**
		 * components property
		 *
		 * @var array
		 */
		public $components = array(
			'Jetons2', // ??
			'WebrsaRecherchesOrientsstructs'
		);
	}

	/**
	 * La classe WebrsaRecherchesComponentTest ...
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebrsaRecherchesComponentTest extends CakeTestCase
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
			'app.Detaildroitrsa',
			'app.Dossier',
			'app.Foyer',
			'app.Historiqueetatpe',
			'app.Informationpe',
			'app.Jeton',
			'app.Modecontact',
			'app.Orientstruct',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Referent',
			'app.Situationdossierrsa',
			'app.Structurereferente',
			'app.StructurereferenteZonegeographique',
			'app.Typeorient',
			'app.User',
			'app.Zonegeographique',
		);

		/**
		 * Controller property
		 *
		 * @var WebrsaRecherchesComponent
		 */
		public $Controller;

		/**
		 * Liste des clés d'options disponibles.
		 *
		 * @var array
		 */
		public $optionKeys = array(
			'Adresse.numcom',
			'Adresse.pays',
			'Adresse.typeres',
			'Adressefoyer.rgadr',
			'Adressefoyer.typeadr',
			'Calculdroitrsa.toppersdrodevorsa',
			'Canton.canton',
			'Detailcalculdroitrsa.natpf',
			'Detaildroitrsa.oridemrsa',
			'Detaildroitrsa.topfoydrodevorsa',
			'Detaildroitrsa.topsansdomfixe',
			'Dossier.anciennete_dispositif',
			'Dossier.fonorg',
			'Dossier.fonorgcedmut',
			'Dossier.fonorgprenmut',
			'Dossier.numorg',
			'Dossier.statudemrsa',
			'Dossier.typeparte',
			'Foyer.haspiecejointe',
			'Foyer.sitfam',
			'Foyer.typeocclog',
			'Orientstruct.etatorient',
			'Orientstruct.haspiecejointe',
			'Orientstruct.origine',
			'Orientstruct.statut_orient',
			'Orientstruct.statutrelance',
			'Orientstruct.typenotification',
			'Personne.pieecpres',
			'Personne.qual',
			'Personne.sexe',
			'Personne.typedtnai',
			'PersonneReferent.referent_id',
			'PersonneReferent.structurereferente_id',
			'Prestation.rolepers',
			'Referentparcours.qual',
			'Sitecov58.id',
			'Situationdossierrsa.etatdosrsa',
			'Situationdossierrsa.moticlorsa',
			'Structurereferenteparcours.type_voie',
		);

		/**
		 * Préparation du test.
		 */
		public function setUpUrl( array $url ) {
			$Request = new CakeRequest( "{$url['controller']}/{$url['action']}", false );
			$Request->addParams( $url );

			$this->Controller = new WebrsaRecherchesOrientsstructsTestsController( $Request );
			// TODO: constructClasses ?
			$this->Controller->Components->init( $this->Controller );

			$this->Controller->Jetons2->initialize( $this->Controller );
			$this->Controller->WebrsaRecherchesOrientsstructs->initialize( $this->Controller );
		}

		/**
		 * Test de la méthode WebrsaRecherchesComponent::params()
		 */
		public function testParams() {
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'search' ) );

			$result = $this->Controller->WebrsaRecherchesOrientsstructs->params();
			$expected = array(
				'modelName' => 'Orientstruct',
				'modelRechercheName' => 'WebrsaRechercheOrientstruct',
				'searchKey' => 'Search',
				'searchKeyPrefix' => 'ConfigurableQuery',
				'configurableQueryFieldsKey' => 'Orientsstructs.search',
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaRecherchesComponent::options()
		 */
		public function testOptions() {
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'search' ) );

			$result = array();
			foreach( $this->Controller->WebrsaRecherchesOrientsstructs->options() as $modelName => $params ) {
				foreach( array_keys( $params ) as $fieldName ) {
					$result[] = "{$modelName}.{$fieldName}";
				}
			}
			sort( $result );
			$expected = $this->optionKeys;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}


		/**
		 * Test de la méthode WebrsaRecherchesComponent::search() sans que le
		 * formulaire ait été envoyé.
		 *
		 * @todo: tester filtresdefault
		 */
		public function testSearch() {
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'search' ) );

			$this->Controller->WebrsaRecherchesOrientsstructs->search();
			$result = $this->Controller->viewVars;
			$this->assertTrue( isset( $this->Controller->viewVars['options'] ) );
		}

		/**
		 * Test de la méthode WebrsaRecherchesComponent::search() après que le
		 * formulaire ait été envoyé, sans aucune configuration.
		 */
		public function testSearchFormSentNoConfiguration() {
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'search' ) );

			$this->Controller->request->data = array( 'Search' => array( 'Personne' => array( 'nom' => 'BUFFIN' ) ) );
			$this->Controller->WebrsaRecherchesOrientsstructs->search();
			$this->assertTrue( isset( $this->Controller->viewVars['results'] ) );

			$expected = array(
				array(
					'Orientstruct' => array(
						'id' => 1,
						'personne_id' => 1,
						'date_propo' => null
					),
					'Dossier' => array(
						'locked' => false
					)
				)
			);
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaRecherchesComponent::search() après que le
		 * formulaire ait été envoyé, avec de la configuration.
		 */
		public function testSearchFormSentWithConfiguration() {
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'search' ) );

			Configure::write(
				'ConfigurableQueryOrientsstructs',
				array(
					'search' => array(
						'fields' => array(
							'Personne.nom'
						),
						'innerTable' => array(
							'Prestation.rolepers'
						),
						'order' => array()
					)
				)
			);
			$this->Controller->request->data = array( 'Search' => array( 'Personne' => array( 'nom' => 'BUFFIN' ) ) );
			$this->Controller->WebrsaRecherchesOrientsstructs->search();
			$this->assertTrue( isset( $this->Controller->viewVars['results'] ) );

			$expected = array(
				array(
					'Personne' => array(
						'nom' => 'BUFFIN',
					),
					'Prestation' => array(
						'rolepers' => 'DEM',
					),
					'Orientstruct' => array(
						'id' => 1,
						'personne_id' => 1,
						'date_propo' => NULL,
					),
					'Dossier' => array(
						'locked' => false,
					),
				)
			);
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaRecherchesComponent::exportcsv()avec de la configuration.
		 */
		public function testExportcsvWithConfiguration() {
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'exportcsv' ) );

			Configure::write(
				'ConfigurableQueryOrientsstructs',
				array(
					'exportcsv' => array(
						'Dossier.numdemrsa',
						'Personne.nom',
						'Prestation.rolepers'
					)
				)
			);
			$this->Controller->request->params['named'] = Hash::flatten( array( 'Search' => array( 'Personne' => array( 'nom' => 'BUFFIN' ) ) ), '__' );
			$this->Controller->WebrsaRecherchesOrientsstructs->exportcsv();

			$this->assertTrue( isset( $this->Controller->viewVars['results'] ) );
			$this->assertTrue( isset( $this->Controller->viewVars['options'] ) );

			$expected = array(
				array(
					'Personne' => array(
						'nom' => 'BUFFIN',
					),
					'Prestation' => array(
						'rolepers' => 'DEM',
					),
					'Dossier' => array(
						'numdemrsa' => '66666666693',
						'locked' => false,
					),
					'Orientstruct' => array(
						'id' => 1,
						'personne_id' => 1,
						'date_propo' => NULL,
					)
				)
			);
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>