<?php
	class DemandesreorientController extends AppController
	{
		public $name = 'Demandesreorient';

		/**
		* @access public
		*/

		public $components = array( 'Default', 'Gedooo' );

		/**
		* @access public
		*/

        public $uses = array( 'Demandereorient', 'Typeorient', 'Structurereferente', 'Referent' );
        
		var $commeDroit = array(
			'view' => 'Demandesreorient:index',
			'add' => 'Demandesreorient:edit'
		);

		/**
		*
		*/

		protected function _options() {
			$options = $this->{$this->modelClass}->enums();
			$options[$this->modelClass]['motifdemreorient_id'] = $this->{$this->modelClass}->Motifdemreorient->find( 'list' );
			$options[$this->modelClass]['vx_typeorient_id'] = $options[$this->modelClass]['nv_typeorient_id'] = $this->Typeorient->listOptions();
			$options[$this->modelClass]['vx_structurereferente_id'] = $options[$this->modelClass]['nv_structurereferente_id'] = $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) );
			$options[$this->modelClass]['vx_referent_id'] = $options[$this->modelClass]['nv_referent_id'] = $this->Referent->listOptions();

			return $options;
		}

		/**
		*
		*/

		public function index() {
			$this->{$this->modelClass}->recursive = 0;
			$this->Default->search(
				$this->data
			);
		}

		/**
		*
		*/

		public function view( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;
			$this->Default->view( $id );
		}

		/**
		*
		*/

		public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit( $id = null ) {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function _add_edit( $id = null ) {
			$options = $this->_options();
			$this->set( 'options', $options );

			if( !empty( $this->data ) ) {
				$this->{$this->modelClass}->begin();

				$this->{$this->modelClass}->create( $this->data );
				$saved = $this->{$this->modelClass}->save();
				$nvOrientstructId = $this->{$this->modelClass}->Orientstruct->getLastInsertId();
				if( !empty( $nvOrientstructId ) ) {
					$generationPdf = $this->Gedooo->mkOrientstructPdf( $nvOrientstructId );
					$saved = $generationPdf && $saved;
				}

				if( $saved ) {
					$this->{$this->modelClass}->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $this->data[$this->modelClass]['personne_id'] ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					if( isset( $generationPdf ) && !$generationPdf ) {
						$this->Session->setFlash( 'Erreur lors de la génération du document PDF (le serveur Gedooo est peut-être tombé ou mal configuré)', 'flash/error' );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				if( $this->action == 'add' ) {
					$orientstruct = $this->{$this->modelClass}->Orientstruct->findById( $id, null, null, -1 );
					$this->assert( !empty( $orientstruct ), 'invalidParameter' );

					$referent_id = $orientstruct['Orientstruct']['referent_id'];
					if( empty( $referent_id ) ) {
						$referent = $this->{$this->modelClass}->VxReferent->readByPersonneId( $orientstruct['Orientstruct']['personne_id'] );
						$referent_id = $referent['Referent']['id'];
					}

					$this->data[$this->modelClass] = array(
						'orientstruct_id' => $orientstruct['Orientstruct']['id'],
						'personne_id' => $orientstruct['Orientstruct']['personne_id'],
						'vx_typeorient_id' => $orientstruct['Orientstruct']['typeorient_id'],
						'vx_structurereferente_id' => $orientstruct['Orientstruct']['structurereferente_id'],
						'vx_referent_id' => $referent_id,
						'accordconcertation' => 'attente',
						'datepremierentretien' => date( 'Y-m-d' )
					);
				}
				else {
					$this->data = $this->{$this->modelClass}->findById( $id, null, null, -1 );
				}
			}

			$this->set( 'personne_id', $this->data[$this->modelClass]['personne_id'] );
            $this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id = null ) {
			$this->Default->delete( $id, array( 'action' => 'index' ) );
		}
	}
?>
