<?php
	App::import('Sanitize');
	class CohortescomitesapresController extends AppController
	{
		public $name = 'Cohortescomitesapres';
		public $uses = array( 'Apre', 'Option', 'Personne', 'ApreComiteapre', 'Cohortecomiteapre', 'Comiteapre', 'Participantcomite', 'Apre', 'ComiteapreParticipantcomite', 'Adressefoyer', 'Tiersprestataireapre', 'Suiviaideapretypeaide', 'Referent', 'Dossier' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
		public $components = array( 'Gedooo.Gedooo', 'Prg' => array( 'actions' => array( 'aviscomite', 'notificationscomite' ) ) );

//		public function __construct() {
//			//FIXME: voir si le fait d'appeler aviscomite ne va pas retourner tous les comités précédents comme au départ
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'aviscomite', 'notificationscomite' ) ) ) );
//			parent::__construct();
//		}

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'referent', $this->Referent->find( 'list' ) );
			$options = array(
				'decisioncomite' => array(
					'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
					'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
					'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
				)
			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function aviscomite() {
			$this->_index( 'Cohortecomiteapre::aviscomite' );
		}

		//---------------------------------------------------------------------

		public function notificationscomite() {
			$this->_index( 'Cohortecomiteapre::notificationscomite' );
		}

		/**
		*
		*/

		protected function _index( $avisComite = null ) {
			$this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );
			$this->Comiteapre->Apre->deepAfterFind = true;

			$isRapport = ( Set::classicExtract( $this->params, 'named.rapport' ) == 1 );
			$idRapport = Set::classicExtract( $this->params, 'named.Cohortecomiteapre__id' );
			$idComite = Set::classicExtract( $this->data, 'Cohortecomiteapre.id' );

			$this->Dossier->begin(); // Pour les jetons
			if( !empty( $this->data ) ) {
				// Sauvegarde
				if( !empty( $this->data['ApreComiteapre'] ) ) {
					$data = Set::extract( $this->data, '/ApreComiteapre' );
					$dataApre = Set::combine( $this->data, 'ApreComiteapre.{n}.apre_id', 'ApreComiteapre.{n}.montantattribue' );

					// On oblige le comité à prendre une décision
					$this->ApreComiteapre->validate['decisioncomite'][] = array(
						'rule' => array( 'notEmpty' ),
						'required' => true,
						'message' => 'Champ obligatoire',
					);

					$return = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) );

					if( $return ) {
						$return = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );

						$saved = $return;
						$this->Apre->deepAfterFind = false;
						foreach( $dataApre as $apre_id => $montantattribue ) {
							$apre = $this->Apre->findById( $apre_id, null, null, -1 );
							$apre['Apre']['montantaverser'] = ( !empty( $montantattribue ) ? $montantattribue : 0 );
							$this->Apre->create( $apre );
							$saved = $this->Apre->save( $apre ) && $saved;
						}

						if( $saved ) {
							$this->ApreComiteapre->commit();
							if( !$isRapport ){
								$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
								$this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', $idComite ) );
							}
							else if( $isRapport ) {
								$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
								$this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', $idRapport ) );
							}
						}
						else {
							$this->ApreComiteapre->rollback();
						}
					}
				}

				$comitesapres = $this->Cohortecomiteapre->search( $avisComite, $this->data );

				$comitesapres['limit'] = 10;
				$this->paginate = $comitesapres;
				$comitesapres = $this->paginate( 'Comiteapre' );

				$this->set( 'comitesapres', $comitesapres );
			}
			$this->_setOptions();
			switch( $avisComite ) {
				case 'Cohortecomiteapre::aviscomite':
					$this->set( 'pageTitle', 'Décisions des comités' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Cohortecomiteapre::notificationscomite':
					$this->set( 'pageTitle', 'Notifications décisions comités' );
					$this->render( $this->action, null, 'visualisation' );
					break;
			}

			$this->Dossier->commit(); //FIXME
		}

		public function exportcsv() {
			$querydata = $this->Cohortecomiteapre->search( null, array_multisize( $this->params['named'] ) );
			unset( $querydata['limit'] );
			$decisionscomites = $this->Comiteapre->find( 'all', $querydata );
			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'decisionscomites' ) );
		}

		/**
		* Modifications du Comité d'examen
		**/

		public function editdecision( $apre_id = null ) {
			$this->ApreComiteapre->Apre->deepAfterFind = false;
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'comitesapres', 'action' => 'rapport', Set::classicExtract( $this->data, 'ApreComiteapre.comiteapre_id' ) ) );
			}

			// TODO: error404/error500 si on ne trouve pas les données
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();

			$aprecomiteapre = $this->ApreComiteapre->findByApreId( $apre_id, null, null, -1 );
			$this->set( compact( 'aprecomiteapre' ) );

			$comiteapre = $this->Comiteapre->findById( Set::classicExtract( $aprecomiteapre, 'ApreComiteapre.comiteapre_id' ), null, null, -1 );
			$this->set( compact( 'comiteapre' ) );

			$apre = $this->Apre->find(
				'first',
				array(
					'conditions' => array(
						'Apre.id' => $apre_id
					)
				)
			);

			unset( $apre['Apre']['Piecemanquante'] );
			unset( $apre['Apre']['Piecepresente'] );
			unset( $apre['Apre']['Piece'] );
			unset( $apre['Apre']['Natureaide'] );
			unset( $apre['Pieceapre'] );
			unset( $apre['Montantconsomme'] );
			foreach( $this->Apre->aidesApre as $model ){
				unset( $apre[$model] );
			}
			unset( $apre['Relanceapre'] );

			// Foyer
			$foyer = $this->Apre->Personne->Foyer->findById( $apre['Personne']['foyer_id'], null, null, -1 );
			$apre['Foyer'] = $foyer['Foyer'];

			// Dossier
			$dossier = $this->Apre->Personne->Foyer->Dossier->findById( $foyer['Foyer']['dossier_id'], null, null, -1 );
			$apre['Dossier'] = $dossier['Dossier'];

			// Adresse
			$adresse = $this->Apre->Personne->Foyer->Adressefoyer->Adresse->findById( $foyer['Foyer']['id'], null, null, -1 );
			$apre['Adresse'] = $adresse['Adresse'];

			$this->Dossier->begin(); // Pour les jetons
			if( !empty( $this->data ) ) {

				$data = Set::extract( $this->data, '/ApreComiteapre' );

				if( $this->ApreComiteapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->ApreComiteapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
					if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
						$this->ApreComiteapre->commit();
						$this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', Set::classicExtract( $this->data, 'ApreComiteapre.comiteapre_id' ) ) );

					}
					else {
						$this->ApreComiteapre->rollback();
					}
				}
			}
			else {
				$this->data = $apre;
			}
			$this->_setOptions();
			$this->Dossier->commit(); // Pour les jetons
			$this->set( 'apre', $apre );
		}

		/**
		 * Génère l'impression de la décision d'un passage en comité d'examen APRE.
		 * On prend la décision de ne pas le stocker.
		 *
		 * @param integer $apre_comiteapre_id L'id de l'entrée de décision d'une APRE en comité APRE
		 * @return void
		 */
		public function impression( $apre_comiteapre_id = null ) {
			$dest = Set::classicExtract( $this->params, 'named.dest' );

			$pdf = $this->ApreComiteapre->getNotificationPdf(
				$apre_comiteapre_id,
				$dest,
				$this->Session->read( 'Auth.User.id' )
			) ;

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'decision_comite_apre_%d-%s-%s.pdf', $apre_comiteapre_id, $dest, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression de la décision du comité APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>
		public $components = array( 'Gedooo.Gedooo', 'Prg' => array( 'actions' => array( 'aviscomite', 'notificationscomite' ) ) 			);

//		public function __construct() {
//			//FIXME: voir si le fait d'appeler aviscomite ne va pas retourner tous les comités précédents comme au départ
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'aviscomite', 'notificationscomite' ) ) ) );
//			parent::__construct();
//		}

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'referent', $this->Referent->find( 'list' ) );
			$options = array(
				'decisioncomite' => array(
					'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
					'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
					'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
				)
			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function aviscomite() {
			$this->_index( 'Cohortecomiteapre::aviscomite' );
		}

		//---------------------------------------------------------------------

		public function notificationscomite() {
			$this->_index( 'Cohortecomiteapre::notificationscomite' );
		}

		/**
		*
		*/

		protected function _index( $avisComite = null ) {
			$this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );
			$this->Comiteapre->Apre->deepAfterFind = true;

			$isRapport = ( Set::classicExtract( $this->params, 'named.rapport' ) == 1 );
			$idRapport = Set::classicExtract( $this->params, 'named.Cohortecomiteapre__id' );
			$idComite = Set::classicExtract( $this->data, 'Cohortecomiteapre.id' );

			$this->Dossier->begin(); // Pour les jetons
			if( !empty( $this->data ) ) {
				// Sauvegarde
				if( !empty( $this->data['ApreComiteapre'] ) ) {
					$data = Set::extract( $this->data, '/ApreComiteapre' );
					$dataApre = Set::combine( $this->data, 'ApreComiteapre.{n}.apre_id', 'ApreComiteapre.{n}.montantattribue' );

					// On oblige le comité à prendre une décision
					$this->ApreComiteapre->validate['decisioncomite'][] = array(
						'rule' => array( 'notEmpty' ),
						'required' => true,
						'message' => 'Champ obligatoire',
					);

					$return = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) );

					if( $return ) {
						$return = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );

						$saved = $return;
						$this->Apre->deepAfterFind = false;
						foreach( $dataApre as $apre_id => $montantattribue ) {
							$apre = $this->Apre->findById( $apre_id, null, null, -1 );
							$apre['Apre']['montantaverser'] = ( !empty( $montantattribue ) ? $montantattribue : 0 );
							$this->Apre->create( $apre );
							$saved = $this->Apre->save( $apre ) && $saved;
						}

						if( $saved ) {
							$this->ApreComiteapre->commit();
							if( !$isRapport ){
								$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
								$this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', $idComite ) );
							}
							else if( $isRapport ) {
								$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
								$this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', $idRapport ) );
							}
						}
						else {
							$this->ApreComiteapre->rollback();
						}
					}
				}

				$comitesapres = $this->Cohortecomiteapre->search( $avisComite, $this->data );

				$comitesapres['limit'] = 10;
				$this->paginate = $comitesapres;
				$comitesapres = $this->paginate( 'Comiteapre' );

				$this->set( 'comitesapres', $comitesapres );
			}
			$this->_setOptions();
			switch( $avisComite ) {
				case 'Cohortecomiteapre::aviscomite':
					$this->set( 'pageTitle', 'Décisions des comités' );
					$this->render( $this->action, null, 'formulaire' );
					break;
				case 'Cohortecomiteapre::notificationscomite':
					$this->set( 'pageTitle', 'Notifications décisions comités' );
					$this->render( $this->action, null, 'visualisation' );
					break;
			}

			$this->Dossier->commit(); //FIXME
		}

		public function exportcsv() {
			$querydata = $this->Cohortecomiteapre->search( null, array_multisize( $this->params['named'] ) );
			unset( $querydata['limit'] );
			$decisionscomites = $this->Comiteapre->find( 'all', $querydata );
			$this->_setOptions();
			$this->layout = '';
			$this->set( compact( 'decisionscomites' ) );
		}

		/**
		* Modifications du Comité d'examen
		**/

		public function editdecision( $apre_id = null ) {
			$this->ApreComiteapre->Apre->deepAfterFind = false;
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'comitesapres', 'action' => 'rapport', Set::classicExtract( $this->data, 'ApreComiteapre.comiteapre_id' ) ) );
			}

			// TODO: error404/error500 si on ne trouve pas les données
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();

			$aprecomiteapre = $this->ApreComiteapre->findByApreId( $apre_id, null, null, -1 );
			$this->set( compact( 'aprecomiteapre' ) );

			$comiteapre = $this->Comiteapre->findById( Set::classicExtract( $aprecomiteapre, 'ApreComiteapre.comiteapre_id' ), null, null, -1 );
			$this->set( compact( 'comiteapre' ) );

			$apre = $this->Apre->find(
				'first',
				array(
					'conditions' => array(
						'Apre.id' => $apre_id
					)
				)
			);

			unset( $apre['Apre']['Piecemanquante'] );
			unset( $apre['Apre']['Piecepresente'] );
			unset( $apre['Apre']['Piece'] );
			unset( $apre['Apre']['Natureaide'] );
			unset( $apre['Pieceapre'] );
			unset( $apre['Montantconsomme'] );
			foreach( $this->Apre->aidesApre as $model ){
				unset( $apre[$model] );
			}
			unset( $apre['Relanceapre'] );

			// Foyer
			$foyer = $this->Apre->Personne->Foyer->findById( $apre['Personne']['foyer_id'], null, null, -1 );
			$apre['Foyer'] = $foyer['Foyer'];

			// Dossier
			$dossier = $this->Apre->Personne->Foyer->Dossier->findById( $foyer['Foyer']['dossier_id'], null, null, -1 );
			$apre['Dossier'] = $dossier['Dossier'];

			// Adresse
			$adresse = $this->Apre->Personne->Foyer->Adressefoyer->Adresse->findById( $foyer['Foyer']['id'], null, null, -1 );
			$apre['Adresse'] = $adresse['Adresse'];

			$this->Dossier->begin(); // Pour les jetons
			if( !empty( $this->data ) ) {

				$data = Set::extract( $this->data, '/ApreComiteapre' );

				if( $this->ApreComiteapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->ApreComiteapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
					if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
						$this->ApreComiteapre->commit();
						$this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', Set::classicExtract( $this->data, 'ApreComiteapre.comiteapre_id' ) ) );

					}
					else {
						$this->ApreComiteapre->rollback();
					}
				}
			}
			else {
				$this->data = $apre;
			}
			$this->_setOptions();
			$this->Dossier->commit(); // Pour les jetons
			$this->set( 'apre', $apre );
		}

		/**
		 * Génère l'impression de la décision d'un passage en comité d'examen APRE.
		 * On prend la décision de ne pas le stocker.
		 *
		 * @param integer $apre_comiteapre_id L'id de l'entrée de décision d'une APRE en comité APRE
		 * @return void
		 */
		public function impression( $apre_comiteapre_id = null ) {
			$dest = Set::classicExtract( $this->params, 'named.dest' );

			$pdf = $this->ApreComiteapre->getNotificationPdf(
				$apre_comiteapre_id,
				$dest,
				$this->Session->read( 'Auth.User.id' )
			) ;

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'decision_comite_apre_%d-%s-%s.pdf', $apre_comiteapre_id, $dest, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression de la décision du comité APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>