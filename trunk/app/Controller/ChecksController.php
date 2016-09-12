<?php
	/**
	 * Code source de la classe ChecksController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	 App::uses('WebrsaCheckAccess', 'Utility');

	/**
	 * La classe ChecksController ...
	 *
	 * @package app.Controller
	 */
	class ChecksController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Checks';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Gedooo.Gedooo',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Appchecks.Checks',
			'Default',
			'Default2',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Appchecks.Check',
			'WebrsaRecherche',
			'WebrsaCheck',
		);

		/**
		 * Utilise les droits d'un autre Controller:action
		 * sur une action en particulier
		 *
		 * @var array
		 */
		public $commeDroit = array(

		);

		/**
		 * Méthodes ne nécessitant aucun droit.
		 *
		 * @var array
		 */
		public $aucunDroit = array(

		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
		);

		/**
		 * Vérifications concernant Apache:
		 *	- la version utilisée
		 *	- les modules nécessaires
		 *
		 * @return array
		 * @access protected
		 */
		protected function _apache() {
			return array(
				'Apache' => array(
					'informations' => array(
						'Version' => $this->Check->version( 'Apache', apache_version(), '2.2' )
					),
					'modules' => $this->Check->apacheModules(
						array(
							'mod_expires',
							'mod_rewrite'
						)
					),
				)
			);
		}

		/**
		 * Vérifications concernant PHP:
		 *	- la version utilisée
		 *	- les extensions nécessaires
		 *	- les variables du php.ini nécessaires
		 *
		 * @return array
		 * @access protected
		 */
		protected function _php() {
			return array(
				'Php' => array(
					'informations' => array(
						'Version' => $this->Check->version( 'PHP', phpversion(), '5.3' )
					),
					'extensions' => $this->Check->phpExtensions(
						array(
							'curl',
							'dom',
							'mbstring',
							'soap',
							'xml'
						)
					),
					'inis' => $this->Check->phpInis(
						array(
							'date.timezone',
							'post_max_size',
							 // Pour PHP >= 5.3.9, le passer à au moins 2500
							'max_input_vars' => array(
								'comparison' => array(
									'rule' => array( 'comparison', '>=', 2500 ),
									'allowEmpty' => false
								),
							),
						)
					),
					'pear_extensions' => $this->Check->pearExtensions(
						array(
							'xml_rpc'
						)
					)
				)
			);
		}

		/**
		 *
		 * @return array
		 * @access protected
		 */
		protected function _environment() {
			return array(
				'Environment' => array(
					'binaries' => $this->Check->binaries(
						array(
							'pdftk'
						)
					),
					'directories' => $this->Check->directories(
						array(
							TMP => 'w',
							Configure::read( 'Cohorte.dossierTmpPdfs' ) => 'w'
						),
						ROOT.DS
					),
					'files' => $this->Check->files(
						array(
							CONFIGS.'webrsa.inc',
							CSS.'webrsa.css',
							JS.'webrsa.js'
						),
						ROOT.DS
					),
					'cache' => $this->Check->cachePermissions(),
					'freespace' => $this->Check->freespace(
						array(
							// 1. Répertoire temporaire de CakePHP
							TMP,
							// 2. Répertoire temporaire pour les PDF.
							Configure::read( 'Cohorte.dossierTmpPdfs' ),
							// 3. Répertoire de cache des wsdl
							ini_get( 'soap.wsdl_cache_dir' ),
						)
					)
				)
			);
		}

		/**
		 * Vérifications de la présence des fichiers de modèle .odt (paramétrbles et statiques).
		 *
		 * @return array
		 * @access protected
		 */
		protected function _modeles() {
			$modeles = $this->WebrsaCheck->allModelesOdt( Configure::read( 'Cg.departement' ) );

			return array(
				'Modelesodt' => array(
					'parametrables' => $this->Check->modelesOdt(
						$modeles['parametrables'],
						MODELESODT_DIR
					),
					'statiques' => $this->Check->modelesOdt(
						$modeles['statiques'],
						MODELESODT_DIR
					)
				)
			);
		}

		/**
		 * Vérifications concernant PostgreSQL:
		 *	- la version utilisée
		 *	- la présence des fonctions fuzzystrmatch
		 *	- la différence de date entre le serveur Web et le serveur PostgreSQL
		 *
		 * @return array
		 * @access protected
		 */
		protected function _postgresql() {
			$Dbo = $this->User->getDataSource();

			return array(
				'Postgresql' => array(
					'Version' => $this->Check->version( 'PostgreSQL', $Dbo->getPostgresVersion(), '8.3' ),
					'Fuzzystrmatch' => $this->WebrsaCheck->checkPostgresFuzzystrmatchFunctions(),
					'Date' => $this->WebrsaCheck->checkPostgresTimeDifference()
				)
			);
		}

		/**
		 * Vérifications concernant CakePHP:
		 *	- la version utilisée
		 *
		 * @return array
		 * @access protected
		 */
		protected function _cakephp() {
			return array(
				'Cakephp' => array(
					'informations' => array(
						'Version' => $this->Check->version( 'CakePHP', Configure::version(), '2.2.4' ),
						'Timeout' => $this->Check->timeout()
					),
					'cache' => $this->Check->durations()
				)
			);
		}

		/**
		 * Vérification de la présence des enregistrements dont les clés primaires
		 * sont configurées dans le webrsa.inc.
		 *
		 * @return array
		 */
		protected function _configurePrimaryKeys() {
			$return = $this->WebrsaCheck->allConfigurePrimaryKeys();

			if( !empty( $return ) ) {
				foreach( $return as $key => $params ) {
					if( is_string( $params ) ) {
						$params = array( 'modelName' => $params );
					}
					$params = Hash::merge( array( 'array_keys' => false ), $params );

					$return[$key] = $this->Check->configurePrimaryKey( $params['modelName'], $key, $params['array_keys'] );
				}
			}

			return $return;
		}

		/**
		 * Vérifications concernant WebRSA:
		 *	- la version utilisée
		 *  - la vérification de paramètres de configuration (Configure::read)
		 *
		 * @return array
		 * @access protected
		 */
		protected function _webrsa() {
			$recherches = $this->WebrsaRecherche->checks();
			foreach( $recherches as $key => $params ) {
				$recherches[$key]['config'] = $this->Check->configure( $params['config'] );
			}

			return array(
				'Webrsa' => array(
					'informations' => array(
						'Version' => $this->Check->version( 'WebRSA', app_version(), '0' ),
					),
					'configure' =>  $this->Check->configure(
						$this->WebrsaCheck->allConfigureKeys( Configure::read( 'Cg.departement' ) )
					),
					'intervals' => $this->WebrsaCheck->checkAllPostgresqlIntervals( Configure::read( 'Cg.departement' ) ),
					'querydata_fragments_errors' => $this->WebrsaCheck->allQuerydataFragmentsErrors(),
					'sqRechercheErrors' => $this->WebrsaCheck->allSqRechercheErrors(),
					'configure_primary_key' => $this->_configurePrimaryKeys(),
					'configure_regexps' => $this->WebrsaCheck->allConfigureRegexpsErrors(),
					'configure_fields' => $this->WebrsaCheck->allCheckParametrage(),
					'ini_set' => $this->WebrsaCheck->allConfigureIniSet(),
					'configure_bad_keys' => $this->WebrsaCheck->allCheckBadKeys(),
					'configurable_query' => $recherches,
					'configure_evidence' => $this->WebrsaCheck->allConfigureEvidence(),
					'tableaux_conditions' => $this->WebrsaCheck->allConfigureTableauxConditions(),
					'webrsa_access' => WebrsaCheckAccess::checkWebrsaAccess(),
				)
			);
		}

		/**
		 * Retourne les enregistrements pour lesquels une erreur de paramétrage
		 * a été détectée.
		 *
		 * @return array
		 */
		protected function _storedDataErrors() {
			return array(
				'Storeddata' => array(
					'errors' => $this->WebrsaCheck->allStoredDataErrors()
				)
			);
		}

		/**
		 * Vérifie la configuration des services (Gedooo, Alfresco, ...).
		 *
		 * @return array
		 */
		protected function _services() {
			$services = $this->WebrsaCheck->services();

			if( !empty( $services ) ) {
				foreach( $services as $serviceName => $results ) {
					if( isset( $results['configure'] ) ) {
						$services[$serviceName]['configure'] = $this->Check->configure(
							$results['configure']
						);
					}
				}
			}

			return array( 'Services' => $services );
		}

		/**
		 * Vérifie la configuration des mails.
		 *
		 * @return array
		 */
		protected function _emails() {
			$names = $this->WebrsaCheck->allEmailConfigs();
			$results = array();

			foreach( $names as $name ) {
				$results[$name] = $this->Check->cakeEmailConfig( $name );
			}

			return array( 'Emails' => $results );
		}

		/**
		 * Vérification complète de l'application et envoi des résultats à la vue.
		 *
		 * @return void
		 * @access public
		 */
		public function index() {
			$this->Gedooo->makeTmpDir( Configure::read( 'Cohorte.dossierTmpPdfs' ) );

			$results = Hash::merge(
				$this->_apache(),
				$this->_php(),
				$this->_environment(),
				$this->_modeles(),
				$this->_postgresql(),
				$this->_cakephp(),
				$this->_webrsa(),
				$this->_storedDataErrors(),
				$this->_services(),
				$this->_emails()
			);

			$this->set( 'results', $results );
		}
	}
?>