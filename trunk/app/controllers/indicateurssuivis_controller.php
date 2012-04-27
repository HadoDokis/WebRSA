<?php
	class IndicateurssuivisController extends AppController
	{
		public $name = 'Indicateurssuivis';
		public $helpers = array( 'Xform', 'Xhtml', 'Default2', 'Search', 'Csv' );
		public $uses = array('Dossier','Option','Structurereferente','Referent', 'Indicateursuivi', 'Dossierep', 'Foyer', 'Personne');
		public $components = array( 'Gestionzonesgeos', 'Prg' => array( 'actions' => array( 'index' ) ) );

		protected function _setOptions() {
			$natpfsSocle = Configure::read( 'Detailcalculdroitrsa.natpf.socle' );
			$this->set( 'natpf', $this->Option->natpf( $natpfsSocle ) );
			$this->Gestionzonesgeos->setCantonsIfConfigured();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );			
			$this->set( 'structs', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
			$this->set( 'referents', $this->Referent->referentsListe() );	
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'options', $this->Dossierep->allEnumLists());
		}
		
		
		public function index() {
			$this->_setOptions();
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
			
			//debug($this->data);exit;
			if( !empty( $this->data ) ) {
				$this->paginate = $this->Indicateursuivi->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->data				
				);
				$indicateurs = $this->paginate( 'Dossier' );
				
				foreach($indicateurs as $key => $value ) {
					//Conjoint :
					$bindPrestation = $this->Personne->hasOne['Prestation'];
					$this->Personne->unbindModelAll();
					$this->Personne->bindModel( array( 'hasOne' => array('Prestation' => $bindPrestation ) ) );
					$conjoint = $this->Personne->find('first', array(
						'fields' => array('Personne.qual','Personne.nom', 'Personne.prenom'),
						'conditions' => array( 
							'Personne.foyer_id' => $value['Foyer']['id'],
							'Prestation.rolepers' => 'CJT'
						) 
					));
					$indicateurs[$key]['Personne']['qualcjt'] = !empty($conjoint['Personne']['qual']) ? $conjoint['Personne']['qual'] : '';
					$indicateurs[$key]['Personne']['prenomcjt'] = !empty($conjoint['Personne']['prenom']) ? $conjoint['Personne']['prenom'] : '';
					$indicateurs[$key]['Personne']['nomcjt'] = !empty($conjoint['Personne']['nom']) ? $conjoint['Personne']['nom'] : '';
				}
				$this->set('indicateurs', $indicateurs);
			}
			
		}
		
		
		public function exportcsv() {
			$this->_setOptions();
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
		
			$querydata = $this->Indicateursuivi->search(
				$mesCodesInsee, 
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				array_multisize( $this->params['named'] ) 
			);
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );
		
			$indicateurs = $this->Dossier->find( 'all', $querydata );
			foreach($indicateurs as $key => $value ) {
				//Conjoint :
				$bindPrestation = $this->Personne->hasOne['Prestation'];
				$this->Personne->unbindModelAll();
				$this->Personne->bindModel( array( 'hasOne' => array('Prestation' => $bindPrestation ) ) );
				$conjoint = $this->Personne->find('first', array(
					'fields' => array('Personne.nom', 'Personne.prenom'),
					'conditions' => array( 
						'Personne.foyer_id' => $value['Foyer']['id'],
						'Prestation.rolepers' => 'CJT'
				)
				));
				$indicateurs[$key]['Personne']['prenomcjt'] = !empty($conjoint['Personne']['prenom']) ? $conjoint['Personne']['prenom'] : '';
				$indicateurs[$key]['Personne']['nomcjt'] = !empty($conjoint['Personne']['nom']) ? $conjoint['Personne']['nom'] : '';
			}		
			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'indicateurs' ) );
		}		
		
		
		
		
		
	}
?>