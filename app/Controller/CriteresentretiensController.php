<?php
	/**
	 * Code source de la classe CriteresentretiensController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriteresentretiensController ...
	 *
	 * @package app.Controller
	 */
	class CriteresentretiensController extends AppController
	{
		public $name = 'Criteresentretiens';

		public $uses = array( 'Critereentretien', 'Entretien', 'Option' );
		public $helpers = array( 'Csv', 'Default2', 'Search' );
		public $components = array(
			'Gestionzonesgeos',
			'Search.SearchPrg' => array( 'actions' => array( 'index' ) ),
			'InsertionsAllocataires'
		);

		/**
		 *
		 */
		public function _setOptions() {
			$this->set( 'options', (array)Hash::get( $this->Entretien->enums(), 'Entretien' ) );
// 			$this->set( 'structs', $this->Entretien->Structurereferente->listOptions() );
			$this->set( 'structs', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referents', $this->Entretien->Referent->listOptions() );
            $this->set( 'typevoie', $this->Option->typevoie() );
		}

		/**
		 *
		 */
		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->request->data ) ) {

				if( !empty( $this->request->data['Entretien']['referent_id'] )) {
					$referentId = suffix( $this->request->data['Entretien']['referent_id'] );
					$this->request->data['Entretien']['referent_id'] = $referentId;
				}

				$paginate = $this->Critereentretien->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data,
					false
				);

				$paginate = $this->_qdAddFilters( $paginate );
				$paginate['conditions'][] = WebrsaPermissions::conditionsDossier();
				$paginate['limit'] = 10;

				$this->paginate = $paginate;
				$progressivePaginate = !Hash::get( $this->request->data, 'Pagination.nombre_total' );
				$entretiens = $this->paginate( 'Entretien', array(), array(), $progressivePaginate );

				$this->set( 'entretiens', $entretiens );
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->_setOptions();

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true, 'conditions' => array( 'orientation' => 'O' ) ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Critereentretien->search(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' ),
				false
			);

			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();

			$entretiens = $this->Entretien->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'entretiens' ) );
			$this->_setOptions();
		}
	}
?>