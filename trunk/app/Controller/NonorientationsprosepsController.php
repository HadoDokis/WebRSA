<?php
	/**
	 * Code source de la classe NonorientationsprosepsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe NonorientationsprosepsController ...
	 *
	 * @package app.Controller
	 */
	class NonorientationsprosepsController extends AppController
	{

		public $helpers = array( 'Default2', 'Xpaginator', 'Csv' );

		public $uses = array( 'Nonorientationproep58', 'Nonorientationproep93', 'Nonorientationproep66' );

		public $components = array( 'Search.Prg' => array( 'actions' => array( 'index' ) ) );

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			$this->modelClass = 'Nonorientationproep'.Configure::read( 'Cg.departement' );
			parent::beforeFilter();
		}

		/**
		*
		*/

		 protected function _setOptions(){
			$this->set( 'structs', $this->Nonorientationproep66->Orientstruct->Structurereferente->listOptions() );
			$this->set( 'referents', $this->Nonorientationproep66->Orientstruct->Referent->listOptions() );
			if( Configure::read( 'CG.cantons' ) ) {
				$this->loadModel( 'Canton' );
				$this->set( 'cantons', $this->Canton->selectList() );
			}
		}

		/**
		*
		*/

//		public function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Search.Prg' => array( 'actions' => array( 'index' ) ) ) );
//			parent::__construct();
//		}

		/**
		*
		*/

		public function index() {
			$cohorte = array();
			if ( !empty( $this->request->data ) ) {
				$filtre = $this->request->data['Filtre'];
				if( !empty( $filtre['referent_id'] )) {
					$referentId = suffix( $filtre['referent_id'] );
					$filtre['referent_id'] = $referentId;
				}

				if ( isset( $this->request->data['Nonorientationproep'] ) ) {
					$this->{$this->modelClass}->begin();
					$success = $this->{$this->modelClass}->saveCohorte( $this->request->data );
					$this->_setFlashResult( 'Save', $success );
					if ( $success ) {
						$this->{$this->modelClass}->commit();
						$this->redirect( Set::merge( array( 'action' => 'index' ), Set::flatten( array( 'Filtre' => $this->request->data['Filtre'] ), '__' ) ) );
					}
					else {
						$this->{$this->modelClass}->rollback();
					}
				}

				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				$paginate = $this->{$this->modelClass}->searchNonReoriente(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					array( 'Filtre' => $filtre )
				);

				$paginate['limit'] = 10;
				$this->paginate = $paginate;
				$this->{$this->modelClass}->Orientstruct->forceVirtualFields = true;
				$cohorte = $this->paginate( $this->{$this->modelClass}->Orientstruct );
			}
			$this->set( 'nbmoisnonreorientation', array( 0 => 'Aujourd\'hui', 6 => '6 mois', 12 => '12 mois', 24 => '24 mois' ) );
			$this->_setOptions();
			$this->set( compact( 'cohorte' ) );
		}


		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );



			$queryData = $this->{$this->modelClass}->searchNonReoriente( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->request->params['named'], '__' ) );
			unset( $queryData['limit'] );

			$orientsstructs = $this->{$this->modelClass}->Orientstruct->find( 'all', $queryData );
// debug($orientsstructs);
// die();
			$this->layout = ''; // FIXME ?
			$this->set( compact( 'orientsstructs' ) );

		}

	}

?>