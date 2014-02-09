<?php
	// FIXME: dans /cohortesnonorientes66/isemploi, on n'a pas la clé Search.Situationdossierrsa.etatdosrsa_choice mais Situationdossierrsa.etatdosrsa_choice dans le formulaire
	/**
	 * Code source de la classe SearchPrgComponent.
	 *
	 * PHP 5.3
	 *
	 * @package Search
	 * @subpackage Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SearchPrgComponent gère le POST/redirect/GET pour certaines actions du
	 * contrôleur, avec la possibilité de filtrer les parties du POST à mettre
	 * dans l'URL ou dans la Session.
	 *
	 * <pre>
	 * public $components = array(
	 * 	'Search.SearchPrg' => array(
	 * 		'actions' => array( 'index' => array( 'filter' => 'Search' ) ),
	 *	)
	 * );
	 * </pre>
	 *
	 * @package Search
	 * @subpackage Controller.Component
	 */
	class SearchPrgComponent extends Component
	{
		/**
		 * Nom du component.
		 *
		 * @var string
		 */
		public $name = 'SearchPrg';

		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $controller = null;

		/**
		 * Components utilisés par ce component-ci.
		 *
		 * @var array
		 */
		public $components = array( 'Session', 'RequestHandler' );

		/**
		 * Called before the Controller::beforeFilter().
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @param array $settings
		 * @return void
		 */
		public function initialize( Controller $controller ) {
			$this->controller = $controller;

			$this->settings = ( isset( $this->settings['actions'] ) ? (array)$this->settings['actions'] : array() );
			$this->settings = Set::normalize( $this->settings );
		}

		/**
		 * FIXME: ne fonctionne pas bien
		 *
		 * @param array $params
		 * @param array $forbiddenlist
		 * @return array
		 */
		protected function _urlencodeParams( $params, $forbiddenlist = array( '?', '/', ':', '&' ) ) {
			foreach( $params as $key => $param ) {
				foreach( $forbiddenlist as $forbidden ) {
					$param = str_replace( $forbidden, ' ', $param );
				}

				if( !is_array( $param ) ) {
					$params[$key] = urlencode( $param );
				}
				else {
					$params[$key] = $param;
				}
			}

			return $params;
		}

		/**
		 * Called after the Controller::beforeFilter() and before the controller action
		 *
		 * @param Controller $controller Controller with components to startup
		 * @return void
		 */
		public function startup( Controller $controller ) {
			if( in_array( $controller->action, array_keys( $this->settings ) ) ) {
				if( !empty( $controller->request->params['form'] ) ) {
					return;
				}

				if( $controller->request->is( 'post' ) ) {
					$params = $controller->request->data;

					if( isset( $this->settings[$controller->action]['filter'] ) ) {
						$key = $this->settings[$controller->action]['filter'];
						$sessionParams = $params;
						$params = array( $key => ( isset( $params[$key] ) ? $params[$key] : array( ) ) );
						unset( $sessionParams[$key] );

						if( !empty( $sessionParams ) ) {
							unset( $sessionParams['sessionKey'] );
							$sessionKey = sha1( implode( '/', Hash::flatten( ( empty( $sessionParams ) ? array( ) : $sessionParams ), '__' ) ) );
							$this->Session->write( "{$this->name}.{$controller->name}__{$controller->action}.{$sessionKey}", $sessionParams );
							$params['sessionKey'] = $sessionKey;
						}
					}

					$params = Hash::flatten( $params, '__' );
					$params = Set::merge( $controller->request->params['named'], $params );
					$params = $this->_urlencodeParams( $params );

					$redirect = array_merge( array( 'action' => $controller->action ), $params );
					$controller->redirect( $redirect );
				}
				else if( $controller->request->is( 'get' ) ) {
					$controller->request->data = Hash::expand( array_map( 'urldecode', $controller->request->params['named'] ), '__' );

					if( isset( $controller->request->params['named']['sessionKey'] ) ) {
						$sessionParams = (array)$this->Session->read( "{$this->name}.{$controller->name}__{$controller->action}.{$controller->request->params['named']['sessionKey']}" );

						$this->Session->delete( "{$this->name}.{$controller->name}__{$controller->action}.{$controller->request->params['named']['sessionKey']}" );
						$controller->request->data = Set::merge( $controller->request->data, $sessionParams );
					}
				}
			}
		}
	}
?>