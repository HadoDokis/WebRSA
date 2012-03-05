<?php
	/**
	 * Cette classe correspond au patron de conception "fabrique" (Factory method pattern) et permet d'attacher
	 * le bon behavior suivant la configuration utilisée.
	 *
	 * PHP 5.3
	 *
	 * @package       app.models.behaviors
	 *
	 *  Exemple:
	 *	$this->User->Behaviors->attach( 'Gedooo.Gedooo' );
	 *	$pdf = $this->User->ged( array( 'Personne' => array( 'nom' => 'Buffin' ) ), 'APRE/apre66.odt' );
	 *	$this->Gedooo->sendPdfContentToClient( $pdf, 'foo.pdf' );
	 *	return;
	 *
	 * TODO: ajouter une version check, la conf (les define), les fichiers vendors
	 */
	if( !defined( 'GEDOOO_PLUGIN_DIR' ) ) {
		define( 'GEDOOO_PLUGIN_DIR', dirname( __FILE__ ).DS.'..'.DS.'..'.DS );
	}
	if( !defined( 'GEDOOO_WSDL' ) ) {
		define( 'GEDOOO_WSDL', Configure::read( 'Gedooo.wsdl' ) );
	}
	if( !defined( 'GEDOOO_TEST_FILE' ) ) {
		define( 'GEDOOO_TEST_FILE', GEDOOO_PLUGIN_DIR.'vendors'.DS.'modelesodt'.DS.'test_gedooo.odt' );
	}

	class GedoooBehavior extends ModelBehavior
	{
		protected $_gedoooBehavior = null;

		/**
		 * Setup this behavior with the specified configuration settings.
		 *
		 * @param Model $Model Model using this behavior
		 * @param array $config Configuration settings for $model
		 * @return void
		 */
		public function setup( &$Model, $config = array() ) {
			$method = Configure::read( 'Gedooo.method' );

			switch( $method ) {
				case 'classic':
					$this->_gedoooBehavior = 'GedoooClassic';
					break;
				case 'cloudooo':
					$this->_gedoooBehavior = 'GedoooCloudooo';
					break;
				case 'unoconv':
					$this->_gedoooBehavior = 'GedoooUnoconv';
					break;
				default:
					trigger_error( "Paramétrage incorrect: la méthode de Gedooo '{$method}' n'existe pas.", E_USER_WARNING );
			}

			if( !empty( $this->_gedoooBehavior ) ) {
				if( !defined( 'PHPGEDOOO_DIR' ) ) {
					if( $method == 'classic' ) {
						define( 'PHPGEDOOO_DIR', GEDOOO_PLUGIN_DIR.'vendors'.DS.'phpgedooo_ancien'.DS );
					}
					else {
						define( 'PHPGEDOOO_DIR', GEDOOO_PLUGIN_DIR.'vendors'.DS.'phpgedooo_nouveau'.DS );
					}
				}

				if( !$Model->Behaviors->attached( "Gedooo.{$this->_gedoooBehavior}" ) ) {
					$Model->Behaviors->attach( "Gedooo.{$this->_gedoooBehavior}" );
				}
			}
		}

		/**
		 * Clean up any initialization this behavior has done on a model.  Called when a behavior is dynamically
		 * detached from a model using Model::detach().
		 *
		 * @param AppModel $Model Model using this behavior
		 * @return void
		 * @see BehaviorCollection::detach()
		 */
		public function cleanup( &$Model ) {
			if( !empty( $this->_gedoooBehavior ) && $Model->Behaviors->attached( "Gedooo.{$this->_gedoooBehavior}" ) ) {
				$Model->Behaviors->detach( "Gedooo.{$this->_gedoooBehavior}" );
			}

			parent::cleanup( $Model );
		}

		/**
		 * Retourne la liste des clés de configuration pour le plugin Gedooo.
		 *
		 * @return array
		 */
		public function gedConfigureKeys( &$Model ) {
			$keys = array( 'Gedooo.method' => 'string' );

			if( !is_null( $this->_gedoooBehavior ) ) {
				$keys = array_merge(
					$keys,
					$Model->Behaviors->{$this->_gedoooBehavior}->gedConfigureKeys( $Model )
				);
			}

			return $keys;
		}
	}
?>