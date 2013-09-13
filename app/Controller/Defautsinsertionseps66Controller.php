<?php
	/**
	 * Code source de la classe Defautsinsertionseps66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Defautsinsertionseps66Controller ...
	 *
	 * @package app.Controller
	 */
	class Defautsinsertionseps66Controller extends AppController
	{
		public $components = array( 'Search.Prg' => array( 'actions' => array( 'selectionnoninscrits', 'selectionradies', 'courriersinformations' ) ), 'Gestionzonesgeos', 'Gedooo.Gedooo' );
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

			if( !empty( $this->request->data ) ) {
				$queryData = $this->Defautinsertionep66->{$qdName}( $this->request->data, ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() ), $this->Session->read( 'Auth.User.filtre_zone_geo' ) );
				$queryData['limit'] = 10;
				$queryData['conditions'][] = WebrsaPermissions::conditionsDossier();

				$this->paginate = array( 'Personne' => $queryData );
				$personnes = $this->paginate( $this->Defautinsertionep66->Dossierep->Personne );
			}

			$this->set( compact( 'personnes' ) );

			$this->set( compact( 'actionbp' ) );

			$this->render( 'selectionnoninscrits' );
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
			$this->set( 'printed',  ClassRegistry::init('Option')->printed() );

			if( !empty( $this->request->data ) ) {
				$search = $this->request->data['Search'];

				if ( !empty( $search ) ) {
					$querydata = array(
						'Dossierep' => $this->Defautinsertionep66->search(
							$mesCodesInsee,
							$this->Session->read( 'Auth.User.filtre_zone_geo' ),
							$search
						)
					);
					$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();
					$this->paginate = $querydata;

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
				Hash::expand( $this->request->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$querydata['conditions'][] = WebrsaPermissions::conditionsDossier();

			$defautsinsertionseps66 = $this->Defautinsertionep66->Dossierep->find( 'all', $querydata );

			$pdfs = array();
			foreach( Set::extract( '/Dossierep/id', $defautsinsertionseps66 ) as $dossierep_id ) {
				$pdfs[] = $this->Defautinsertionep66->getCourrierInformationPdf( $dossierep_id, $this->Session->read( 'Auth.User.id' ) );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'CourriersInformation' );

			if( $pdfs ) {
				$this->Defautinsertionep66->commit();
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'CourriersInformation.pdf' );
			}
			else {
				$this->Defautinsertionep66->rollback();
				$this->Session->setFlash( 'Impossible de générer les courriers d\'information pour cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>