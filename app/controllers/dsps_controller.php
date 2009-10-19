<?php
	class DspsController extends AppController
	{
		var $name = 'Dsps';

		var $helpers = array( 'Xform', 'Xhtml' );

		var $components = array( 'Jetons' );

		/** ********************************************************************
		*
		*** *******************************************************************/

		function beforeFilter() {
			$return = parent::beforeFilter();

			$options = $this->Dsp->allEnumLists();
			$this->set( 'options', $options );

			return $return;
		}

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function view( $id = null ) {
			$dsp = $this->Dsp->findByPersonneId( $id );
			if( empty( $dsp ) ) {
				$dsp = $this->Dsp->Personne->findById( $id );
			}
			$this->assert( !empty( $dsp ), 'invalidParameter' );
			$this->set( 'dsp', $dsp );
        }


		/** ********************************************************************
		*
		*** *******************************************************************/

		function _add_edit( $id = null ) {
			// Début de la transaction
			$this->Dsp->begin();

			// On cherche soit la dsp directement, soit la personne liée
			$dsp = null;
			if( ( $this->action == 'edit' ) && !empty( $id ) ) {
				$dsp = $this->Dsp->findById( $id );
			}
			else if( ( $this->action == 'add' ) && !empty( $id ) ) {
				$dsp = $this->Dsp->Personne->findById( $id, null, null, -1 );
			}

			// Vérification indirecte de l'id
			$this->assert( !empty( $dsp ), 'invalidParameter' );

			// Assertion: on doit pouvoir mettre un jeton sur le dossier
			$dossier_id = $this->Dsp->Personne->dossierId( Set::classicExtract( $dsp, 'Personne.id' ) );
			$hasJeton = $this->Jetons->get( $dossier_id );
			$this->assert( $hasJeton, 'lockedDossier' );

			// Tentative d'enregistrement
			if( !empty( $this->data ) ) {
				$this->Dsp->create( $this->data );
                $this->Dsp->nullify( array( 'exceptions' => array( 'Dsp.'.$this->Dsp->primaryKey ) ) );
				if( $this->Dsp->save() ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ) );
					// On enlève le jeton du dossier
					$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) ); // FIXME: if -> error
					// Fin de la transaction
					$this->Dsp->commit();
					$this->redirect( array( 'action' => 'view', Set::classicExtract( $this->data, 'Dsp.personne_id' ) ) );
				}
			}
			// Affectation au formulaire
			else if( $this->action == 'edit' ) {
				$this->data = $dsp;
			}

			// Fin de la transaction
			$this->Dsp->commit();

			// Affectation à la vue
			$this->set( 'dsp', $dsp );
            $this->render( $this->action, null, '_add_edit' );
		}
	}
?>