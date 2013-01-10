<?php
	/**
	 * Fichier source de la classe AppController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 */
	App::uses( 'Controller', 'Controller' );

	/**
	 * Classe de base de tous les contrôleurs de l'application.
	 *
	 * @package app.Controller
	 */
	class AppController extends Controller
	{
		/**
		 * Components utilisés
		 *
		 * @var array
		 */
		public $components = array( 'Session', 'Auth', 'Acl' );

		/**
		 * Helpers utilisés
		 *
		 * @var array
		 */
		public $helpers = array( 'Xhtml', 'Form', 'Permissions', 'Locale', 'Default', 'Xpaginator', 'Gestionanomaliebdd', 'Menu' );

		/**
		 * Modèles utilisés
		 *
		 * @var array
		 */
		public $uses = array( 'User', 'Connection' );

		/**
		 * Méthode temporaire permettant de continuer à utiliser AppController::cakeError() durant la
		 * migration.
		 *
		 * @param string $method
		 * @param array $messages
		 * @return boolean
		 */
		public function cakeError( $method, $messages = array() ) {
			return $this->assert( false, $method, $messages );
		}

		/**
		* INFO:
		*   cake/libs/error.php
		*   cake/libs/view/errors/
		*/
		public function assert( $condition, $error = 'error500', $parameters = array( ) ) {
			if( $condition !== true ) {
				$calledFrom = debug_backtrace();
				$calledFromFile = substr( str_replace( ROOT, '', $calledFrom[0]['file'] ), 1 );
				$calledFromLine = $calledFrom[0]['line'];

				$this->log( 'Assertion failed: '.$error.' in '.$calledFromFile.' line '.$calledFromLine.' for url '.$this->request->here );

				// Need to finish transaction ?
				if( isset( $this->{$this->modelClass} ) ) {
					$db = $this->{$this->modelClass}->getDataSource();
					$db->rollback( $this->{$this->modelClass} );
				}

				$exceptionClass = "{$error}Exception";
				if( class_exists( $exceptionClass, false ) ) {
					throw new $exceptionClass( $error );
				}
				else {
					throw new InternalErrorException( $error );
				}

				exit();
			}
		}

		/**
		 * Fait-on une pagination standard ou une pagination progressive ?
		 *
		 * @see Configure::write( 'Optimisations.progressivePaginate', true )
		 *
		 * @param type $object
		 * @param type $scope
		 * @param type $whitelist
		 * @param type $progressivePaginate
		 * @return type
		 */
		public function paginate( $object = null, $scope = array( ), $whitelist = array( ), $progressivePaginate = null ) {
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = $this->_hasProgressivePagination();
			}

			Configure::write( "Optimisations.{$this->name}_{$this->action}.progressivePaginate", $progressivePaginate );

			if( $progressivePaginate ) {
				return $this->Components->load( 'Search.ProgressivePaginator', $this->paginate )->paginate( $object, $scope, $whitelist );
			}
			else {
				return $this->Components->load( 'Paginator', $this->paginate )->paginate( $object, $scope, $whitelist );
			}
		}

		/**
		 * Permet de rajouter des conditions aux conditions de recherches suivant
		 * le paramétrage des service référent dont dépend l'utilisateur connecté.
		 *
		 * Nécessite la mise à true du paramètre 'Recherche.qdFilters.Serviceinstructeur'
		 * ainsi que l'ajout de conditions au service instructeur de l'utilisateur
		 * connecté.
		 *
		 * Utilisé pour l'injection de conditions pour la confidentialité au CG 58.
		 *
		 * @param array $querydata Les querydata dans lesquelles rajouter les conditionss
		 * @return array
		 */
		protected function _qdAddFilters( $querydata ) {
			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				$sqrecherche = $this->Session->read( 'Auth.Serviceinstructeur.sqrecherche' );
				if( !empty( $sqrecherche ) ) {
					$querydata['conditions'][] = $sqrecherche;
				}
			}

			return $querydata;
		}

		/**
		 * Vérification des habilitations de l'utilisateur connecté.
		 *
		 * @return void
		 */
		protected function _checkHabilitations() {
			$habilitations = array(
				'date_deb_hab' => $this->Session->read( 'Auth.User.date_deb_hab' ),
				'date_fin_hab' => $this->Session->read( 'Auth.User.date_fin_hab' )
			);

			$error = (
				( !empty( $habilitations['date_deb_hab'] ) && ( strtotime( $habilitations['date_deb_hab'] ) >= time() ) )
				// Si la date d'habilitation est celle du jour il n'est plus habilité du tout
				|| ( !empty( $habilitations['date_fin_hab'] ) && ( strtotime( $habilitations['date_fin_hab'] ) < time() ) )
			);

			if( $error ) {
				throw new DateHabilitationUserException(
					'Mauvaises dates d\'habilitation de l\'utilisateur',
					401,
					array( 'habilitations' => $habilitations )
				);
			}
		}

		/**
		 * Utilisateurs concurrents, mise à jour du dernier accès pour la connection, au sein d'une transaction.
		 * Si la session a expiré, on redirige sur UsersController::logout
		 *
		 * @return void
		 */
		protected function _updateConnection() {
			if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
				if( !( $this->name == 'Users' && in_array( $this->action, array( 'login', 'logout' ) ) ) ) {
					$connection_id = $this->Connection->field(
						'id',
						array(
							'user_id' => $this->Session->read( 'Auth.User.id' ),
							'php_sid' => $this->Session->id(),
							'( Connection.modified + INTERVAL \''.readTimeout().' seconds\' ) >= NOW()'
						)
					);

					if( !empty( $connection_id ) ) {
						$this->Connection->id = $connection_id;
						$this->Connection->saveField( 'modified', null );
					}
					else {
						$this->redirect( array( 'controller' => 'users', 'action' => 'logout' ) );
					}
				}
			}
		}

		/**
		 * Vérifie que l'utilisateur a la permission d'accéder à la page.
		 *
		 * @see WebrsaPermissions::check()
		 *
		 * @return void
		 */
		protected function _checkPermissions() {
			if( !WebrsaPermissions::check( $this->name, $this->action ) ) {
				throw new error403Exception( null );
			}
		}

		/**
		 * @return void
		 */
		public function beforeFilter() {
			// Désactivation du cache du navigateur: (quand on revient en arrière dans l'historique de
			// navigation, la page n'est pas cachée du côté du navigateur, donc il ré-exécute la demande)
			$this->disableCache();

			//Paramétrage du composant Auth
			$this->Auth->loginAction = array( 'controller' => 'users', 'action' => 'login' );
			$this->Auth->logoutRedirect = array( 'controller' => 'users', 'action' => 'login' );
			$this->Auth->loginRedirect = array( 'controller' => 'dossiers', 'action' => 'index' );
			$this->Auth->authorize = array( 'Actions' => array( 'actionPath' => 'controllers' ) );

			$this->set( 'etatdosrsa', ClassRegistry::init( 'Option' )->etatdosrsa() );
			$return = parent::beforeFilter();

			$this->Auth->allow( '*' );

			// Fin du traitement pour les requestactions et les appels ajax
			if( isset( $this->request->params['requested'] ) ) {
				return $return;
			}

			$isLoginPage = ( substr( $_SERVER['REQUEST_URI'], strlen( $this->request->base ) ) == '/users/login' );
			$isLogoutPage = ( substr( $_SERVER['REQUEST_URI'], strlen( $this->request->base ) ) == '/users/logout' );

			// Utilise-t'on l'alerte de fin de session ?
			$useAlerteFinSession = (
				!$isLoginPage
				&& ( Configure::read( "alerteFinSession" ) )
				&& ( Configure::read( 'debug' ) == 0 )
			);
			$this->set( 'useAlerteFinSession', $useAlerteFinSession );

			if( !$isLoginPage && !$isLogoutPage ) {
				if( !$this->Session->check( 'Auth' ) || !$this->Session->check( 'Auth.User' ) ) {
					//le forcer a se connecter
					$this->redirect( array( 'controller' => 'users', 'action' => 'login' ) );
				}
				else {
					$this->_updateConnection();

					if( !isset( $this->request->params['isAjax'] ) ) {
						$this->_checkHabilitations();
						$this->_checkPermissions();
					}
				}
			}

			return $return;
		}

		/**
		 * Permet de savoir si la pagination progressive est définie dans le webrsa.inc:
		 * 	- pour ce contrôleur et cette action
		 * 	-  pour ce contrôleur
		 * 	- pour l'ensemble des contrôleurs
		 *
		 * @return boolean
		 */
		protected function _hasProgressivePagination() {
			// Pagination progressive pour ce contrôleur et cette action ?
			$progressivePaginate = Configure::read( "Optimisations.{$this->name}_{$this->action}.progressivePaginate" );

			// Pagination progressive pour ce contrôleur ?
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = Configure::read( "Optimisations.{$this->name}.progressivePaginate" );
			}

			// Pagination progressive en général ?
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = Configure::read( 'Optimisations.progressivePaginate' );
			}

			return $progressivePaginate;
		}

		/**
		 * Fonction utilisataire permettant de mettre en message flash le résultat des actions Save et Delete.
		 *
		 * @param string $message
		 * @param boolean $result
		 * @return void
		 */
		protected function _setFlashResult( $message, $result ) {
			$class = ( $result ? 'success' : 'error' );
			$this->Session->setFlash(
					__( "{$message}->{$class}" ), 'default', array( 'class' => $class )
			);
		}
	}
?>