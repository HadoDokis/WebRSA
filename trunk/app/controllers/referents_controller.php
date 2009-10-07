<?php
    class ReferentsController extends AppController
    {

        var $name = 'Referents';
        var $uses = array( 'Referent', 'Structurereferente', 'Option' );


        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'fonction_pers', $this->Option->fonction_pers() );
            return $return;
        }


        function index() {
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

        function add() {
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
                    $this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
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
        }

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
    }

?>