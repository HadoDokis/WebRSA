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
			'Gedooo',
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
		* FIXME: si on n'a rien dans la liste -> prévenir
		* FIXME: jetons
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
					$this->redirect( array( 'action' => 'index' ) );
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

		public function conseil( $seanceep_id = null ) {
			$this->_freu( $seanceep_id, 'conseil', 'cg', 'Decisionreorientconseil' );
		}

		/**
		*
		*/

		protected function _freu( $id, $step, $demandesreorient, $modelDecision ) {
			$seanceep = $this->{$this->modelClass}->findById( $id, null, null, -1 );
			$this->assert( !empty( $seanceep ), 'invalidParameter' );

			if( !empty( $this->data ) ) {
				$this->{$this->modelClass}->begin();

				if( $seanceep[$this->modelClass]['demandesreorient'] == $demandesreorient ) {
					$notEmptyRule = array(
						'rule' => 'checkDependantDecision',
						'message' => __( "Validate::notEmpty", true )
					);

					foreach( array( 'nv_typeorient_id', 'nv_structurereferente_id', 'nv_referent_id' ) as $field ) {
						$validate = $this->{$this->modelClass}->Demandereorient->{$modelDecision}->validate[$field];
						array_unshift( $validate, $notEmptyRule );
						$this->{$this->modelClass}->Demandereorient->{$modelDecision}->validate[$field] = $validate;
					}
				}

				$success = true;
				if( $seanceep[$this->modelClass]['demandesreorient'] == $demandesreorient ) {
					foreach( $this->data["Decisionreorient{$step}"] as $i => $decision ) {
						$demandereorient = $this->{$this->modelClass}->Demandereorient->findById( $decision['demandereorient_id'], null, null, -1 );

						$vxOrientstruct = array();
						if( !empty( $demandereorient['Demandereorient']['nv_orientstruct_id'] ) ) {
							$vxOrientstruct = $this->{$this->modelClass}->Demandereorient->Orientstruct->findById( $demandereorient['Demandereorient']['nv_orientstruct_id'], null, null, -1 );
						}

						// TODO: assert ?
						$orientstruct = array(
							$this->{$this->modelClass}->Demandereorient->Orientstruct->alias => array(
								'personne_id' => $demandereorient['Demandereorient']['personne_id'],
								'typeorient_id' => $decision['nv_typeorient_id'],
								'structurereferente_id' => $decision['nv_structurereferente_id'],
								'referent_id' => $decision['nv_referent_id'],
								'valid_cg' => true,
								'date_propo' => date( 'Y-m-d' ),
								'date_valid' => date( 'Y-m-d' ),
								'statut_orient' => 'Orienté',
							)
						);

						$orientstruct = Xset::bump(
							Set::merge(
								Set::flatten( $vxOrientstruct ),
								Set::flatten( $orientstruct )
							)
						);

						$this->{$this->modelClass}->Demandereorient->Orientstruct->create( $orientstruct );
						$success = $this->{$this->modelClass}->Demandereorient->Orientstruct->save() && $success;
						$demandereorient['Demandereorient']['nv_orientstruct_id'] = $this->{$this->modelClass}->Demandereorient->Orientstruct->id;

						if( $success ) {
							$success = $this->Gedooo->mkOrientstructPdf( $demandereorient['Demandereorient']['nv_orientstruct_id'] ) && $success;
						}

						$this->{$this->modelClass}->Demandereorient->create( $demandereorient );
						$success = $this->{$this->modelClass}->Demandereorient->save() && $success;
					}
				}

				$success = $this->{$this->modelClass}->Demandereorient->{$modelDecision}->saveAll( $this->data["Decisionreorient{$step}"], array( 'validate' => 'first', 'atomic' => false ) ) && $success;

				if( $success ) {
					$this->{$this->modelClass}->commit(); // FIXME
					$this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
				}
			}

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

			$options = Set::merge( $this->{$this->modelClass}->enums(), $this->{$this->modelClass}->Demandereorient->enums(), $this->{$this->modelClass}->Demandereorient->{$modelDecision}->enums() );
			$options = Set::insert( $options, "Decisionreorient{$step}.nv_typeorient_id", $this->Typeorient->listOptions() );
			$options = Set::insert( $options, "Decisionreorient{$step}.nv_structurereferente_id", $this->Structurereferente->list1Options() );
			$options = Set::insert( $options, "Decisionreorient{$step}.nv_referent_id", $this->Referent->listOptions() );

			$this->set( compact( 'demandesreorient', 'options', 'step' ) );
		}

		/**
		* FIXME: jetons
		*/

		public function equipe( $id = null ) {
			$step = 'equipe';

			$seanceep = $this->{$this->modelClass}->findById( $id, null, null, -1 );
			$this->assert( !empty( $seanceep ), 'invalidaParameter' );

			if( !empty( $this->data ) ) {
				$this->{$this->modelClass}->begin();

				if( $seanceep[$this->modelClass]['demandesreorient'] == 'decisionep' ) {
					$notEmptyRule = array(
						'rule' => 'checkDependantDecision',
						'message' => __( "Validate::notEmpty", true )
					);

					foreach( array( 'nv_typeorient_id', 'nv_structurereferente_id', 'nv_referent_id' ) as $field ) {
						$validate = $this->{$this->modelClass}->Demandereorient->Decisionreorientequipe->validate[$field];
						array_unshift( $validate, $notEmptyRule );
						$this->{$this->modelClass}->Demandereorient->Decisionreorientequipe->validate[$field] = $validate;
					}
				}

				$success = true;
				if( $seanceep[$this->modelClass]['demandesreorient'] == 'decisionep' ) {
					foreach( $this->data["Decisionreorient{$step}"] as $i => $decision ) {
						$demandereorient = $this->{$this->modelClass}->Demandereorient->findById( $decision['demandereorient_id'], null, null, -1 );

						$vxOrientstruct = array();
						if( !empty( $demandereorient['Demandereorient']['nv_orientstruct_id'] ) ) {
							$vxOrientstruct = $this->{$this->modelClass}->Demandereorient->Orientstruct->findById( $demandereorient['Demandereorient']['nv_orientstruct_id'], null, null, -1 );
						}

						// TODO: assert ?
						$orientstruct = array(
							$this->{$this->modelClass}->Demandereorient->Orientstruct->alias => array(
								'personne_id' => $demandereorient['Demandereorient']['personne_id'],
								'typeorient_id' => $decision['nv_typeorient_id'],
								'structurereferente_id' => $decision['nv_structurereferente_id'],
								'referent_id' => $decision['nv_referent_id'],
								'valid_cg' => true,
								'date_propo' => date( 'Y-m-d' ),
								'date_valid' => date( 'Y-m-d' ),
								'statut_orient' => 'Orienté',
							)
						);

						$orientstruct = Xset::bump(
							Set::merge(
								Set::flatten( $vxOrientstruct ),
								Set::flatten( $orientstruct )
							)
						);

						$this->{$this->modelClass}->Demandereorient->Orientstruct->create( $orientstruct );
						$success = $this->{$this->modelClass}->Demandereorient->Orientstruct->save() && $success;
						$demandereorient['Demandereorient']['nv_orientstruct_id'] = $this->{$this->modelClass}->Demandereorient->Orientstruct->id;

						if( $success ) {
							$success = $this->Gedooo->mkOrientstructPdf( $demandereorient['Demandereorient']['nv_orientstruct_id'] ) && $success;
						}

						$this->{$this->modelClass}->Demandereorient->create( $demandereorient );
						$success = $this->{$this->modelClass}->Demandereorient->save() && $success;
					}
				}

				$success = $this->{$this->modelClass}->Demandereorient->Decisionreorientequipe->saveAll( $this->data["Decisionreorient{$step}"], array( 'validate' => 'first', 'atomic' => false ) ) && $success;

				if( $success ) {
					$this->{$this->modelClass}->commit();
					$this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->{$this->modelClass}->rollback();
					$this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
				}
			}

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