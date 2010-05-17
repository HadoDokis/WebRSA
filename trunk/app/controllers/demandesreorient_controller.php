<?php
	class DemandesreorientController extends AppController
	{
		var $name = 'Demandesreorient';

		var $uses = array( 'Demandereorient', 'Typeorient', 'Structurereferente', 'Referent', 'Personne' );

		/**
		*
		*/

		function beforeFilter() {
			$return = parent::beforeFilter();

			$step = 'referent';
			$options = array();
			$options = Set::insert( $options, "Precoreorient{$step}.typeorient_id", $this->Typeorient->listOptions() );
			$options = Set::insert( $options, "Precoreorient{$step}.structurereferente_id", $this->Structurereferente->list1Options() );
			$options = Set::insert( $options, "Precoreorient{$step}.referent_id", $this->Referent->find( 'list' ) );

			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}

			foreach( array( 'Ep', 'Motifdemreorient'/*, 'Orientstruct'*/ ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}

			$referent_id = $this->Referent->find( 'list' );
			foreach( array( 'reforigine_id' ) as $field ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $referent_id );
			}


            $this->set( compact( 'options' ) );
			return $return;
		}


        /**
        *
        */

        public function indexparams() {
            $this->Default->index();
        }
		/**
		*
		*/

		public function index( $personne_id ) {
            // Préparation du menu du dossier
            $dossierId = $this->Demandereorient->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossierId ), 'invalidParameter' );
            $this->set( compact( 'dossierId' ) );

			$this->Default->index();
		}

		/**
		*
		*/

        public function add( $orientstruct_id ) {
            $args = func_get_args();
            // Préparation du menu du dossier
           /* $orientstruct = $this->Demandereorient->Orientstruct->findById( $orientstruct_id );
            $this->assert( !empty( $orientstruct ), 'invalidParameter' );
            $personne_id = Set::classicExtract( $orientstruct, 'Orientstruct.personne_id' );
            $dossierId = $this->Demandereorient->Personne->dossierId( $personne_id );

            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'fields' => array(
                        'Personne.qual',
                        'Personne.nom',
                        'Personne.prenom',
                    ),
                    'recursive' => -1
                )
            );

            $this->set( compact( 'dossierId', 'orientstruct_id', 'personne_id', 'personne' ) );*/
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

		/**
		*
		*/

        public function edit( $demandereorient_id ) {
            $args = func_get_args();

			/*$personne_id = $this->{$this->modelClass}->field( 'personne_id', array( 'Demandereorient.id' => $demandereorient_id ) );
            $orientstruct_id = $this->{$this->modelClass}->field( 'orientstruct_id', array( 'Demandereorient.id' => $demandereorient_id ) );


            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'fields' => array(
                        'Personne.qual',
                        'Personne.nom',
                        'Personne.prenom',
                    ),
                    'recursive' => -1
                )
            );


            // Préparation du menu du dossier
            $dossierId = $this->Demandereorient->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossierId ), 'invalidParameter' );

			$this->set( compact( 'dossierId', 'personne_id', 'orientstruct_id', 'personne' ) );*/
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

		protected function _detailsPersonne( $personne_id ) {
			$this->Personne->unbindModelAll();
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );

            $foyer = $this->Personne->Foyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Foyer.id' => Set::classicExtract( $personne, 'Personne.foyer_id' )
                    ),
                    'recursive' => -1
                )
            );

            $dossier = $this->Personne->Foyer->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => Set::classicExtract( $foyer, 'Foyer.dossier_rsa_id' )
                    ),
                    'recursive' => -1
                )
            );

            $adresse = $this->Personne->Foyer->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => Set::classicExtract( $foyer, 'Foyer.id' ),
						'Adressefoyer.rgadr' => '01'
                    ),
                    'recursive' => 0
                )
            );

			return Set::merge( $personne, $foyer, $dossier, $adresse );
		}

		/**
		*
		*/

        function _add_edit() {
            $args = func_get_args();

            if( Set::check( $this->params, 'form.cancel' ) ) {
                $this->Session->setFlash( __( 'Save->cancel', true ), 'flash/information' );
                $this->redirect( array( 'action' => 'index' ) );
            }

            if( $this->action == 'edit' ) {
                $demandereorient_id = $args[0];
				$demandereorient = $this->{$this->modelClass}->findById( $demandereorient_id, null, null, -1 );
				$referent_origine = $this->Personne->Referent->findById( Set::classicExtract( $demandereorient, 'Demandereorient.reforigine_id' ) ); // FIXME

                $personne_id = $this->{$this->modelClass}->field( 'personne_id', array( 'Demandereorient.id' => $demandereorient_id ) );
                $orientstruct_id = $this->{$this->modelClass}->field( 'orientstruct_id', array( 'Demandereorient.id' => $demandereorient_id ) );

                $orientstruct = $this->Demandereorient->Orientstruct->findById( $orientstruct_id );
                $this->assert( !empty( $orientstruct ), 'invalidParameter' );

                $item = $this->{$this->modelClass}->findById( $demandereorient_id, null, null, 1 );
                $this->assert( !empty( $item ), 'invalidParameter' );

                $varname = Inflector::variable( Inflector::singularize( $this->name ) );
                $this->set( $varname, $item );
            }
            else {
                $orientstruct_id = $args[0];
                $orientstruct = $this->Demandereorient->Orientstruct->findById( $orientstruct_id );
                $this->assert( !empty( $orientstruct ), 'invalidParameter' );

                $personne_id = Set::classicExtract( $orientstruct, 'Orientstruct.personne_id' );
				$referent_origine = $this->Personne->Referent->readByPersonneId( $personne_id ); // FIXME

                $dossierId = $this->Demandereorient->Personne->dossierId( $personne_id );
            }

            $this->{$this->modelClass}->begin();
            if( !empty( $this->data ) ) {
                $this->data = Xset::filterDeep( $this->data );
                $this->{$this->modelClass}->create( $this->data );
                $this->{$this->modelClass}->Precoreorientreferent->create( $this->data );

                $validates = $this->{$this->modelClass}->validates();
				$validates = $this->{$this->modelClass}->Precoreorientreferent->validates() && $validates;

                $saved = false;
                if( $validates ) {
                    $saved = true;
                    $saved = $this->{$this->modelClass}->save() && $saved;
                    $this->{$this->modelClass}->Precoreorientreferent->set( 'demandereorient_id', $this->{$this->modelClass}->id );
                    $saved = $this->{$this->modelClass}->Precoreorientreferent->save() && $saved;
                }

                if( $saved ) {
                    $this->{$this->modelClass}->commit();
                    $this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
                    $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
                }
                else {
                    $this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
                    $this->{$this->modelClass}->rollback();
                }
            }
            else if( $this->action == 'edit' ) {
                $this->data = $item;
            }

			$personne = $this->_detailsPersonne( $personne_id );

            $dossierId = $this->Demandereorient->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossierId ), 'invalidParameter' );

            $this->set( compact( 'dossierId', 'personne_id', 'orientstruct_id', 'personne', 'orientstruct', 'referent_origine' ) );

            $this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
            $item = $this->{$this->modelClass}->findById( $id, null, null, -1 );
            $this->assert( !empty( $item ), 'invalidParameter' );
            $personne_id = Set::classicExtract( $item, 'Demandereorient.personne_id' );

            if( $this->{$this->modelClass}->delete( $id ) ) {
                $this->Session->setFlash( __( 'Delete->success', true ), 'flash/success' );
            }
            else {
                $this->Session->setFlash( __( 'Delete->error', true ), 'flash/error' );
            }

            $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
		}

		/**
		*
		*/

		public function view( $id ) {
            $personne_id = $this->{$this->modelClass}->field( 'personne_id', array( 'Demandereorient.id' => $id ) );

            // Préparation du menu du dossier
            $dossierId = $this->Demandereorient->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossierId ), 'invalidParameter' );
            $this->set( compact( 'dossierId' ) );
			$this->Default->view( $id );
		}
	}
?>