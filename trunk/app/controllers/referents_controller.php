<?php
    class ReferentsController extends AppController
    {

        var $name = 'Referents';
        var $uses = array( 'Referent', 'Structurereferente', 'Option' );
        var $helpers = array( 'Xform' );
        
		var $commeDroit = array(
			'add' => 'Referents:edit'
		);

        public $components = array(
            'Prg' => array(
                'actions' => array( 'liste_demande_reorient' )
            )
        );


        function beforeFilter() {
            $return = parent::beforeFilter();

            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'fonction_pers', $this->Option->fonction_pers() );
            $this->set( 'referent', $this->Referent->find( 'list' ) );

            $options = array();
            foreach( array( 'Structurereferente' ) as $linkedModel ) {
                $field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
            }

            $this->set( compact( 'options' ) );

            return $return;
        }


        function index() {
            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
                $this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
            }

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );
            $referents = $this->Referent->find(
                'all',
                array(
                    'recursive' => -1
                )

            );
            $this->set('referents', $referents);
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

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

		/**
		*
		*/

        function _add_edit() {
            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );

            $args = func_get_args();
			//$this->Default->{$this->action}( $args );
			call_user_func_array( array( $this->Default, $this->action ), $args );
		}

        /*function add() {
            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );

            if( !empty( $this->data ) ) {
                if( $this->Referent->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
//                     $this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
                }
            }



            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $referent_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $referent_id ), 'error404' );

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    )
                )
            );
            $this->set( 'sr', $sr );

            if( !empty( $this->data ) ) {
                if( $this->Referent->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
                }
            }
            else {
                $referent = $this->Referent->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Referent.id' => $referent_id,
                        )
                    )
                );
                $this->data = $referent;
            }

            $this->render( $this->action, null, 'add_edit' );
        }*/

        function delete( $referent_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $referent_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Recherche de la personne
            $referent = $this->Referent->find(
                'first',
                array( 'conditions' => array( 'Referent.id' => $referent_id )
                )
            );

            // Mauvais paramètre
            if( empty( $referent_id ) ) {
                $this->cakeError( 'error404' );
            }

            // Tentative de suppression ... FIXME
            if( $this->Referent->delete( array( 'Referent.id' => $referent_id ) ) ) {
                $this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
                $this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
            }
        }

		/**
		* FIXME: à mettre dans le modèle
		*/

        /**
        *   Liste des demandes de réorientation par référents
        */

        public function liste_demande_reorient( $operations = array() ) {
            if( !empty( $this->data ) ) {
                $referents = $this->Default->search( $operations, $this->data );
            }
        }

		protected function _demandes_reorient( $conditions ) {
			//$this->{$this->modelClass}->Demandereorient->Ep->unbindModelAll();
			$demandes = $this->{$this->modelClass}->Demandereorient->find(
				'all',
				array(
					'conditions' => $conditions,
					'recursive' => 2
				)
			);

			if( !empty( $demandes ) ) {
				foreach( Set::flatten( $demandes ) as $path => $value ) {
					if( !empty( $value ) && preg_match( "/(?<!\w)(Structurereferente)(\.|\.[0-9]+\.)(typeorient_id)$/", $path, $matches ) ) {
						$newPath = preg_replace( "/(?<!\w)(Structurereferente)(\.|\.[0-9]+\.)(typeorient_id)$/", 'Typeorient', $path );
						$typeorient = $this->{$this->modelClass}->Structurereferente->Typeorient->findById( $value, null, null, -1 );
						$demandes = Set::insert( $demandes, $newPath, Set::extract( $typeorient, 'Typeorient' ) );
					}
				}
			}

			return $demandes;
		}

		/**
		*
		*/

		public function demandes_reorient( $referent_id ) {
			$demandes_origine = $this->_demandes_reorient(
				array(
					'Demandereorient.vx_referent_id' => $referent_id/*,
					'Demandereorient.statut <' => 3,*/
				)
			);

			//
			/*$precosreorient = $this->Precoreorient->find(
				'list',
				array(
					'fields' => array(
						'Precoreorient.id',
						'Precoreorient.demandereorient_id',
					),
					'conditions' => array(
						'OR' => array(
							'Demandereorient.vx_referent_id' => $referent_id,
							'Precoreorient.referent_id' => $referent_id,
						),
						'OR' => array(
							'Precoreorient.rolereorient' => 'equipe',
							'Precoreorient.rolereorient' => 'conseil'
						)
					),
					'recursive' => 0
				)
			);*/

			$demandes_destination = array();
			if( !empty( $precosreorient ) ) {
				$demandes_destination = $this->_demandes_reorient(
					array(
						'Demandereorient.nv_referent_id' => $referent_id/*,
						'Demandereorient.statut <' => 3,*/
					)
				);
			}

			//
			$this->set( compact( 'demandes_origine', 'demandes_destination' ) );
		}
    }

?>
