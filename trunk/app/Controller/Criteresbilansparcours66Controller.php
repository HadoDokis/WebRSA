<?php
	/**
	 * Fichier source de la classe Criteresbilansparcours66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Moteur de recherche de bilans de parcours (CG 66).
	 *
	 * @package app.Controller
	 */
	class Criteresbilansparcours66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Search' );
		public $uses = array(  'Criterebilanparcours66', 'Bilanparcours66', 'Option', 'Referent' );
		public $components = array( 'Gestionzonesgeos', 'Search.Prg' => array( 'actions' => array( 'index' ) ) );
		public $aucunDroit = array( 'exportcsv' );

		/**
		*
		*/
		public function _setOptions() {
			$this->set( 'options', $this->Bilanparcours66->allEnumLists() );
			$this->set( 'struct', $this->Bilanparcours66->Referent->Structurereferente->listOptions() );
			$this->set( 'referents', $this->Bilanparcours66->Referent->listOptions() );
		}

		/**
		*
		*/

		public function index() {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->request->data ) ) {
				$data = $this->request->data;

				if( !empty( $data['Bilanparcours66']['referent_id'] )) {
					$referentId = suffix( $data['Bilanparcours66']['referent_id'] );
					$data['Bilanparcours66']['referent_id'] = $referentId;
				}

				$queryData = $this->Criterebilanparcours66->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;

				$bilansparcours66 = $this->paginate( $this->Bilanparcours66 );

				$this->set( 'bilansparcours66', $bilansparcours66 );
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$this->_setOptions();
			$this->render( 'index' );
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Criterebilanparcours66->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Hash::expand( $this->request->params['named'], '__' ) );
			unset( $querydata['limit'] );
			$bilansparcours66 = $this->Bilanparcours66->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = ''; // FIXME ?
			$this->set( compact( 'bilansparcours66' ) );
		}
	}
?>