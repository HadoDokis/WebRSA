<?php

	class Proposorientationscovs58Controller extends AppController {
		
		public $name = "Proposorientationscovs58";
		
		public $helpers = array( 'Default', 'Default2' );
		
		protected function _setOptions() {
			$this->set( 'referents', $this->Propoorientationcov58->Referent->listOptions() );
			$this->set( 'typesorients', $this->Propoorientationcov58->Typeorient->listOptions() );
			$this->set( 'structuresreferentes', $this->Propoorientationcov58->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
		}
		
		public function beforeFilter() {
			return parent::beforeFilter();
		}
		
		public function add( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
			}

			$dossier_id = $this->Propoorientationcov58->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Propoorientationcov58->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Propoorientationcov58->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ) {
				$saved = true;
				$this->Propoorientationcov58->create();
				
				$this->data['Propoorientationcov58']['personne_id'] = $personne_id;
				$this->data['Propoorientationcov58']['rgorient'] = $this->Propoorientationcov58->Personne->Orientstruct->rgorientMax($personne_id);
				
				$saved = $this->Propoorientationcov58->save( $this->data['Propoorientationcov58'] ) && $saved;
				
				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Propoorientationcov58->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					$this->Propoorientationcov58->rollback();
				}
			}
			else {
				$personne = $this->Propoorientationcov58->Personne->findByid( $personne_id, null, null, 0 );
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}
		
	}

?>