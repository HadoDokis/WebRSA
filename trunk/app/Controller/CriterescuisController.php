<?php
	/**
	 * Code source de la classe CriterescuisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriterescuisController implémente un moteur de recherche par CUIs (CG 58, 66 et 93).
	 *
	 * @package app.controllers
	 */
	class CriterescuisController extends AppController
	{
		public $name = 'Criterescuis';

		public $uses = array( 'Criterecui', 'Cui', 'Option', 'Structurereferente' );

		public $helpers = array( 'Csv', 'Ajax', 'Search' );

		public $components = array(
			'Gestionzonesgeos',
			'Prg2' => array( 'actions' => array( 'index' ) )
		);

		public $aucunDroit = array( 'exportcsv' );

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions(){
			$options = array();
			$struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$this->set( 'struct', $struct );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$qual = $this->Option->qual();
			$this->set( 'qual', $qual );
			$options = $this->Cui->allEnumLists();
			$this->set( 'options', $options );
		}

		/**
		 * Moteur de recherche par CUI.
		 *
		 * @return void
		 */
		public function index() {
			if( !empty( $this->request->data ) ) {
				$paginate = $this->Criterecui->search(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);
				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );

				$this->paginate = $paginate;
				$criterescuis = $this->paginate( 'Cui' );

				$this->set( 'criterescuis', $criterescuis );
			}
			$this->_setOptions();
			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
		}

		/**
		 * Export du tableau en CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$querydata = $this->Criterecui->search(
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->request->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$cuis = $this->Cui->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'cuis' ) );
		}
	}
?>