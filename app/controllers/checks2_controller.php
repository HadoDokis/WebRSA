<?php
    /**
     *
     * PHP 5.3
     *
     * @package       app.controllers
     */
	class Checks2Controller extends AppController
	{
		/**
		* @access public
		*/
		public $name = 'Checks2';

		/**
		* @access public
		*/
		public $uses = array( 'Appchecks.Check', 'Webrsacheck' );

		/**
		* @access public
		*/
		public $helpers = array( 'Appchecks.Checks', 'Default2' );

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
							'date.timezone'
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
				),
			);
		}

		/**
		 * Vérifications de la présence des fichiers de modèle .odt (paramétrbles et statiques).
		 *
		 * @return array
		 * @access protected
		 */
		protected function _modeles() {
			$modeles = $this->Webrsacheck->allModelesOdt( Configure::read( 'Cg.departement' ) );

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
			$this->User->Behaviors->attach( 'Pgsqlcake.Schema' );
			$version = $this->User->pgVersion();
			$shortversion = preg_replace( '/^([0-9]+\.[0-9]+).*/', '\1', $version );

			return array(
				'Postgresql' => array(
					'Version' => $this->Check->version( 'PostgreSQL', $version, '8.3' ),
					'Fuzzystrmatch' => $this->User->pgHasFunctions(
						array(
							'levenshtein',
							'metaphone',
							'soundex',
							'text_soundex',
							'difference',
							'dmetaphone',
							'dmetaphone_alt'
						),
						"Problème avec les fonctions fuzzystrmatch (les fonctions suivantes sont manquantes: %s)<br/>Sous Ubuntu, il vous faut vérifier que le paquet postgresql-contrib-{$shortversion} est bien installé. <br />Une fois fait, dans une console postgresql, en tant qu'administrateur, tapez: <code>\i /usr/share/postgresql/{$shortversion}/contrib/fuzzystrmatch.sql</code>"
					),
					'Date' => $this->User->pgCheckTimeDifference()
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
						'Version' => $this->Check->version( 'CakePHP', cake_version(), '1.2.10' ),
						'Timeout' => $this->Check->timeout()
					)
				)
			);
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
			return array(
				'Webrsa' => array(
					'informations' => array(
						'Version' => array(
							'value' => app_version(),
							'success' => true
						)
					),
					'configure' =>  $this->Check->configure(
						$this->Webrsacheck->allConfigureKeys( Configure::read( 'Cg.departement' ) )
					),
					'intervals' => $this->Webrsacheck->checkAllPostgresqlIntervals( Configure::read( 'Cg.departement' ) )
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
					'errors' => $this->Webrsacheck->allStoredDataErrors()
				)
			);
		}

		/**
		 * Vérification complète de l'application et envoi des résultats à la vue.
		 *
		 * @return void
		 * @access public
		 */
		public function index() {
			$results = Set::merge(
				$this->_apache(),
				$this->_php(),
				$this->_environment(),
				$this->_modeles(),
				$this->_postgresql(),
				$this->_cakephp(),
				$this->_webrsa(),
				$this->_storedDataErrors()
			);
			$this->set( 'results', $results );
		}
	}
?>