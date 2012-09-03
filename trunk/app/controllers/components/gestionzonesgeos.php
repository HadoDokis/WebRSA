<?php
	/**
	 * Fichier source de la classe GestionzonesgeosComponent.
	 *
	 * PHP 5.3
	 *
	 * @package       app.Controller.Component
	 */

	/**
	 * La classe GestionzonesgeosComponent fournit des méthodes permettant d'obtenir la liste des codes
	 * INSSE et des cantons auxquels l'utilisateur a droit, suivant la configuration de l'application.
	 *
	 * Ces listes sont mises en cache dans la session, car elles dépendent de l'utilisateur.
	 *
	 * @package       app.Controller.Component
	 */
	class GestionzonesgeosComponent extends Component
	{
		/**
		 * Controller using this component.
		 *
		 * @var Controller
		 */
		public $controller = null;

		/**
		 * On a besoin d'un esession.
		 *
		 * @var array
		 */
		public $components = array( 'Session' );

		/**
		 * Initialisation: sauvegarde du contrôleur dans un attribut.
		 *
		 * @param Controller $controller
		 * @param array $settings
		 */
		public function initialize( &$controller, $settings = array() ) {
			$this->controller = &$controller;
		}

		/**
		 * Retourne la liste des codes INSEE accessibles à l'utilisateur connecté, soit en faisant une requête
		 * (suivant la configuration de Zonesegeographiques.CodesInsee) dont les résultats sont mis en cache
		 * dans la session, soit en retournant la liste mise en cache.
		 *
		 * @return array
		 */
		public function listeCodesInsee() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !$this->Session->check( 'Cache.mesCodesInsee' ) ) {
				if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
					$listeCodesInseeLocalites = $this->controller->User->Zonegeographique->listeCodesInseeLocalites(
						$mesCodesInsee,
						$this->Session->read( 'Auth.User.filtre_zone_geo' )
					);
				}
				else {
					$listeCodesInseeLocalites = $this->controller->User->Zonegeographique->listeCodesInseeLocalites(
						ClassRegistry::init( 'Adresse' )->listeCodesInsee(),
						$this->Session->read( 'Auth.User.filtre_zone_geo' )
					);
				}
				$this->Session->write( 'Cache.mesCodesInsee', $listeCodesInseeLocalites );

				return $listeCodesInseeLocalites;
			}
			else {
				return $this->Session->read( 'Cache.mesCodesInsee' );
			}
		}

		/**
		 * Envoie à la vue de la liste des cantons si la variable CG.cantons est à vrai dans le webrsa.inc.
		 * Si les cantons ne sont pas utilisés, cette variable sera néanmoins envoyée, mais sa valeur sera un
		 * tableau vide.
		 *
		 * @param string $varname Le nom de la variable envoyée à la vue.
		 * @return void
		 */
		public function setCantonsIfConfigured( $varname = 'cantons' ) {
			$cantons = array();

			if( Configure::read( 'CG.cantons' ) ) {
				if ( !$this->Session->check( 'Cache.cantons' ) ) {
					$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
					$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

					$cantonModel = ClassRegistry::init( 'Canton' );
					$cantons = $cantonModel->selectList(
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						array_keys( $mesCodesInsee )
					);

					$this->Session->write( 'Cache.cantons', $cantons );
				}
				else {
					$cantons = $this->Session->read( 'Cache.cantons' );
				}
			}

			$this->controller->set( $varname, $cantons );
		}

		/**
		 * The beforeRedirect method is invoked when the controller's redirect method is called but before
		 * any further action. If this method returns false the controller will not continue on to redirect the
		 * request.
		 * The $url, $status and $exit variables have same meaning as for the controller's method.
		 *
		 * @param Controller $controller
		 * @param mixed $url
		 * @param type $status
		 * @param boolean $exit
		 */
		public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			parent::beforeRedirect( $controller, $url, $status , $exit );
		}
	}
?>