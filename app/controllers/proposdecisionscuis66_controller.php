<?php
    class Proposdecisionscuis66Controller extends AppController
    {
        public $name = 'Proposdecisionscuis66';
        
        public $uses = array( 'Propodecisioncui66', 'Option' );
        
        public $helpers = array( 'Default2', 'Default' );
        
        protected function _setOptions() {
			$options = $this->Propodecisioncui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Propodecisioncui66->Cui->enums(),
				$options
			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function propositioncui( $cui_id = null ) {
			$nbrCuis = $this->Propodecisioncui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Propodecisioncui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);
			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );

			$proposdecisionscuis66 = $this->Propodecisioncui66->find(
				'all',
				array(
					'conditions' => array(
						'Propodecisioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'proposdecisionscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

			// Retour à la liste des CUI en cas de retour
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
			}
		}
		
		/** ********************************************************************
		*
		*** *******************************************************************/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}


		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Propodecisioncui66->begin();

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$propodecisioncui66_id = $id;
				$propodecisioncui66 = $this->Propodecisioncui66->find(
					'first',
					array(
						'conditions' => array(
							'Propodecisioncui66.id' => $propodecisioncui66_id
						),
						'contain' => array(
							'Cui'
						),
						'recursive' => -1
					)
				);
				$this->set( 'propodecisioncui66', $propodecisioncui66 );
				
				$cui_id = Set::classicExtract( $propodecisioncui66, 'Propodecisioncui66.cui_id' );
			}
			
						
			// CUI en lien avec la proposition
			$cui = $this->Propodecisioncui66->Cui->find(
				'first',
				array(
					'conditions' => array(
						'Cui.id' => $cui_id
					),
					'contain' => false,
					'recursive' => -1
				)
			);

			$personne_id = Set::classicExtract( $cui, 'Cui.personne_id' );
			
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui', $cui );
			$this->set( 'cui_id', $cui_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'proposdecisionscuis66', 'action' => 'propositioncui', $cui_id ) );
			}
			
			$dossier_id = $this->Propodecisioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );
			
			
			if ( !$this->Jetons->check( $dossier_id ) ) {
				$this->Propodecisioncui66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			
			if ( !empty( $this->data ) ) {

				if( $this->Propodecisioncui66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Propodecisioncui66->save( $this->data );
					
					
					if( $saved ) {
						$saved = $this->Propodecisioncui66->Cui->updatePositionFromPropodecisioncui66( $this->Propodecisioncui66->id ) && $saved;
					}


					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Propodecisioncui66->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'proposdecisionscuis66', 'action' => 'propositioncui', $cui_id ) );
					}
					else {
						$this->Propodecisioncui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->data = $propodecisioncui66;
				}
			}
			
			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
        }
        
        
		/**
		 * Imprime la notification pour récupérer l'avis de l'élu, suite à l'avis de la MNE
		 *
		 * @param integer $id L'id de la proposition du CUI que l'on veut imprimer
		 * @return void
		 */
		public function notifelucui( $id ) {
			$pdf = $this->Propodecisioncui66->getNotifelucuiPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'NotifElu_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier à destination de l\'élu.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
		
		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}
    }
?>
