<?php
	class Signalementseps93Controller extends Appcontroller
	{
		/**
		*
		*/

		public function add( $contratinsertion_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( $this->action == 'add' ) {
				$contratinsertion_id = $id;
				$personne_id = $this->Signalementep93->Contratinsertion->field( 'personne_id', array( 'Contratinsertion.id' => $contratinsertion_id ) );
			}
			else {
				// TODO
			}

			$this->assert( !empty( $personne_id ), 'invalidParameter' );

			if( !empty( $this->data ) ) {
				$this->Signalementep93->begin();

				$rangpcd = $this->Signalementep93->field( 'rang', array( 'Signalementep93.contratinsertion_id' => $contratinsertion_id ), array( 'Signalementep93.rang DESC' ) );
				$this->data['Signalementep93']['contratinsertion_id'] = $contratinsertion_id;
				$this->data['Signalementep93']['rang'] = ( empty( $rangpcd ) ? 1 : $rangpcd + 1 );

				$this->Signalementep93->create( $this->data );
				$success = $this->Signalementep93->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Signalementep93->commit();
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Signalementep93->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				// TODO
			}

			$this->set( 'personne_id', $personne_id );
			$this->render( null, null, 'add_edit' );
		}
	}
?>