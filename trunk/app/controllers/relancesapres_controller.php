<?php
	class RelancesapresController extends AppController
	{

		public $name = 'Relancesapres';
		public $uses = array( 'Apre', 'Option', 'Personne', 'Prestation'/*, 'Dsp'*/, 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert', 'Apre', 'Relanceapre' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
		public $aucunDroit = array( 'ajaxpiece' );

		public $commeDroit = array(
			'add' => 'Relancesapres:edit'
		);

		/**
		*
		*/

		public function beforeFilter() {
			parent::beforeFilter();
			$options = $this->Relanceapre->allEnumLists();
			$this->set( 'options', $options );
			$piecesapre = $this->Apre->Pieceapre->find( 'list' );
			$this->set( 'piecesapre', $piecesapre );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
		}

		/**
		*   Ajax pour les pièces liées à la bonne APRE
		*/

		public function ajaxpiece( $apre_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			$dataApre_id = Set::extract( $this->data, 'Relanceapre.apre_id' );
			$apre_id = ( empty( $apre_id ) && !empty( $dataApre_id ) ? $dataApre_id : $apre_id );

			$apre = $this->Apre->findbyId( $apre_id, null, null, 1 );
			$this->set( 'apre', $apre );

			$this->render( 'ajaxpiece', 'ajax' );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Relanceapre->begin();

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$apre_id = $id;

				$apre = $this->Apre->find( 'first', array( 'conditions' => array( 'Apre.id' => $id ) ) );
				$this->set( 'apre', $apre );

				$personne_id = Set::classicExtract( $apre, 'Apre.personne_id' );
				$dossier_id = $this->Personne->dossierId( Set::classicExtract( $apre, 'Apre.personne_id' ) );
			}
			else if( $this->action == 'edit' ) {
				$relanceapre_id = $id;
				$relanceapre = $this->Relanceapre->findById( $relanceapre_id, null, null, 1 );
				$this->assert( !empty( $relanceapre ), 'invalidParameter' );

				$personne_id = Set::classicExtract( $relanceapre, 'Apre.personne_id' );
				$apre = $this->Apre->find( 'first', array( 'conditions' => array( 'Apre.personne_id' => $personne_id ) ) );
				$this->set( 'apre', $apre );
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'dossier_id', $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'apres', 'action' => 'index', $personne_id ) );
			}

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Relanceapre->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ){
				if( $this->Relanceapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Relanceapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Relanceapre->commit(); // FIXME
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array(  'controller' => 'apres','action' => 'index', Set::classicExtract( $apre, 'Apre.personne_id' ) ) );
					}
					else {
						$this->Relanceapre->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else{
				if( $this->action == 'edit' ) {
					$this->data = $relanceapre;
				}
			}
			$this->Relanceapre->commit();

			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function view( $relanceapre_id = null ){
			$relanceapre = $this->Relanceapre->findById( $relanceapre_id );
			$this->assert( !empty( $relanceapre ), 'invalidParameter' );

			$apre = $this->Apre->findByPersonneId( Set::classicExtract( $relanceapre, 'Relanceapre.apre_id' ) );
			$this->set( 'apre', $apre );

			$this->set( 'relanceapre', $relanceapre );
			$this->set( 'personne_id', Set::classicExtract( $relanceapre, 'Relanceapre.apre_id' ) );
		}

		/**
		 * Génère l'impression d'une relance d'APRE pour le CG 93.
		 * On prend la décision de ne pas le stocker.
		 *
		 * @param integer $id L'id de la relance d'APRE que l'on veut imprimer.
		 * @return void
		 */
		public function impression( $id = null ) {
			$pdf = $this->Relanceapre->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) ) ;

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'relanceapre_%d-%s.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'impression de la relance de l\'APRE.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>