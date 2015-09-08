<?php
	/**
	 * Code source de la classe WebrsaAbstractCohortesComponentTest.
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
	App::uses( 'WebrsaAbstractCohortesComponent', 'Controller/Component' );
	App::uses( 'WebrsaCohortesDossierspcgs66Component', 'Controller/Component' );
	App::uses( 'AppModel', 'Model' );
	App::uses( 'AbstractWebrsaCohorte', 'Model/Abstractclass' );
	App::uses( 'AbstractWebrsaCohorteDossierpcg66', 'Model/Abstractclass' );
	App::uses( 'WebrsaCohorteDossierpcg66Atransmettre', 'Model' );
	App::uses( 'CakeTestSession', 'CakeTest.Model/Datasource' );

	class PublicWebrsaCohortesDossierspcgs66Component extends WebrsaCohortesDossierspcgs66Component
	{
		/**
		 * Appelle dynamiquement les fonctions protégés
		 * $this->foo() appellera $this->_foo()
		 *
		 * @param string $name
		 * @param array $arguments
		 * @return mixed
		 */
		public function __call($name, array $arguments){
			if ( method_exists($this, '_'.$name) ) {
				return call_user_func_array(array($this, '_'.$name), $arguments);
			}
			else {
				throw new NotImplementedException(__CLASS__.'::'.$name.'() not found!');
			}
		}
	}

	/**
	 * WebrsaCohorteDossierpcg66TestsController class
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebrsaCohorteDossierpcg66TestsController extends AppController
	{
		/**
		 * name property
		 *
		 * @var string
		 */
		public $name = 'Dossierspcgs66';

		/**
		 * uses property
		 *
		 * @var mixed null
		 */
		public $uses = array( 'Dossierpcg66', 'User', 'WebrsaCohorteDossierpcg66Atransmettre' );

		/**
		 * components property
		 *
		 * @var array
		 */
		public $components = array(
			'Jetons2', // ??
			'PublicWebrsaCohortesDossierspcgs66',
			'Cohortes',
			'Session'
		);

		/**
		 * Les paramètres de redirection.
		 *
		 * @var array
		 */
		public $redirected = null;

		/**
		 *
		 * @param string|array $url A string or array-based URL pointing to another location within the app,
		 *     or an absolute URL
		 * @param integer $status Optional HTTP status code (eg: 404)
		 * @param boolean $exit If true, exit() will be called after the redirect
		 * @return mixed void if $exit = false. Terminates script if $exit = true
		 */
		public function redirect( $url, $status = null, $exit = true) {
			$this->redirected = array( $url, $status, $exit );
			return false;
		}
	}

	/**
	 * La classe WebrsaAbstractCohortesComponentTest ...
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class WebrsaAbstractCohortesComponentTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Appellationromev3',
			'app.Bilanparcours66',
			'app.Calculdroitrsa',
			'app.Categoriemetierromev2',
			'app.Categorieromev3',
			'app.Decdospcg66Orgdospcg66',
			'app.Decisiondossierpcg66',
			'app.Decisionpersonnepcg66',
			'app.Decisionpdo',
			'app.Defautinsertionep66',
			'app.Detailcalculdroitrsa',
			'app.Detaildroitrsa',
			'app.Domaineromev3',
			'app.Dossier',
			'app.Dossierpcg66',
			'app.Familleromev3',
			'app.Fichiermodule',
			'app.Foyer',
			'app.Historiqueetatpe',
			'app.Informationpe',
			'app.Jeton',
			'app.Metierromev3',
			'app.Modecontact',
			'app.Nonoriente66',
			'app.Nonorientationprocov58',
			'app.Nonorientationproep66',
			'app.Nonorientationproep93',
			'app.Nonrespectsanctionep93',
			'app.Orgtransmisdossierpcg66',
			'app.Orientstruct',
			'app.OrientstructServiceinstructeur',
			'app.Originepdo',
			'app.Personne',
			'app.Personnepcg66',
			'app.PersonneReferent',
			'app.Poledossierpcg66',
			'app.Prestation',
			'app.Propoorientationcov58',
			'app.Propoorientsocialecov58',
			'app.Propononorientationprocov58',
			'app.Referent',
			'app.Regressionorientationep58',
			'app.Reorientationep93',
			'app.Saisinebilanparcoursep66',
			'app.Serviceinstructeur',
			'app.Situationdossierrsa',
			'app.Situationpdo',
			'app.Statutpdo',
			'app.Structurereferente',
			'app.StructurereferenteZonegeographique',
			'app.Transfertpdv93',
			'app.Traitementpcg66',
			'app.Typeorient',
			'app.Typepdo',
			'app.User',
			'app.Zonegeographique',
		);

		/**
		 * Controller property
		 *
		 * @var WebrsaAbstractCohortesComponent
		 */
		public $Controller;

		/**
		 * Liste des clés d'options disponibles.
		 *
		 * @var array
		 */
		public $optionKeys = array (
			0 => 'Adresse.numcom',
			1 => 'Adresse.pays',
			2 => 'Adresse.typeres',
			3 => 'Adressefoyer.rgadr',
			4 => 'Adressefoyer.typeadr',
			5 => 'Calculdroitrsa.toppersdrodevorsa',
			6 => 'Canton.canton',
			7 => 'Categorieromev3.appellationromev3_id',
			8 => 'Categorieromev3.domaineromev3_id',
			9 => 'Categorieromev3.familleromev3_id',
			10 => 'Categorieromev3.metierromev3_id',
			11 => 'Decdospcg66Orgdospcg66.orgtransmisdossierpcg66_id',
			12 => 'Decisiondossierpcg66.decisionpdo_id',
			13 => 'Decisiondossierpcg66.org_id',
			14 => 'Detailcalculdroitrsa.natpf',
			15 => 'Detaildroitrsa.oridemrsa',
			16 => 'Detaildroitrsa.topfoydrodevorsa',
			17 => 'Detaildroitrsa.topsansdomfixe',
			18 => 'Dossier.anciennete_dispositif',
			19 => 'Dossier.fonorg',
			20 => 'Dossier.fonorgcedmut',
			21 => 'Dossier.fonorgprenmut',
			22 => 'Dossier.locked',
			23 => 'Dossier.numorg',
			24 => 'Dossier.statudemrsa',
			25 => 'Dossier.typeparte',
			26 => 'Dossierpcg66.etatdossierpcg',
			27 => 'Dossierpcg66.haspiecejointe',
			28 => 'Dossierpcg66.iscomplet',
			29 => 'Dossierpcg66.istransmis',
			30 => 'Dossierpcg66.orgpayeur',
			31 => 'Dossierpcg66.originepdo_id',
			32 => 'Dossierpcg66.poledossierpcg66_id',
			33 => 'Dossierpcg66.serviceinstructeur_id',
			34 => 'Dossierpcg66.typepdo_id',
			35 => 'Dossierpcg66.user_id',
			36 => 'Foyer.haspiecejointe',
			37 => 'Foyer.sitfam',
			38 => 'Foyer.typeocclog',
			39 => 'Personne.pieecpres',
			40 => 'Personne.qual',
			41 => 'Personne.sexe',
			42 => 'Personne.typedtnai',
			43 => 'PersonneReferent.referent_id',
			44 => 'PersonneReferent.structurereferente_id',
			45 => 'Prestation.rolepers',
			46 => 'Referentparcours.qual',
			47 => 'Sitecov58.id',
			48 => 'Situationdossierrsa.etatdosrsa',
			49 => 'Situationdossierrsa.moticlorsa',
			50 => 'Structurereferenteparcours.type_voie',
			51 => 'Traitementpcg66.situationpdo_id',
			52 => 'Traitementpcg66.statutpdo_id',
		);

		/**
		 * Préparation du test.
		 */
		public function setUpUrl( array $url ) {
			$Request = new CakeRequest( "{$url['controller']}/{$url['action']}", false );
			$Request->addParams( $url );

			$this->Controller = new WebrsaCohorteDossierpcg66TestsController( $Request );
			$this->Controller->Components->init( $this->Controller );
			$this->Controller->Session->initialize( $this->Controller );
			$this->Controller->Jetons2->initialize( $this->Controller );
			$this->Controller->Cohortes->initialize( $this->Controller );
			$this->Controller->PublicWebrsaCohortesDossierspcgs66->initialize( $this->Controller );

			$this->params = $this->Controller->PublicWebrsaCohortesDossierspcgs66->params( array(
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66Atransmettre'
			) );

			CakeTestSession::write('Auth.User.id', 456);
		}

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			Configure::write( 'Cg.departement', 66 );
			parent::setUp();
			CakeTestSession::start();
		}

		/**
		 * tearDown method
		 *
		 * @return void
		 */
		public function tearDown() {
			CakeTestSession::destroy();
			parent::tearDown();
		}

		/**
		 * test case startup
		 *
		 * @return void
		 */
		public static function setupBeforeClass() {
			CakeTestSession::setupBeforeClass();
		}

		/**
		 * cleanup after test case.
		 *
		 * @return void
		 */
		public static function teardownAfterClass() {
			CakeTestSession::teardownAfterClass();
		}

		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::params()
		 */
		public function testParams() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			$result = $this->Controller->PublicWebrsaCohortesDossierspcgs66->params();
			$expected = array (
				'modelRechercheName' => 'WebrsaCohorteDossierpcg66',
				'modelName' => 'Dossierpcg66',
				'searchKey' => 'Search',
				'searchKeyPrefix' => 'ConfigurableQuery',
				'configurableQueryFieldsKey' => 'Dossierspcgs66.cohorte_atransmettre',
				'cohorteKey' => 'Cohorte',
				'dossierIdPath' => '{n}.Dossier.id',
				'modelSave' => 'Dossierpcg66',
				'auto' => false,
				'filtresdefautClass' => 'Search.Filtresdefaut'
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::options()
		 */
		public function testOptions() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			$result = array();
			foreach( $this->Controller->PublicWebrsaCohortesDossierspcgs66->options() as $modelName => $params ) {
				foreach( array_keys( $params ) as $fieldName ) {
					$result[] = "{$modelName}.{$fieldName}";
				}
			}
			sort( $result );
			$expected = $this->optionKeys;
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::_getQuery()
		 */
		public function testGetQuery() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );
			$user_id = $this->Controller->Session->read( 'Auth.User.id' );
			$php_sid = $this->Controller->Session->id();

			$result = $this->Controller->PublicWebrsaCohortesDossierspcgs66->getQuery( $this->params );
			$expected = array(
				'limit' => (int) 10,
				'fields' => array(
					(int) 0 => 'Dossierpcg66.id',
					(int) 1 => 'Dossierpcg66.foyer_id',
					(int) 2 => 'Dossier.id',
					(int) 3 => 'Dossierpcg66.poledossierpcg66_id',
					(int) 4 => 'Dossierpcg66.dateaffectation',
					(int) 5 => 'Decisiondossierpcg66.id',
					(int) 6 => '( "Dossier"."id" IN ( SELECT "jetons"."dossier_id" AS "jetons__dossier_id" FROM "jetons" AS "jetons"   WHERE (NOT ("jetons"."php_sid" = \''.$php_sid.'\') AND NOT ("jetons"."user_id" = '.$user_id.')) AND "modified" >= \'XXX\' AND "jetons"."dossier_id" = "Dossier"."id"    ) ) AS "Dossier__locked"',
				),
				'joins' => array(
					(int) 0 => array(
						'table' => '"foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."foyer_id" = "Foyer"."id"'
					),
					(int) 1 => array(
						'table' => '"dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"'
					),
					(int) 2 => array(
						'table' => '"personnes"',
						'alias' => 'Personne',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id"'
					),
					(int) 3 => array(
						'table' => '"calculsdroitsrsa"',
						'alias' => 'Calculdroitrsa',
						'type' => 'RIGHT',
						'conditions' => '"Calculdroitrsa"."personne_id" = "Personne"."id"'
					),
					(int) 4 => array(
						'table' => '"prestations"',
						'alias' => 'Prestation',
						'type' => 'LEFT OUTER',
						'conditions' => '"Prestation"."personne_id" = "Personne"."id" AND "Prestation"."natprest" = \'RSA\''
					),
					(int) 5 => array(
						'table' => '"adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id" AND "Adressefoyer"."id" IN( SELECT "adressesfoyers"."id" AS adressesfoyers__id FROM adressesfoyers AS adressesfoyers   WHERE "adressesfoyers"."foyer_id" = "Foyer"."id" AND "adressesfoyers"."rgadr" = \'01\'   ORDER BY "adressesfoyers"."dtemm" DESC  LIMIT 1 )'
					),
					(int) 6 => array(
						'table' => '"adresses"',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"'
					),
					(int) 7 => array(
						'table' => '"situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"'
					),
					(int) 8 => array(
						'table' => '"detailsdroitsrsa"',
						'alias' => 'Detaildroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detaildroitrsa"."dossier_id" = "Dossier"."id"'
					),
					(int) 9 => array(
						'table' => '"personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id" AND (("PersonneReferent"."id" IS NULL) OR ("PersonneReferent"."id" IN ( SELECT "personnes_referents"."id"
					FROM personnes_referents
					WHERE
						"personnes_referents"."personne_id" = "Personne"."id"
						AND "personnes_referents"."dfdesignation" IS NULL
					ORDER BY "personnes_referents"."dddesignation" DESC
					LIMIT 1 )))'
					),
					(int) 10 => array(
						'table' => '"referents"',
						'alias' => 'Referentparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."referent_id" = "Referentparcours"."id"'
					),
					(int) 11 => array(
						'table' => '"structuresreferentes"',
						'alias' => 'Structurereferenteparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"Referentparcours"."structurereferente_id" = "Structurereferenteparcours"."id"'
					),
					(int) 12 => array(
						'table' => '"detailscalculsdroitsrsa"',
						'alias' => 'Detailcalculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detailcalculdroitrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id"'
					),
					(int) 13 => array(
						'table' => '"decisionsdossierspcgs66"',
						'alias' => 'Decisiondossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id"'
					),
					(int) 14 => array(
						'table' => '"users"',
						'alias' => 'User',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."user_id" = "User"."id"'
					),
					(int) 15 => array(
						'table' => '"polesdossierspcgs66"',
						'alias' => 'Poledossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."poledossierpcg66_id" = "Poledossierpcg66"."id"'
					),
					(int) 16 => array(
						'table' => '"personnespcgs66"',
						'alias' => 'Personnepcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."personne_id" = "Personne"."id" AND "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id"'
					),
					(int) 17 => array(
						'table' => '"entreesromesv3"',
						'alias' => 'Categorieromev3',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."categorieromev3_id" = "Categorieromev3"."id"'
					),
					(int) 18 => array(
						'table' => '"famillesromesv3"',
						'alias' => 'Familleromev3',
						'type' => 'LEFT OUTER',
						'conditions' => '"Categorieromev3"."familleromev3_id" = "Familleromev3"."id"'
					),
					(int) 19 => array(
						'table' => '"domainesromesv3"',
						'alias' => 'Domaineromev3',
						'type' => 'LEFT OUTER',
						'conditions' => '"Domaineromev3"."familleromev3_id" = "Familleromev3"."id"'
					),
					(int) 20 => array(
						'table' => '"metiersromesv3"',
						'alias' => 'Metierromev3',
						'type' => 'LEFT OUTER',
						'conditions' => '"Metierromev3"."domaineromev3_id" = "Domaineromev3"."id"'
					),
					(int) 21 => array(
						'table' => '"appellationsromesv3"',
						'alias' => 'Appellationromev3',
						'type' => 'LEFT OUTER',
						'conditions' => '"Appellationromev3"."metierromev3_id" = "Metierromev3"."id"'
					),
					(int) 22 => array(
						'table' => '"codesromemetiersdsps66"',
						'alias' => 'Categoriemetierromev2',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."categoriedetail" = "Categoriemetierromev2"."id"'
					),
					(int) 23 => array(
						'table' => '"traitementspcgs66"',
						'alias' => 'Traitementpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id" AND (("Traitementpcg66"."id" IS NULL) OR ((("Traitementpcg66"."id" IN (SELECT "traitementspcgs66"."id" AS traitementspcgs66__id FROM traitementspcgs66 AS traitementspcgs66 WHERE "traitementspcgs66"."personnepcg66_id" = "Personnepcg66"."id" ORDER BY "traitementspcgs66"."created" DESC LIMIT 1))  AND  ("Traitementpcg66"."typetraitement" = \'documentarrive\')  AND  ("Traitementpcg66"."datereception" IS NOT NULL)  AND  ("Dossierpcg66"."etatdossierpcg" = \'attinstrdocarrive\'))))'
					),
					(int) 24 => array(
						'table' => '"decisionspdos"',
						'alias' => 'Decisionpdo',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."decisionpdo_id" = "Decisionpdo"."id"'
					),
					(int) 25 => array(
						'table' => '"typespdos"',
						'alias' => 'Typepdo',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."typepdo_id" = "Typepdo"."id"'
					),
					(int) 26 => array(
						'table' => '"originespdos"',
						'alias' => 'Originepdo',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."originepdo_id" = "Originepdo"."id"'
					),
					(int) 27 => array(
						'table' => '"servicesinstructeurs"',
						'alias' => 'Serviceinstructeur',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."serviceinstructeur_id" = "Serviceinstructeur"."id"'
					)
				),
				'contain' => false,
				'conditions' => array(
					'OR' => array(
						'Prestation.rolepers' => array(
							(int) 0 => 'DEM',
							(int) 1 => 'CJT'
						),
						(int) 0 => 'Prestation.id IS NULL'
					),
					(int) 0 => array(
						'Prestation.rolepers' => 'DEM',
						(int) 0 => array(
							'OR' => array(
								'Detailcalculdroitrsa.id' => null,
								(int) 0 => 'Detailcalculdroitrsa.id IN (SELECT "detailscalculsdroitsrsa"."id" AS detailscalculsdroitsrsa__id FROM detailscalculsdroitsrsa AS detailscalculsdroitsrsa WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC LIMIT 1)'
							)
						),
						(int) 1 => array(
							'OR' => array(
								'Decisiondossierpcg66.id' => null,
								(int) 0 => 'Decisiondossierpcg66.id IN (SELECT "decisionsdossierspcgs66"."id" FROM decisionsdossierspcgs66 WHERE "decisionsdossierspcgs66"."dossierpcg66_id" = "Dossierpcg66"."id" ORDER BY "decisionsdossierspcgs66"."created" DESC LIMIT 1)'
							)
						),
						(int) 2 => array(
							'OR' => array(
								(int) 0 => 'Categorieromev3.familleromev3_id IS NULL',
								(int) 1 => 'Familleromev3.id = Categorieromev3.familleromev3_id'
							)
						),
						(int) 3 => array(
							'OR' => array(
								(int) 0 => 'Categorieromev3.domaineromev3_id IS NULL',
								(int) 1 => 'Domaineromev3.id = Categorieromev3.domaineromev3_id'
							)
						),
						(int) 4 => array(
							'OR' => array(
								(int) 0 => 'Categorieromev3.metierromev3_id IS NULL',
								(int) 1 => 'Metierromev3.id = Categorieromev3.metierromev3_id'
							)
						),
						(int) 5 => array(
							'OR' => array(
								(int) 0 => 'Categorieromev3.appellationromev3_id IS NULL',
								(int) 1 => 'Appellationromev3.id = Categorieromev3.appellationromev3_id'
							)
						),
						(int) 6 => array(
							'OR' => array(
								(int) 0 => 'Personnepcg66.categoriedetail IS NULL',
								(int) 1 => 'Categoriemetierromev2.id = Personnepcg66.categoriedetail'
							)
						)
					),
					(int) 1 => array(
						'Dossierpcg66.etatdossierpcg' => 'atttransmisop',
						(int) 0 => 'Dossierpcg66.dateimpression IS NOT NULL',
						'Dossierpcg66.istransmis' => '0'
					),
					(int) 2 => array(),
					(int) 3 => 'NOT ( "Dossier"."id" IN ( SELECT "jetons"."dossier_id" AS "jetons__dossier_id" FROM "jetons" AS "jetons"   WHERE (NOT ("jetons"."php_sid" = \''.$php_sid.'\') AND NOT ("jetons"."user_id" = '.$user_id.')) AND "modified" >= \'XXX\' AND "jetons"."dossier_id" = "Dossier"."id"    ) )'
				)
			);
			$result['fields'][6] = preg_replace( '/"modified" >= \'[^\']+\'/', '"modified" >= \'XXX\'', $result['fields'][6] );
			$result['conditions'][3] = preg_replace( '/"modified" >= \'[^\']+\'/', '"modified" >= \'XXX\'',	$result['conditions'][3] );

			$flatExpected = Hash::flatten($expected,'__');
			foreach(Hash::flatten($result,'__') as $key => $value) {
				if ($value !== $flatExpected[$key]) {
					debug(array($key => array(
					'result___value' => preg_replace('/[\n]/', '*n*', preg_replace('/[\t]/', '*t*', $value)),
					'expected_value' => preg_replace('/[\n]/', '*n*', preg_replace('/[\t]/', '*t*', $flatExpected[$key]))
					)));
				}
			}

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		public function testFormatFieldsForInsert() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			$fields = array(
				'Mymodel.field1' => array( 'type' => 'text' ),
				'Mymodel.field2' => array( 'type' => 'checkbox' ),
				'Mymodel2.field1' => array( 'type' => 'select', 'options' => array(1,2,3) ),
			);
			$result = $this->Controller->PublicWebrsaCohortesDossierspcgs66->formatFieldsForInsert( $fields, $this->params );

			$expected = array (
				'data[Cohorte][][Mymodel][field1]' =>
					array (
					  'type' => 'text',
					  'options' => NULL,
					),
				'data[Cohorte][][Mymodel][field2]' =>
					array (
					  'type' => 'checkbox',
					  'options' => NULL,
					),
				'data[Cohorte][][Mymodel2][field1]' =>
					array (
					  'type' => 'select',
					  'options' => array(1,2,3),
					),
			);
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}


		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::cohorte() sans que le
		 * formulaire ait été envoyé.
		 *
		 */
		public function testCohorte() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			$this->Controller->PublicWebrsaCohortesDossierspcgs66->cohorte();
			$result = $this->Controller->viewVars;
			$this->assertTrue( isset( $this->Controller->viewVars['options'] ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::cohorte() après que le
		 * formulaire ait été envoyé, sans aucune configuration.
		 */
		public function testCohorteFormSentNoConfiguration() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			$this->Controller->request->data = array( 'Search' => array( 'Personne' => array( 'nom' => 'BUFFIN' ) ) );
			$this->Controller->PublicWebrsaCohortesDossierspcgs66->cohorte( $this->params );
			$this->assertTrue( isset( $this->Controller->viewVars['results'] ) );

			$expected = array (
				array (
					'Dossierpcg66' => array (
						'id' => 1,
						'foyer_id' => 1,
						'poledossierpcg66_id' => 1,
						'dateaffectation' => '2012-12-11',
					),
					'Dossier' => array (
						'id' => 1,
						'locked' => false,
					),
					'Decisiondossierpcg66' => array (
						'id' => 1,
					),
				),
			);
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::search() après que le
		 * formulaire ait été envoyé, avec de la configuration.
		 */
		public function testCohorteFormSentWithConfiguration() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			Configure::write(
				'ConfigurableQueryDossierspcgs66',
				array(
					'cohorte_atransmettre' => array(
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
			$this->Controller->PublicWebrsaCohortesDossierspcgs66->cohorte( $this->params );
			$this->assertTrue( isset( $this->Controller->viewVars['results'] ) );

			$expected = array(
				array(
					'Personne' => array(
						'nom' => 'BUFFIN',
					),
					'Prestation' => array(
						'rolepers' => 'DEM',
					),
					'Dossierpcg66' => array (
						'id' => 1,
						'foyer_id' => 1,
						'poledossierpcg66_id' => 1,
						'dateaffectation' => '2012-12-11',
					),
					'Dossier' => array (
						'id' => 1,
						'locked' => false,
					),
					'Decisiondossierpcg66' => array (
						'id' => 1,
					),
				)
			);
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode WebrsaAbstractCohortesComponent::exportcsv()avec de la configuration.
		 */
		public function testExportcsvWithConfiguration() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'exportcsv' ) );

			Configure::write(
				'ConfigurableQueryDossierspcgs66',
				array(
					'exportcsv' => array(
						'Dossier.numdemrsa',
						'Personne.nom',
						'Prestation.rolepers'
					)
				)
			);
			$this->Controller->request->params['named'] = Hash::flatten( array( 'Search' => array( 'Personne' => array( 'nom' => 'BUFFIN' ) ) ), '__' );
			$this->Controller->PublicWebrsaCohortesDossierspcgs66->exportcsv( $this->params );

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
						'id' => 1,
						'numdemrsa' => '66666666693',
						'locked' => false,
					),
					'Dossierpcg66' => array (
						'id' => 1,
						'foyer_id' => 1,
						'poledossierpcg66_id' => 1,
						'dateaffectation' => '2012-12-11',
					),
					'Decisiondossierpcg66' => array (
						'id' => 1,
					),
				)
			);
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}

		/**
		 * On test l'enregistrement d'une cohorte, apres l'enregistrement, $result doit être vide
		 */
		public function testSaveCohorte() {
			$this->setUpUrl( array( 'controller' => 'dossierspcgs66', 'action' => 'cohorte_atransmettre' ) );

			$this->Controller->request->data = array(
				'Search' => array( 'Personne' => array( 'nom' => 'BUFFIN' ) ),
				'Cohorte' => array(
					(int) 0 => array(
						'Dossierpcg66' => array(
							'istransmis' => '1',
							'id' => '1'
						),
						'Decdospcg66Orgdospcg66' => array(
							'orgtransmisdossierpcg66_id' => array(
								(int) 0 => '1'
							),
							'decisiondossierpcg66_id' => '1'
						),
						'Decisiondossierpcg66' => array(
							'datetransmissionop' => array(
								'month' => '09',
								'day' => '07',
								'year' => '2015'
							),
							'id' => '1'
						)
					)
				)
			);
			$this->Controller->PublicWebrsaCohortesDossierspcgs66->cohorte( $this->params );

			$expected = array();
			$result = (array)Hash::get( $this->Controller->viewVars, 'results' );
			$this->assertEquals( $expected, $result, var_export( $result, true ) );
		}
	}
?>