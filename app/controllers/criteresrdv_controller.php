<?php
	/**
	 * Code source de la classe CriteresrdvController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriteresrdvController implémente un moteur de recherche par rendez-vous (CG 58, 66 et 93).
	 *
	 * @package app.controllers
	 */
	class CriteresrdvController extends AppController
	{
		public $name = 'Criteresrdv';

		public $uses = array( 'Critererdv', 'Rendezvous', 'Option', 'Typerdv', 'Referent' );

		public $helpers = array( 'Csv', 'Ajax', 'Paginator', 'Search' );

		public $components = array(
			'Gestionzonesgeos',
			'Prg2' => array( 'actions' => array( 'index' ) )
		);

		/**
		 * Changement du temps d'exécution maximum et de la quantité de mémoire maximale.
		 *
		 * @return void
		 */
		public function beforeFilter() {
			ini_set( 'max_execution_time', 0 );
			ini_set( 'memory_limit', '128M' );
			parent::beforeFilter();
		}

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );
			$this->set( 'struct', $this->Structurereferente->listOptions() );
			$typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );
			$this->set( 'permanences', $this->Permanence->find( 'list' ) );
			$this->set( 'referents', $this->Referent->find( 'list' ) );

			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'rolepers', $this->Option->rolepers() );
		}

		/**
		 * Moteur de recherche par rendez-vous.
		 *
		 * @return void
		 */
		public function index() {
			if( !empty( $this->data ) ) {
				$querydata = $this->Critererdv->search(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->data
				);

				$querydata['limit'] = 10;
				$querydata = $this->_qdAddFilters( $querydata );

				$this->paginate = array( 'Rendezvous' => $querydata );
				$rdvs = $this->paginate( 'Rendezvous' );

				$this->set( 'rdvs', $rdvs );
			}

			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->_setOptions();
		}

		/**
		 * Moteur de recherche par nouvelles PDOs.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$querydata = $this->Critererdv->search(
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' )
			);

			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$rdvs = $this->Rendezvous->find( 'all', $querydata );

			// Population du select référents liés aux structures
			$structurereferente_id = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
			$referents = $this->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );

			$this->layout = '';
			$this->_setOptions();
			$this->set( compact( 'rdvs' ) );
		}
	}
?>