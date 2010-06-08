<?php
	class SeancesepsController extends AppController
	{
		/**
		*
		*/

		public $name = 'Seanceseps';

		/**
		*
		*/

		public $uses = array( 'Seanceep', 'Propositionnombredossiersep', 'Typeorient', 'Structurereferente', 'Referent' );

		/**
		* @access public
		*/

		public $components = array(
			'Default',
			'Prg' => array( 'actions' => array( 'index' ) )
		);

        /**
        *
        */

        protected function _options() {
            $options = $this->{$this->modelClass}->enums();
            $options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->find( 'list' );
            $options[$this->modelClass]['structurereferente_id'] = $this->{$this->modelClass}->Structurereferente->find( 'list' );

            return $options;
        }

		/**
		*
		*/

		public function index() {
            $this->set( 'options', $this->_options() );

			$this->{$this->modelClass}->recursive = 0;
			$this->Default->search(
				array(
					'Seanceep.dateseance' => 'BETWEEN'
				)
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

		public function ordre( $id = null ) {
			$this->{$this->modelClass}->recursive = -1;

			if( !empty( $this->data ) ) {
				$this->{$this->modelClass}->begin();
				if( $success = $this->Propositionnombredossiersep->saveAll( $this->data['Demandereorient'], array( 'validate' => 'only' ) ) ) {
					$this->{$this->modelClass}->Demandereorient->updateAll(
						array( 'Demandereorient.seanceep_id' => NULL ),
						array(
							'Demandereorient.seanceep_id' => $id
						)
					);

					foreach( $this->data['Demandereorient'] as $zonegeographique ) {
						$success = $this->{$this->modelClass}->Demandereorient->marquerAtraiterParZonegeographique(
							$id,
							$zonegeographique['numcomptt'],
							$zonegeographique['limit']
						) && $success;
					}
				}

				if( $success ) {
					$this->{$this->modelClass}->commit();
					$this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
				}
			}

			$demandesreorient = $this->{$this->modelClass}->Demandereorient->countAtraiterParZonegeographique( $id );
			foreach( $demandesreorient as $i => $demandereorient ) {
				$this->data['Demandereorient'][$i] = array(
					'limit' => $demandereorient['Demandereorient']['limit'],
					'numcomptt' => $demandereorient['Adresse']['numcomptt'],
					'locaadr' => $demandereorient['Adresse']['locaadr'],
				);
			}

			$this->Default->view( $id );
		}

		/**
		*
		*/

		public function equipe( $id = null ) {
			$demandesreorient = $this->{$this->modelClass}->Demandereorient->find(
				'all',
				array(
					'conditions' => array(
						'Demandereorient.seanceep_id' => $id
					),
					'order' => array(
						"{$this->{$this->modelClass}->Demandereorient->alias}.urgent DESC", // INFO: d'abord les urgents
						"{$this->{$this->modelClass}->Demandereorient->alias}.created ASC"
					)
				)
			);

			$step = 'equipe';

			$options = Set::merge( $this->{$this->modelClass}->enums(), $this->{$this->modelClass}->Demandereorient->enums(), $this->{$this->modelClass}->Demandereorient->Decisionreorientequipe->enums() );
			$options = Set::insert( $options, "Decisionreorient{$step}.nv_typeorient_id", $this->Typeorient->listOptions() );
			$options = Set::insert( $options, "Decisionreorient{$step}.nv_structurereferente_id", $this->Structurereferente->list1Options() );
			$options = Set::insert( $options, "Decisionreorient{$step}.nv_referent_id", $this->Referent->listOptions() );

			$this->set( compact( 'demandesreorient', 'options', 'step' ) );
		}

		/**
		*
		*/

		public function _add_edit( $id = null ) {
            $options = $this->_options();
            $this->set( 'options', $options );
			$this->{$this->modelClass}->recursive = -1;
            $this->Default->_add_edit( $id, null, null, array( 'action' => 'index' ) );
//             $this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id = null ) {
			$this->Default->delete( $id, array( 'action' => 'index' ) );
		}
	}
?>