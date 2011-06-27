<?php
	class GestionzonesgeosComponent extends Component
	{
		/**
		* The initialize method is called before the controller's beforeFilter method.
		*/

		public function initialize( &$controller, $settings = array() ) {
			$this->controller = &$controller;
		}

		/**
		* Retourne la liste des codes INSEE accessibles à l'utilisateur connecté,
		* soit en faisant une requête (suivant la configuration de Zonesegeographiques.CodesInsee)
		* dont les résultats sont mis en cache dans la session, soit en retournant
		* la liste mise en cache.
		*/

		public function listeCodesInsee() {
			$mesZonesGeographiques = $this->controller->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !$this->controller->Session->check( 'Cache.mesCodesInsee' ) ) {
				if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
					$listeCodesInseeLocalites = $this->controller->User->Zonegeographique->listeCodesInseeLocalites(
						$mesCodesInsee,
						$this->controller->Session->read( 'Auth.User.filtre_zone_geo' )
					);
				}
				else {
					$listeCodesInseeLocalites = $this->controller->User->Zonegeographique->listeCodesInseeLocalites(
						ClassRegistry::init( 'Adresse' )->listeCodesInsee(),
						$this->controller->Session->read( 'Auth.User.filtre_zone_geo' )
					);
				}
				$this->controller->Session->write( 'Cache.mesCodesInsee', $listeCodesInseeLocalites );

				return $listeCodesInseeLocalites;
			}
			else {
				return $this->controller->Session->read( 'Cache.mesCodesInsee' );
			}
		}

		/**
		 * Envoie à la vue de la liste des cantons si la variable CG.cantons est à vrai dans
		 * le webrsa.inc
		 */

		public function setCantonsIfConfigured( $varname = 'cantons' ) {
			if( Configure::read( 'CG.cantons' ) ) {
				if ( !$this->controller->Session->check( 'Cache.cantons' ) ) {
					$mesZonesGeographiques = $this->controller->Session->read( 'Auth.Zonegeographique' );
					$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

					$cantonModel = ClassRegistry::init( 'Canton' );
					$cantons = $cantonModel->selectList(
						$this->controller->Session->read( 'Auth.User.filtre_zone_geo' ),
						array_keys( $mesCodesInsee )
					);

					$this->controller->Session->write( 'Cache.cantons', $cantons );
					$this->controller->set( $varname, $cantons );
				}
				else {
					$this->controller->set( $varname, $this->controller->Session->read( 'Cache.cantons' ) );
				}
			}
		}

		/** *******************************************************************
			The beforeRedirect method is invoked when the controller's redirect method
			is called but before any further action. If this method returns false the
			controller will not continue on to redirect the request.
			The $url, $status and $exit variables have same meaning as for the controller's method.
		******************************************************************** */
		public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			parent::beforeRedirect( $controller, $url, $status , $exit );
		}
	}
?>