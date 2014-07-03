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

		public $helpers = array(
			'Allocataires',
			'Csv',
			'Default2',
			'Xpaginator',
		);

		public $uses = array( 'Nonorientationproep58', 'Nonorientationproep93', 'Nonorientationproep66' );

		public $components = array(
			'Allocataires',
			'InsertionsAllocataires',
			'Search.SearchPrg' => array( 'actions' => array( 'index' ) )
		);

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
						$this->redirect( Set::merge( array( 'action' => 'index' ), Hash::flatten( array( 'Filtre' => $this->request->data['Filtre'] ), '__' ) ) );
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

				$paginate['conditions'][] = WebrsaPermissions::conditionsDossier();

				if( Configure::read( 'Cg.departement' ) == 58 ) {
					$filtre = (array)Hash::get( $this->request->data, 'Filtre' );
					$paginate = $this->Allocataires->completeSearchQuery( $paginate );
					$paginate = ClassRegistry::init( 'Allocataire' )->searchConditions( $paginate, $filtre );
				}

				$this->paginate = $paginate;
				$this->{$this->modelClass}->Orientstruct->forceVirtualFields = true;
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );

				$cohorte = $this->paginate( $this->{$this->modelClass}->Orientstruct, array(), array(), $progressivePaginate );
			}
			$this->set( 'nbmoisnonreorientation', array( 0 => 'Aujourd\'hui', 6 => '6 mois', 12 => '12 mois', 24 => '24 mois' ) );
			$this->_setOptions();
			$this->set( 'options', $this->Allocataires->options() );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );

			$this->set( compact( 'cohorte' ) );
		}


		/**
		* Export du tableau en CSV
		*/
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$named = Hash::expand( $this->request->params['named'], '__' );

			$queryData = $this->{$this->modelClass}->searchNonReoriente( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $named );
			$queryData['conditions'][] = WebrsaPermissions::conditionsDossier();

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$filtre = (array)Hash::get( $named, 'Filtre' );
				$queryData = $this->Allocataires->completeSearchQuery( $queryData );
				$queryData = ClassRegistry::init( 'Allocataire' )->searchConditions( $queryData, $filtre );
			}

			unset( $queryData['limit'] );

			$orientsstructs = $this->{$this->modelClass}->Orientstruct->find( 'all', $queryData );

			$this->layout = null;
			$this->set( compact( 'orientsstructs' ) );

		}
	}
?>