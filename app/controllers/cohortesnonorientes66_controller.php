<?php
	class Cohortesnonorientes66Controller extends AppController
	{
		public $name = 'Cohortesnonorientes66';

		public $uses = array(
			'Cohortenonoriente66',
			'Personne',
			'Zonegeographique',
			'Dossier',
			'Option',
			'Canton'
		);

		public $helpers = array( 'Csv', 'Ajax', 'Default2', 'Locale', 'Search', 'Fileuploader' );

		public $components = array(
			'Prg2' => array(
				'actions' => array(
					'isemploi' => array( 'filter' => 'Search' ),
					'notisemploi' => array( 'filter' => 'Search' ),
					'notisemploiaimprimer' => array( 'filter' => 'Search' ),
					'notifaenvoyer' => array( 'filter' => 'Search' ),
					'oriente' => array( 'filter' => 'Search' )
				)
			),
			'Fileuploader'
		);


		/**
		*
		*/
		public function _setOptions() {
			$this->set( 'options',  $this->Personne->allEnumLists() );
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );


			$this->set( 'qual',  $this->Option->qual() );
			
			$etats = Configure::read( 'Situationdossierrsa.etatdosrsa.ouvert' );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $etats ) );
			
			$this->set( 'users', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'order' => array( 'User.nom ASC' )
					)
				)
			);

			// Population du select de type d'orientation
			$conditionsTypeorient = array();
			if( $this->action == 'isemploi' ) {
				$typeorient_id = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
				if( is_array( $typeorient_id ) && isset( $typeorient_id[0] ) ){
					$conditionsTypeorient['Typeorient.parentid'] = $typeorient_id;
				}
			}
// 			else if( $this->action == 'notisemploi' ) {
// 				$typeorient_id = Configure::read( 'Orientstruct.typeorientprincipale.SOCIAL' );
// 				if( is_array( $typeorient_id ) ){
// 					$conditionsTypeorient['Typeorient.parentid'] = $typeorient_id;
// 				}
// 			}
			$typesOrients = $this->Personne->Orientstruct->Typeorient->listOptions( $conditionsTypeorient );
			$this->set( 'typesOrient', $typesOrients );

			// Population du select des structures référentes
			$this->set( 'structuresReferentes', $this->Personne->Orientstruct->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
			
			$Historiqueetatpe = ClassRegistry::init( 'Historiqueetatpe' );
			$this->set( 'historiqueetatpe', $Historiqueetatpe->allEnumLists() );
			
			$User = ClassRegistry::init( 'User' );
			$this->set( 'gestionnaire', $User->find(
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
			
			$optionsOrientstruct = $this->Personne->Orientstruct->allEnumLists();
			$this->set( compact( 'optionsOrientstruct') );
			
			$this->set( 'options', $this->Personne->Orientstruct->Nonoriente66->allEnumLists() );
		}



		/**
		*
		*/

		public function isemploi() {
			$this->_index( 'Nonoriente::isemploi' );
		}

		/**
		*
		*/

		public function notisemploi() {
			$this->_index( 'Nonoriente::notisemploi' );
		}

		/**
		*
		*/

		public function notisemploiaimprimer() {
			$this->_index( 'Nonoriente::notisemploiaimprimer' );
		}

		/**
		*
		*/

		public function notifaenvoyer() {
			$this->_index( 'Nonoriente::notifaenvoyer' );
		}
		
		/**
		*
		*/

		public function oriente() {
			$this->_index( 'Nonoriente::oriente' );
		}


		/**
		*
		*/

		protected function _index( $statutNonoriente = null ) {
			$this->assert( !empty( $statutNonoriente ), 'invalidParameter' );

			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
				$this->Personne->Orientstruct->begin();

				/**
				*
				* Sauvegarde
				*
				*/
// debug($this->data);
// die();
				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->data['Orientstruct'] ) ) {

					$success = true;
					if( in_array( $this->action, array( 'isemploi', 'notisemploi' ) ) ) {
						$success = $this->Personne->Nonoriente66->saveAll( $this->data['Nonoriente66'], array( 'validate' => 'first', 'atomic' => false ) ) && $success;
					}


					$success = $this->Personne->Orientstruct->saveAll( $this->data['Orientstruct'], array( 'validate' => 'first', 'atomic' => false ) ) && $success;

					if( $success ) {
						
						foreach( array_unique( Set::extract( $this->data, 'Orientstruct.{n}.dossier_id' ) ) as $dossier_id ) {
							$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
						}
// 							$this->log( var_export( $this->data['Orientstruct'], true ), LOG_DEBUG );
// 							$this->log( var_export( $this->data['Orientstruct'], true ), LOG_DEBUG );
// 						$this->Personne->Orientstruct->commit();
						$this->Personne->Orientstruct->commit();
						unset( $this->data['Orientstruct'] );
						if( isset( $this->data['sessionKey'] ) ) {
							$this->Session->del( "Prg.{$this->name}__{$this->action}.{$this->data['sessionKey']}" );
						}
					}
					else {
						$this->Personne->Orientstruct->rollback();
					}
				}

				/**
				*
				* Filtrage
				*
				*/

				if( ( $statutNonoriente == 'Nonoriente::isemploi' ) || ( $statutNonoriente == 'Nonoriente::notisemploi' ) || ( $statutNonoriente == 'Nonoriente::notisemploiaimprimer' ) || ( $statutNonoriente == 'Nonoriente::notifaenvoyer' ) || ( $statutNonoriente == 'Nonoriente::oriente' )  && !empty( $this->data ) ) {
					$this->Dossier->begin(); // Pour les jetons

					$limit = 10;
					if( $statutNonoriente == 'Nonoriente::notisemploiaimprimer' ){
						$limit = 100;
					}
					$this->paginate = $this->Cohortenonoriente66->search( $statutNonoriente, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
					$this->paginate['limit'] = $limit;
					$cohortesnonorientes66 = $this->paginate( 'Personne' );

					//Pour le lien filelink, sauvegarde de l'URL de la recherche lorsqu'on cliquera sur le bouton "Retour" dans la liste des fichiers liés
					$this->Session->write( "Savedfilters.Nonorientes66.filelink",
						Set::merge(
							array(
								'controller' => Inflector::underscore( $this->name ),
								'action' => $this->action
							),
							$this->params['named']
						)
					);
// debug( $cohortesnonorientes66 );
					$this->Dossier->commit();

					$this->set( 'cohortesnonorientes66', $cohortesnonorientes66 );

				}

			}

			$this->_setOptions();
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}


			switch( $statutNonoriente ) {
				case 'Nonoriente::isemploi':
					$this->render( $this->action, null, 'isemploi' );
					break;
				case 'Nonoriente::notisemploi':
					$this->render( $this->action, null, 'notisemploi' );
					break;
				case 'Nonoriente::notisemploiaimprimer':
					$this->render( $this->action, null, 'notisemploiaimprimer' );
					break;
				case 'Nonoriente::notifaenvoyer':
					$this->render( $this->action, null, 'notifaenvoyer' );
					break;
				case 'Nonoriente::oriente':
					$this->render( $this->action, null, 'oriente' );
					break;
			}
		}

		
		/**
		* Impression d'un rendez-vous.
		*
		* @param integer $rdv_id
		* @return void
		*/
		public function impression( $id = null ) {
			$pdf = $this->Cohortenonoriente66->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'nonorientation-%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * 
		 *
		 * @param integer $id L'id de 
		 */
		public function impressions() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
			
			// La page sur laquelle nous sommes
			$page = Set::classicExtract( $this->params, 'named.page' );
			if( ( intval( $page ) != $page ) || $page < 0 ) {
				$page = 1;
			}
		
			$pdf = $this->Cohortenonoriente66->getDefaultCohortePdf(
				'Nonoriente::notisemploiaimprimer',
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$this->Session->read( 'Auth.User.id' ),
				XSet::bump( $this->params['named'] ),
				$page
			);

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'nonorientes-%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	
		/**
			* Impression d'un rendez-vous.
			*
			* @param integer $id
			* @return void
			*/
		public function impressionOrientation( $id = null ) {
			$pdf = $this->Personne->Orientstruct->getPdfNonoriente66( $id );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'orientation-%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
		
		/**
		 * 
		 *
		 * @param integer $id L'id de 
		 */
		public function impressionsOrientation() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );
			
			// La page sur laquelle nous sommes
			$page = Set::classicExtract( $this->params, 'named.page' );
			if( ( intval( $page ) != $page ) || $page < 0 ) {
				$page = 1;
			}
		
			$pdfs = $this->Cohortenonoriente66->getCohortePdfNonoriente66(
				'Nonoriente::notifaenvoyer',
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				XSet::bump( $this->params['named'], '__' ),
				$page
			);
			

			if( !empty( $pdfs ) ) {
				$pdf = $this->Gedooo->concatPdfs( $pdfs, 'Nonoriente66' );
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'orientations-%s.pdf', date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}	
	}
?>