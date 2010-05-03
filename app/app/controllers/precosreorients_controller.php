<?php
	class PrecosreorientsController extends AppController
	{
		var $name = 'Precosreorients';
		var $uses = array( 'Precoreorient', 'Demandereorient', 'Typeorient', 'Structurereferente', 'Referent' );

		/**
		*
		*/

		function _cohorte( $step, $ep_id ) {
			/// Set options
			$options = array();
			$options = Set::insert( $options, "Precoreorient{$step}.typeorient_id", $this->Typeorient->listOptions() );
			$options = Set::insert( $options, "Precoreorient{$step}.structurereferente_id", $this->Structurereferente->list1Options() );
			$options = Set::insert( $options, "Precoreorient{$step}.referent_id", $this->Referent->find( 'list' ) );

			/// Essai de sauvegarde
			if( !empty( $this->data ) && isset( $this->data["Precoreorient{$step}"] ) ) {
				$this->Precoreorient->begin();
				$result = $this->Precoreorient->saveAll( $this->data["Precoreorient{$step}"], array( 'validate' => 'first', 'atomic' => false ) );

				//if( ( !is_array( $result ) && $result ) || ( is_array( $result ) && array_sum( $result ) == count( $result ) ) ) {
				if( $result ) {
					$this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
					$this->Precoreorient->commit();
				}
				else {
					$this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
					$this->Precoreorient->rollback();
				}
			}

			/// Recherche pour la cohorte
			$this->Demandereorient->Ep->unbindModelAll( false );

			$this->paginate = array(
				'Demandereorient' => array(
					'conditions' =>array(
						'Demandereorient.ep_id' => $ep_id
					),
					'recursive' => 2,
					'limit' => 1
				)
			);

			$precosreorient = $this->paginate( 'Demandereorient' );

			$this->set( compact( 'step', 'precosreorient', 'options' ) );

            $this->render( $this->action, null, 'index' );
		}


		/**
		*
		*/

		function index( $ep_id ) {
			$this->_cohorte( 'equipe', $ep_id );
		}

		/**
		*
		*/

		function conseil( $ep_id ) {
			$this->_cohorte( 'conseil', $ep_id );
		}
	}
?>