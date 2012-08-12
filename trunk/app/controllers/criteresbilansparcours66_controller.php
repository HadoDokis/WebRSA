<?php
	/**
	* Moteur de recherche pour les bilans de parcours du CG 66
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Criteresbilansparcours66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Ajax', 'Locale', 'Csv' );
		public $uses = array(  'Criterebilanparcours66', 'Bilanparcours66', 'Option', 'Referent' );
		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );
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
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
				$data = $this->data;

				if( !empty( $data['Bilanparcours66']['referent_id'] )) {
					$referentId = suffix( $data['Bilanparcours66']['referent_id'] );
					$data['Bilanparcours66']['referent_id'] = $referentId;
				}

				$queryData = $this->Criterebilanparcours66->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;
				$this->Bilanparcours66->forceVirtualFields = true;
				$bilansparcours66 = $this->paginate( $this->Bilanparcours66 );

// 				foreach( $bilansparcours66 as $key => $bilanparcours66 ) {
// 					$bilansparcours66[$key]['Personne']['nom_complet'] = implode(
// 						' ',
// 						array(
// 							@$bilansparcours66[$key]['Personne']['qual'],
// 							@$bilansparcours66[$key]['Personne']['nom'],
// 							@$bilansparcours66[$key]['Personne']['prenom']
// 						)
// 					);
// 					$bilansparcours66[$key]['Referent']['nom_complet'] = implode(
// 						' ',
// 						array(
// 							@$bilansparcours66[$key]['Referent']['qual'],
// 							@$bilansparcours66[$key]['Referent']['nom'],
// 							@$bilansparcours66[$key]['Referent']['prenom']
// 						)
// 					);
// 
// 				}

				$this->set( 'bilansparcours66', $bilansparcours66 );
			}

			if( Configure::read( 'CG.cantons' ) ) {
				$this->loadModel( 'Canton' );
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Zonegeographique' )->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Adresse' )->listeCodesInsee() );
			}

			$this->_setOptions();
			$this->render( null, null, 'index' );
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Criterebilanparcours66->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ) );
			unset( $querydata['limit'] );
			$bilansparcours66 = $this->Bilanparcours66->find( 'all', $querydata );

			foreach( $bilansparcours66 as $key => $bilanparcours66 ) {
				$bilansparcours66[$key]['Personne']['nom_complet'] = implode(
					' ',
					array(
						@$bilansparcours66[$key]['Personne']['qual'],
						@$bilansparcours66[$key]['Personne']['nom'],
						@$bilansparcours66[$key]['Personne']['prenom']
					)
				);
				$bilansparcours66[$key]['Referent']['nom_complet'] = implode(
					' ',
					array(
						@$bilansparcours66[$key]['Referent']['qual'],
						@$bilansparcours66[$key]['Referent']['nom'],
						@$bilansparcours66[$key]['Referent']['prenom']
					)
				);

			}

			$this->_setOptions();
			$this->layout = ''; // FIXME ?
			$this->set( compact( 'bilansparcours66' ) );
		}
	}
?>