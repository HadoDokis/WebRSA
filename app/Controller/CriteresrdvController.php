<?php
	/**
	 * Code source de la classe CriteresrdvController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriteresrdvController implémente un moteur de recherche par rendez-vous (CG 58, 66 et 93).
	 *
	 * @package app.Controller
	 */
	class CriteresrdvController extends AppController
	{
		public $name = 'Criteresrdv';

		public $uses = array( 'Critererdv', 'Rendezvous', 'Option' );

		public $helpers = array( 'Csv', 'Paginator', 'Search' );

		public $components = array(
			'Gestionzonesgeos',
			'Search.Prg' => array( 'actions' => array( 'index' ) ),
			'Workflowscers93',
			'InsertionsAllocataires'
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
			$this->set( 'statutrdv', $this->Rendezvous->Statutrdv->find( 'list' ) );
// 			$this->set( 'struct', $this->Rendezvous->Structurereferente->listOptions() );
			
			$this->set( 'struct', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			
			$typerdv = $this->Rendezvous->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );
			$this->set( 'permanences', $this->Rendezvous->Permanence->find( 'list' ) );
			$this->set( 'referents', $this->Rendezvous->Referent->listOptions() );

			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'rolepers', $this->Option->rolepers() );
		}

		/**
		 * Moteur de recherche par rendez-vous.
		 *
		 * @return void
		 */
		public function index() {
			// On conditionne l'affichage des RDVs selon la structure référente liée au RDV
			// Si la structure de l'utilisateur connecté est différente de celle du RDV, on ne l'affiche pas.
			$conditionStructure = array();
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );
				if( !is_null( $structurereferente_id ) ) {
					$conditionStructure = array( 'Rendezvous.structurereferente_id' => $structurereferente_id );
				}
			}
			
			if( !empty( $this->request->data ) ) {
				$querydata = $this->Critererdv->search(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data,
					$conditionStructure //FIXME
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
				Hash::expand( $this->request->params['named'], '__' )
			);

			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$rdvs = $this->Rendezvous->find( 'all', $querydata );

			// Population du select référents liés aux structures
			$structurereferente_id = Set::classicExtract( $this->request->data, 'Critererdv.structurereferente_id' );
			$referents = $this->Rendezvous->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );

			$this->layout = '';
			$this->_setOptions();
			$this->set( compact( 'rdvs' ) );
		}
	}
?>