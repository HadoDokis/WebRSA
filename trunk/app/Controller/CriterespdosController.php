<?php
	/**
	 * Code source de la classe CriterespdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriterespdosController implémente un moteur de recherche par PDOs (CG 58 et 93).
	 *
	 * @package app.Controller
	 */
	class CriterespdosController extends AppController
	{
		public $name = 'Criterespdos';

		public $uses = array( 'Criterepdo', 'Personne', 'Typenotifpdo', 'Typepdo', 'Option', 'Situationpdo',
			'Propopdo', 'Decisionpdo', 'Originepdo', 'Statutpdo', 'Statutdecisionpdo', 'Situationdossierrsa'
		);

		public $helpers = array( 'Csv', 'Search', 'Default2' );

		public $components = array(
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'Search.Prg' => array( 'actions' => array( 'index', 'nouvelles' ) )
		);

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		protected function _setOptions() {
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'pieecpres', $this->Option->pieecpres() );
			$this->set( 'commission', $this->Option->commission() );
			$this->set( 'motidempdo', $this->Option->motidempdo() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'motifpdo', $this->Option->motifpdo() );
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Originepdo->find( 'list' ) );

			$this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
			$this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
			$this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );

			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'qual', $this->Option->qual() );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			$options = $this->Propopdo->allEnumLists();
			$options = Hash::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
			$this->set( compact( 'options' ) );
		}

		/**
		 * Moteur de recherche par PDOs.
		 *
		 * @return void
		 */
		public function index( ) {
			if( !empty( $this->request->data ) ) {
				$paginate = $this->Criterepdo->search(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);

				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );

				$this->paginate = $paginate;
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
				$criterespdos = $this->paginate( 'Propopdo', array(), array(), $progressivePaginate );

				$this->set( 'criterespdos', $criterespdos );
			}

			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->_setOptions();

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );
		}

		/**
		 * Moteur de recherche par nouvelles PDOs.
		 *
		 * @return void
		 */
		public function nouvelles() {
			if( !empty( $this->request->data ) ) {
				$querydata = $this->Criterepdo->listeDossierPDO(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);

				$querydata['limit'] = 10;
				$querydata = $this->_qdAddFilters( $querydata );

				$this->paginate = array( 'Personne' => $querydata );
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
				$criterespdos = $this->paginate( 'Personne', array(), array(), $progressivePaginate );

				$this->set( 'criterespdos', $criterespdos );
			}


			$this->_setOptions();
			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			// Précise les options des états de dossiers :
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatAttente()) );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );

			$this->render( 'liste' );
		}

		/**
		 * Export du tableau en CSV.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$querydata = $this->Criterepdo->search(
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);

			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$pdos = $this->Propopdo->find( 'all', $querydata );

			$this->_setOptions();

			$this->layout = '';
			$this->set( compact( 'pdos' ) );
		}
	}
?>