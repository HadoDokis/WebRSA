<?php
	/**
	 * Fichier source de la classe Criteresdossierscovs58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Moteur de recherche de dossiers COV (CG 58).
	 *
	 * @package app.Controller
	 */
	class Criteresdossierscovs58Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Search' );
		public $uses = array(  'Criteredossiercov58', 'Dossiercov58' );
		public $components = array( 'Gestionzonesgeos', 'Search.Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		*
		*/
		public function _setOptions() {
			$this->set( 'options', $this->Dossiercov58->Passagecov58->allEnumLists() );
			$this->set( 'themes', $this->Dossiercov58->Themecov58->find( 'list' ) );
			$sitescovs58 = $this->Dossiercov58->Passagecov58->Cov58->Sitecov58->find( 'list', array( 'fields' => array( 'name' ) ) );
			$this->set( compact( 'sitescovs58' ) );
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

				$queryData = $this->Criteredossiercov58->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$data
				);
				$queryData['limit'] = 10;
				$this->paginate = $this->_qdAddFilters( $queryData );


				$forceVirtualFields = $this->Dossiercov58->forceVirtualFields;
				$this->Dossiercov58->forceVirtualFields = true;
				$dossierscovs58 = $this->paginate( $this->Dossiercov58 );
				$this->Dossiercov58->forceVirtualFields = $forceVirtualFields;

				foreach( $dossierscovs58 as $key => $dossiercov58 ) {
					$dossierscovs58[$key]['Personne']['nom_complet'] = implode(
						' ',
						array(
							@$dossierscovs58[$key]['Personne']['qual'],
							@$dossierscovs58[$key]['Personne']['nom'],
							@$dossierscovs58[$key]['Personne']['prenom']
						)
					);
				}

				$this->set( 'dossierscovs58', $dossierscovs58 );
			}
			$this->_setOptions();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->render( 'index' );
		}
	}
?>