<?php
    class Accompagnementscuis66Controller extends AppController
    {
        public $name = 'Accompagnementscuis66';

        public $uses = array( 'Accompagnementcui66', 'Option' );

        public $helpers = array( 'Default2', 'Default' );

        public $components = array( 'Jetons2', 'Default', 'Gedooo.Gedooo' );

        protected function _setOptions() {
			$options = $this->Accompagnementcui66->enums();

			$this->set( 'qual', $this->Option->qual() );
			$options = Set::merge(
				$this->Accompagnementcui66->Cui->enums(),
				$options
			);
			$typevoie = $this->Option->typevoie();
			$options = Set::insert( $options, 'typevoie', $typevoie );

			$secteursactivites = $this->Accompagnementcui66->Cui->Personne->Dsp->Libsecactderact66Secteur->find(
					'list',
					array(
						'contain' => false,
						'order' => array( 'Libsecactderact66Secteur.code' )
					)
				);
			$this->set( 'secteursactivites', $secteursactivites );

			$codesromemetiersdsps66 = $this->Accompagnementcui66->Cui->Personne->	Dsp->Libderact66Metier->find(
					'all',
					array(
						'contain' => false,
						'order' => array( 'Libderact66Metier.code' )
					)
				);
			foreach( $codesromemetiersdsps66 as $coderomemetierdsp66 ) {
				$options['Coderomemetierdsp66'][$coderomemetierdsp66['Libderact66Metier']['coderomesecteurdsp66_id'].'_'.$coderomemetierdsp66['Libderact66Metier']['id']] = $coderomemetierdsp66['Libderact66Metier']['code'].'. '.$coderomemetierdsp66['Libderact66Metier']['name'];
			}

			$this->set( 'options', $options );
		}

		/**
		*
		*/

		public function index( $cui_id = null ) {
			$nbrCuis = $this->Accompagnementcui66->Cui->find( 'count', array( 'conditions' => array( 'Cui.id' => $cui_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrCuis == 1 ), 'invalidParameter' );

			$cui = $this->Accompagnementcui66->Cui->find(
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

			$accompagnementscuis66 = $this->Accompagnementcui66->find(
				'all',
				array(
					'conditions' => array(
						'Accompagnementcui66.cui_id' => $cui_id
					),
					'recursive' => -1,
					'contain' => false
				)
			);

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'cui_id', $cui_id );
			$this->set( compact( 'accompagnementscuis66' ) );
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );

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

			if( $this->action == 'add' ) {
				$cui_id = $id;
			}
			else if( $this->action == 'edit' ) {
				$accompagnementcui66_id = $id;
				$accompagnementcui66 = $this->Accompagnementcui66->find(
					'first',
					array(
						'conditions' => array(
							'Accompagnementcui66.id' => $accompagnementcui66_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);
				$this->set( 'decisioncui66', $accompagnementcui66 );

				$cui_id = Set::classicExtract( $accompagnementcui66, 'Accompagnementcui66.cui_id' );
			}


			// CUI en lien avec la proposition
			$cui = $this->Accompagnementcui66->Cui->find(
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


			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

			$dossier_id = $this->Accompagnementcui66->Cui->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			// On récupère l'utilisateur connecté et qui exécute l'action
			$userConnected = $this->Session->read( 'Auth.User.id' );
			$this->set( compact( 'userConnected' ) );

            $this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'accompagnementscuis66', 'action' => 'index', $cui_id ) );
			}

			if ( !empty( $this->request->data ) ) {
                $this->Accompagnementcui66->begin();

				if( $this->Accompagnementcui66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Accompagnementcui66->save( $this->request->data );

					if( $saved ) {
						$this->Accompagnementcui66->commit();
                        $this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'accompagnementscuis66', 'action' => 'index', $cui_id ) );
					}
					else {
						$this->Accompagnementcui66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				if( $this-> action == 'edit' ){
					$this->request->data = $accompagnementcui66;
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/cuis/index/'.$personne_id );
			$this->render( 'add_edit' );
        }


		/**
		 * Imprime un document pour les périodes d'immersion liées au CUI.
		 *
		 * @param integer $id L'id de la période d'immersion que l'on veut imprimer
		 * @return void
		 */
		public function impression( $id ) {
			$pdf = $this->Accompagnementcui66->getDefaultPdf( $id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'periodeimmersion_%d_%d.pdf', $id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la période d\'immersion.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}


		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->_setOptions();
			$this->Default->view( $id );
		}
    }
?>
