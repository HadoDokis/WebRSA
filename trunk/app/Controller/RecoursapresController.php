<?php
	App::import('Sanitize');

	class RecoursapresController extends AppController
	{
		public $name = 'Recoursapres';

		public $uses = array( 'Canton', 'Dossier', 'Recoursapre', 'Foyer', 'Adresse', 'Comiteapre', 'Personne', 'ApreComiteapre', 'Apre', 'Option', 'Adressefoyer' );

		public $components = array( 'Gestionzonesgeos', 'Gedooo.Gedooo', 'Search.Prg' => array( 'actions' => array( 'demande', 'visualisation' ) ) );

		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml', 'Search' );

		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();
			$options = array(
				'decisioncomite' => array(
					'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC' ),
					'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ' ),
					'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF' ),
				),
				'recoursapre' => array(
					'N' => __d( 'apre', 'ENUM::RECOURSAPRE::N' ),
					'O' => __d( 'apre', 'ENUM::RECOURSAPRE::O' )
				)
			);
			$this->set( 'options', $options );

			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
		}

		/**
		*
		*/

		public function demande() {
			$this->_index( 'Recoursapre::demande' );
		}

		/**
		*
		*/

		public function visualisation() {
			$this->_index( 'Recoursapre::visualisation' );
		}

		/**
		*
		*/

		protected function _index( $avisRecours = null ){
			$this->Gestionzonesgeos->setCantonsIfConfigured();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = (!empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array( ) );
			

			$this->Dossier->begin();
			if( !empty( $this->request->data ) ) {
				if( !empty( $this->request->data['ApreComiteapre'] ) ) {
					$data = Set::extract( $this->request->data, '/ApreComiteapre' );
					if( $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
						$saved = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
						if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
							$this->ApreComiteapre->commit();
							$this->redirect( array( 'action' => 'demande' ) ); // FIXME
						}
						else {
							$this->ApreComiteapre->rollback();
						}
					}
				}

				$recoursapres = $this->Recoursapre->search(
					$avisRecours,
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);

				$recoursapres['limit'] = 10;
				$this->paginate = $recoursapres;
				$recoursapres = $this->paginate( 'ApreComiteapre' );

				$this->set( 'recoursapres', $recoursapres );

				$this->Dossier->commit();

			}

			switch( $avisRecours ) {
				case 'Recoursapre::demande':
					$this->set( 'pageTitle', 'Demandes de recours' );
					$this->render( 'formulaire' );
					break;
				case 'Recoursapre::visualisation':
					$this->set( 'pageTitle', 'Visualisation des recours' );
					$this->render( 'visualisation' );
					break;
			}

			$this->Dossier->commit(); //FIXME
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Recoursapre->search(
				"Recoursapre::visualisation",
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->request->params['named'], '__' )
			);

			unset( $querydata['limit'] );
			$recoursapres = $this->ApreComiteapre->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'recoursapres' ) );
		}

		/**
		 * Impression d'un recours pour une demande d'APRE, pour un destinataire donné.
		 *
		 * @param integer $apre_id L'id de l'APRE
		 * @return void
		 */
		public function impression( $apre_id = null ) {
			$dest = Set::classicExtract( $this->request->params, 'named.dest' );

			$pdf = $this->Recoursapre->getDefaultPdf(
				$apre_id,
				$dest,
				$this->Session->read( 'Auth.User.id' )
			) ;

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'recoursapre_%d-%s-%s.pdf', $apre_id, $dest, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression du recours d\'APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>