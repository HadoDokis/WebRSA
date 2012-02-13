<?php
	class Defautsinsertionseps66Controller extends AppController
	{
		public $components = array( 'Prg' => array( 'actions' => array( 'selectionnoninscrits', 'selectionradies', 'courriersinformations' ) ), 'Gestionzonesgeos', 'Gedooo' );
		public $helpers = array( 'Default2', 'Search' );

		/**
		*
		*/

		protected function _selectionPassageDefautinsertionep66( $qdName, $actionbp ) {
			$this->set( 'etatdosrsa', ClassRegistry::init('Option')->etatdosrsa( ClassRegistry::init('Situationdossierrsa')->etatOuvert()) );
			
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$mesCodesInsee = ClassRegistry::init( 'Zonegeographique' )->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
			}
			else {
				$mesCodesInsee = ClassRegistry::init( 'Adresse' )->listeCodesInsee();
			}
			$this->set( compact( 'mesCodesInsee' ) );

			if( !empty( $this->data ) ) {
				$queryData = $this->Defautinsertionep66->{$qdName}( $this->data, ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				$queryData['limit'] = 10;

				$this->paginate = array( 'Personne' => $queryData );
				$personnes = $this->paginate( $this->Defautinsertionep66->Dossierep->Personne );
			}

			$this->set( compact( 'personnes' ) );

			$this->set( compact( 'actionbp' ) );

			$this->render( $this->action, null, 'selectionnoninscrits' ); // FIXME: nom de la vue
		}

		/**
		*
		*/

		public function selectionnoninscrits() {
			$this->_selectionPassageDefautinsertionep66( 'qdNonInscrits', 'noninscriptionpe' );
		}

		/**
		*
		*/

		public function selectionradies() {
			$this->_selectionPassageDefautinsertionep66( 'qdRadies', 'radiationpe' );
		}

		/**
		*
		*/

		public function courriersinformations() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$this->Gestionzonesgeos->setCantonsIfConfigured();

			if( !empty( $this->data ) ) {
				$search = $this->data['Search'];

				if ( !empty( $search ) ) {
					$this->paginate['Dossierep'] = $this->Defautinsertionep66->search(
						$mesCodesInsee,
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$search
					);

					$results = $this->paginate( $this->Defautinsertionep66->Dossierep );
					$this->set( compact( 'results' ) );
				}
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
		}

		public function printCourriersInformations() {
			$this->Defautinsertionep66->begin();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
			
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );
			}

			$querydata = $this->Defautinsertionep66->search(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				array_multisize( $this->params['named'] )
			);
			unset( $querydata['limit'] );

			$defautsinsertionseps66 = $this->Defautinsertionep66->Dossierep->find( 'all', $querydata );

			$pdfs = array();
			foreach( Set::extract( '/Dossierep/id', $defautsinsertionseps66 ) as $dossierep_id ) {
				$pdfs[] = $this->Defautinsertionep66->getCourrierInformationPdf( $dossierep_id );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'CourriersInformation' );

			if( $pdfs ) {
				$this->Defautinsertionep66->commit();
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'CourriersInformation' );
			}
			else {
				$this->Defautinsertionep66->rollback();
				$this->Session->setFlash( 'Impossible de générer les courriers d\'information pour cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>