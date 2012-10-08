<?php
	/**
	 * Code source de la classe FiltresdefautComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers.components
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe FiltresdefautComponent permet de mettre des valeurs par défaut
	 * dans l'attribut $this->request->data du contrôleur auquel il est attaché
	 * lorsque l'on appelle certaines actions du contrôleur.
	 *
	 * Utilisation dans le contrôleur:
	 * <pre>
	 * public $components = array(
	 *	'Filtresdefaut' => array( 'index' )
	 * );
	 * </pre>
	 *
	 * Utilisation dans le fichier de configuration.
	 * <pre>
	 * Configure::write(
	 * 	'Filtresdefaut.Dossiers_index',
	 * 	array(
	 * 		'Dossier.dernier' => '1',
	 * 		'Dossier.dtdemrsa_from' => '1979-01-24',
	 * 		'Dossier.dtdemrsa_to' => date( 'Y-m-d', strtotime( '+1 day') ),
	 * 		'Situationdossierrsa.etatdosrsa_choice' => '1',
	 * 		'Situationdossierrsa.etatdosrsa' => array( 2, 3, 4 )
	 * 	)
	 * );
	 * </pre>
	 *
	 * @package app.controllers.components
	 */
	class FiltresdefautComponent extends Component
	{
		/**
		 * Le nom du component.
		 *
		 * @var string
		 */
		public $name = 'Filtresdefaut';

		/**
		 * Le contrôleur auquel le component est attaché.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres du component.
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Initialisation.
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 */
		public function initialize( Controller $controller ) {
			$settings = $this->settings;
			$this->settings = (array)$settings;
			$this->Controller = $controller;
		}

		/**
		 * Le contrôleur est-il dans un état qui demande une fusion avec les
		 * données par défaut ?
		 *
		 * @return boolean
		 */
		public function needsMerge() {
			return in_array( $this->Controller->action, $this->settings )
					&& empty( $this->Controller->data );
		}

		/**
		 * Retournee la clé sous laquelle la variable de confiuration sera lue
		 * pour le contrôleur auquel le component est attaché.
		 *
		 * @return string
		 */
		public function configureKey() {
			return "{$this->name}.{$this->Controller->name}_{$this->Controller->action}";
		}

		/**
		 * Fusion des données post de la requête au contrôleur et de la configuration
		 * stockée dans "{$this->name}.{$this->Controller->name}_{$this->Controller->action}".
		 *
		 * Les données du contrôleur écraseront les données de la configuration.
		 *
		 * @return void
		 */
		public function merge() {
			$filtresdefaut = (array)Configure::read( $this->configureKey() );
			if( !empty( $filtresdefaut ) ) {
				$filtresdefaut = Xset::bump( $filtresdefaut, '__' );
				$this->Controller->data = Set::merge( $filtresdefaut, $this->Controller->data );
			}
		}

		/**
		 * Fusion éventuelle des données du contrôleur, après l'action de
		 * celui-ci et juste avant le rendu de la vue.
		 *
		 * @param Controller $controller Le contrôleur dans lequel fusionner les données
		 * @return void
		 */
		public function beforeRender( Controller $controller ) {
			$this->Controller = $controller;

			if( $this->needsMerge() ) {
				$this->merge();
			}
		}
	}
?>