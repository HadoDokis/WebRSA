<?php
	/**
	 * Code source de la classe WebrsaAbstractRecherchesComponentTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'Component', 'Controller' );
	App::uses( 'WebrsaAbstractMoteursComponent', 'Controller/Component' );
	App::uses( 'WebrsaAbstractRecherchesComponent', 'Controller/Component' );

	class WebrsaRecherchesOrientsstructsComponent extends WebrsaAbstractRecherchesComponent
	{
		public $query = null;

		/**
		 * Méthode publique permettant d'accéder à la méthode protégée.
		 *
		 * @param array $params
		 * @return array
		 */
		public function params( array $params = array() ) {
			return $this->_params($params);
		}

		/**
		 * Surcharge pour récupérer le résultat dans l'attribut query.
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		protected function _getQueryConditions( array $query, array $params = array()  ) {
			$this->query = parent::_getQueryConditions( $query, $params );
			return $this->query;
		}
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
	 * La classe WebrsaAbstractRecherchesComponentTest ...
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebrsaAbstractRecherchesComponentTest extends CakeTestCase
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
			'app.Detailcalculdroitrsa',
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
		 * @var WebrsaAbstractRecherchesComponent
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
		public function setUp() {
			Configure::write( 'Cg.departement', 66 );
			parent::setUp();
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Controller );
		}

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
		 * Test de la méthode WebrsaAbstractRecherchesComponent::params()
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
				'auto' => false,
				'filtresdefautClass' => 'Search.Filtresdefaut'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractRecherchesComponent::options()
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
		 * Test de la méthode WebrsaAbstractRecherchesComponent::search() sans que le
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
		 * Test de la méthode WebrsaAbstractRecherchesComponent::search() après que le
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
		 * Test de la méthode WebrsaAbstractRecherchesComponent::search() après que le
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
		 * Test de la méthode WebrsaAbstractRecherchesComponent::search() après que le
		 * formulaire ait été envoyé, la configuration des clés 'accepted',
		 * 'skip', 'restrict' et 'force'
		 */
		public function testSearchFormSentWithAdvancedConfiguration() {
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
						'order' => array(),
						'accepted' => array(
							'Detailcalculdroitrsa.natpf' => array( 'RSD', 'RSI' )
						),
						'skip' => array(
							'Personne.nom'
						),
						'restrict' => array(
							'Detailcalculdroitrsa.natpf_choice' => '1',
							'Detailcalculdroitrsa.natpf' => array( 'RSD', 'RSI' )
						),
						'force' => array(
							'Personne.prenom' => 'Bar'
						)
					)
				)
			);
			$this->setUpUrl( array( 'controller' => 'orientsstructs', 'action' => 'search' ) );

			$search = array(
				'Search' => array(
					'Personne' => array(
						'nom' => 'Foo'
					),
					'Detailcalculdroitrsa' => array(
						'natpf_choice' => '0'
					)
				)
			);
			$this->Controller->request->data = $search;
			$this->Controller->WebrsaRecherchesOrientsstructs->search();
			$this->assertTrue( isset( $this->Controller->viewVars['results'] ) );

			// 1°) La configuration de 'accepted' a-t'elle été prise en compte ?
			$result = (array)Hash::get( $this->Controller->viewVars, 'options.Detailcalculdroitrsa.natpf' );
			$expected = array(
				'RSD' => 'RSA Socle (Financement sur fonds Conseil général)',
				'RSI' => 'RSA Socle majoré (Financement sur fonds Conseil général)'
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );

			// 2°) Les configurations de 'skip', 'restrict' et 'force' ont-elles été prises en compte ?
			$result = $this->Controller->WebrsaRecherchesOrientsstructs->query['conditions'];
			$expected = array(
				array(
					'OR' => array(
						'Informationpe.id IS NULL',
						'Informationpe.id IN ( SELECT "derniereinformationspe"."i__id" FROM ( SELECT "i"."id" AS "i__id", "h"."date" AS "h__date" FROM "informationspe" AS "i" INNER JOIN "public"."historiqueetatspe" AS "h" ON ("h"."informationpe_id" = "i"."id")  WHERE (((("i"."nir" IS NOT NULL)  AND  ("Personne"."nir" IS NOT NULL)  AND  (TRIM( BOTH \' \' FROM "i"."nir" ) <> \'\')  AND  (TRIM( BOTH \' \' FROM "Personne"."nir" ) <> \'\')  AND  (SUBSTRING( "i"."nir" FROM 1 FOR 13 ) = SUBSTRING( "Personne"."nir" FROM 1 FOR 13 ))  AND  ("i"."dtnai" = "Personne"."dtnai"))) OR ((("i"."nom" IS NOT NULL)  AND  ("Personne"."nom" IS NOT NULL)  AND  ("i"."prenom" IS NOT NULL)  AND  ("Personne"."prenom" IS NOT NULL)  AND  (TRIM( BOTH \' \' FROM "i"."nom" ) <> \'\')  AND  (TRIM( BOTH \' \' FROM "i"."prenom" ) <> \'\')  AND  (TRIM( BOTH \' \' FROM "Personne"."nom" ) <> \'\')  AND  (TRIM( BOTH \' \' FROM "Personne"."prenom" ) <> \'\')  AND  (TRIM( BOTH \' \' FROM "i"."nom" ) = "Personne"."nom")  AND  (TRIM( BOTH \' \' FROM "i"."prenom" ) = "Personne"."prenom")  AND  ("i"."dtnai" = "Personne"."dtnai")))) AND "h"."id" IN ( SELECT "dernierhistoriqueetatspe"."id" AS dernierhistoriqueetatspe__id FROM historiqueetatspe AS dernierhistoriqueetatspe   WHERE "dernierhistoriqueetatspe"."informationpe_id" = "i"."id"   ORDER BY "dernierhistoriqueetatspe"."date" DESC, "dernierhistoriqueetatspe"."id" DESC  LIMIT 1 )    ) AS "derniereinformationspe" ORDER BY "derniereinformationspe"."h__date" DESC LIMIT 1 )'
					)
				),
				'UPPER(Personne.prenom) LIKE \'BAR\'',
				'Detaildroitrsa.id IN (
									SELECT detailscalculsdroitsrsa.detaildroitrsa_id
										FROM detailscalculsdroitsrsa
											INNER JOIN detailsdroitsrsa ON (
												detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id
											)
										WHERE
											detailsdroitsrsa.dossier_id = Dossier.id
											AND detailscalculsdroitsrsa.natpf IN ( \'RSD\', \'RSI\' )
								)',
				array()
			);
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractRecherchesComponent::exportcsv()avec de la configuration.
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

		/**
		 * Test de la méthode WebrsaAbstractRecherchesComponent::exportcsv(), avec
		 * la configuration des clés 'accepted', 'skip', 'restrict' et 'force'.
		 */
		public function testExportcsvWithAdvancedConfiguration() {
			// FIXME: il faudra une clé fields dans exportcsv...
		}
	}
?>