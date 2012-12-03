<?php
	/**
	 * Code source de la classe DossiersMenusComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe DossiersMenusComponent.
	 *
	 * @package app.Controller.Component
	 */
	class DossiersMenusComponent extends Component
	{
		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array( 'Session' );

		/**
		 * Appelée avant Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 */
		public function initialize( Controller $controller ) {
			parent::initialize( $controller );
			$this->Controller = $controller;
		}

		/**
		 *
		 * FIXME: en fonction des CG lorsqu'on tape dans l'URL
		 *
		 * @param type $params
		 * @return type
		 * @throws Error403Exception
		 */
		public function dossierMenu( $params ) {
			$dossierMenu = $this->Controller->Dossier->menu(
				$params,
				$this->Controller->Jetons2->sqLocked( 'Dossier', 'locked' )
			);

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$filtre_zone_geo = $this->Session->read( 'Auth.User.filtre_zone_geo' );

				if( $filtre_zone_geo === true ) {
					$typesActions = ( isset( $this->Controller->typesActions ) ? $this->Controller->typesActions : array() );
					if( !isset( $typesActions['read'] ) ) {
						$typesActions['read'] = array();
					}
					if( !isset( $typesActions['write'] ) ) {
						$typesActions['write'] = array();
					}

					$codesAdresses = Hash::extract( $dossierMenu, 'Adressefoyer.{n}.codeinsee' );

					//FIXME: écriture seulement
					if( in_array( $this->Controller->action, $typesActions['write'] ) && !in_array( $codesAdresses[0], $this->Session->read( 'Auth.Zonegeographique' ) ) ) {
						throw new Error403Exception( 'FIXME' );
					}
					// Ici, c'est de la visualisation
					else if( in_array( $this->Controller->action, $typesActions['read'] ) ) {
						$inter = array_intersect( $codesAdresses, $this->Session->read( 'Auth.Zonegeographique' ) );
						if( empty( $inter ) ) {
							throw new Error403Exception( 'FIXME' );
						}
					}
				}
			}

			return $dossierMenu;
		}
	}
?>