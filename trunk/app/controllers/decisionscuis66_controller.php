<?php
    class Decisionscuis66Controller extends AppController
    {
        public $name = 'Decisionscuis66';
        
        public $uses = array( 'Decisioncui66', 'Option' );
        
        public $helpers = array( 'Default2', 'Default' );
        
        protected function _setOptions() {
			$options = $this->Decisioncui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Decisioncui66->Cui->enums(),
				$this->Decisioncui66->Cui->Propodecisioncui66->enums(),
				$options
			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function decisioncui( $cui_id = null ) {
			$nbrCuis = $this->Decisioncui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Decisioncui66->Cui->find(
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

			$decisionscuis66 = $this->Decisioncui66->find(
				'all',
				array(
					'conditions' => array(
						'Decisioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'decisionscuis66' ) );
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

			$this->Decisioncui66->begin();

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$decisioncui66_id = $id;
				$decisioncui66 = $this->Decisioncui66->find(
					'first',
					array(
						'conditions' => array(
							'Decisioncui66.id' => $decisioncui66_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				$this->set( 'decisioncui66', $decisioncui66 );
				
				$cui_id = Set::classicExtract( $decisioncui66, 'Decisioncui66.cui_id' );
			}
			
						
			// CUI en lien avec la proposition
			$cui = $this->Decisioncui66->Cui->find(
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

			
			// Récupération des avis proposés sur le CUI
			$proposdecisionscuis66 = $this->Decisioncui66->Cui->Propodecisioncui66->find(
				'all',
				array(
					'conditions' => array(
						'Propodecisioncui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);
			$this->set( compact( 'proposdecisionscuis66' ) );
			
			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'decisionscuis66', 'action' => 'decisioncui', $cui_id ) );
			}
			
			$dossier_id = $this->Decisioncui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );
			
			
			if ( !$this->Jetons->check( $dossier_id ) ) {
				$this->Decisioncui66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			
			if ( !empty( $this->data ) ) {

				if( $this->Decisioncui66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Decisioncui66->save( $this->data );

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Decisioncui66->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'decisionscuis66', 'action' => 'decisioncui', $cui_id ) );
					}
					else {
						$this->Decisioncui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->data = $decisioncui66;
				}
			}
			
			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
        }
       
		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		
		/**
		 * Imprime la notification au bénéficiaire pour le CUI.
		 *
		 * @param integer $id L'id de la décision sur le CUI que l'on veut imprimer
		 * @return void
		 */
		public function impression( $id, $destinataire ) {
			$pdf = $this->Decisioncui66->getDefaultPdf( $id, $destinataire, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'cui_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de décision', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

    }
?>
