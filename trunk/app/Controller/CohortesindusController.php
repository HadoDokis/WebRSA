<?php
	/**
	 * Code source de la classe CohortesindusController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import('Sanitize');

	/**
	 * La classe CohortesindusController implémente un moteur de rechrche par indus.
	 *
	 * @deprecated since version 3.0.0
	 * @see IndusController::search() et IndusController::exportcsv()
	 *
	 * @package app.Controller
	 */
	class CohortesindusController extends AppController
	{
		public $name = 'Cohortesindus';

		public $uses = array( 'Cohorteindu', 'Option', 'Structurereferente', 'Dossier', 'Situationdossierrsa' );

		public $helpers = array( 'Csv', 'Paginator', 'Locale', 'Search' );

		public $paginate = array(
			'limit' => 20,
		);

		public $components = array(
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'InsertionsBeneficiaires',
			'Search.SearchPrg' => array( 'actions' => array( 'index' ) )
		);

		/**
		 * Envoi des options communes dans les vues.
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$sr = $this->Structurereferente->find(
				'list',
				array(
					'fields' => array(
						'Structurereferente.lib_struc'
					),
				)
			);
			$this->set( 'sr', $sr );

			$this->set( 'natpfcre', $this->Option->natpfcre( 'autreannulation' ) );
			$this->set( 'typeparte', $this->Option->typeparte() );
			//$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatOuvert()) );
			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'type_allocation', $this->Option->type_allocation() );
			$this->set( 'dif', $this->Option->dif() );
			$this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'qual', $this->Option->qual() );
		}

		/**
		 * Moteur de recherche par indus.
		 *
		 * @return void
		 */
		public function index() {
			$comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' );

			$cmp = Set::extract( $this->request->data, 'Cohorteindu.compare' );
			$this->assert( empty( $cmp ) || in_array( $cmp, array_keys( $comparators ) ), 'invalidParameter' );

			if( !empty( $this->request->data ) ) {
				$paginate = $this->Cohorteindu->search(
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);
				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );
				$paginate['conditions'][] = WebrsaPermissions::conditionsDossier();

				$this->paginate = $paginate;
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
				$cohorteindu = $this->paginate( 'Dossier', array(), array(), $progressivePaginate );

				$this->set( 'cohorteindu', $cohorteindu );
			}

			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->set( 'comparators', $comparators );

			$this->set( 'structuresreferentesparcours', $this->InsertionsBeneficiaires->structuresreferentes( array( 'type' => 'optgroup', 'conditions' => array( 'Structurereferente.orientation' => 'O' ) + $this->InsertionsBeneficiaires->conditions['structuresreferentes'], 'prefix' => false ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );
		}

		/**
		 * Export CSV des enregistrements renvoyés par le moteur de recherche.
		 *
		 *@return void
		 */
		public function exportcsv(){
			$querydata = $this->Cohorteindu->search(
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);
			$querydata = $this->_qdAddFilters( $querydata );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();

			unset( $querydata['limit'] );
			$indus = $this->Dossier->find( 'all', $querydata );


			$this->layout = '';
			$this->set( compact( 'headers', 'indus' ) );
		}
	}
?>