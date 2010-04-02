<?php
    class EpsThemesController extends AppController
    {
        var $name = 'EpsThemes';
        var $uses = array( 'Ep', 'Demandereorient', 'Motifdemreorient', 'Parcoursdetecte' );
        var $components = array( 'Jetonsfonctions' );

        /**
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'motifdemreorient', $this->Motifdemreorient->find( 'list' ) );
            return $return;
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *   Ordre du jour
        */

        function _add_edit( $id = null ) {
            /// Demandereorient qui ne sont pas dans un ep ou qui sont déjà dans celle-ci
            $demandereorient = $this->Demandereorient->find(
                'all',
                array(
                    'conditions' => array(
                        'OR' => array(
                            'Demandereorient.ep_id IS NULL',
                            'Demandereorient.ep_id' => $id
                        )
                    )
                )
            );

            // Parcours qui ne sont pas dans un ep ou qui sont déjà dans celle-ci
            $parcoursdetecte = $this->Parcoursdetecte->find(
                'all',
                array(
                    'recursive' => 2,
                    'conditions' => array(
                        'OR' => array(
                            'Parcoursdetecte.ep_id IS NULL',
                            'Parcoursdetecte.ep_id' => $id
                        )
                    )
                )
            );

            $this->set( compact( 'parcoursdetecte', 'demandereorient' ) );

            /// FIXME
            $themes = array( 'Demandereorient', 'Parcoursdetecte' );
            $this->Ep->begin();

            // Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Valid'] ) ) {
                $this->redirect( array( 'controller' => 'eps', 'action' => 'liste' ) );
            }

            if( $this->Jetonsfonctions->get( $this->name, $this->action ) ) {

                if( $this->action == 'add' ) {
                    $ep_id = $id;

                }
                else if( $this->action == 'edit' ) {
                    $ep_id = $id;
                    /*$demandereorient = $this->Demandereorient->find(
                        'all',
                        array(
                            'conditions' => array(
                                'Demandereorient.ep_id' => $ep_id
                            )
                        )
                    );
//                     $this->assert( !empty( $demandereorient ), 'invalidParameter' );
                    $parcoursdetecte = $this->Parcoursdetecte->find(
                        'all',
                        array(
                            'conditions' => array(
                                'Parcoursdetecte.ep_id' => $ep_id
                            )
                        )
                    );*/
                }

                if( !empty( $this->data ) ) {
//                     foreach( $this->data['Demandereorient']['Demandereorient'] as $i => $demandereorientId ) {
//                         if( empty( $demandereorientId ) ) {
//                             unset( $this->data['Demandereorient']['Demandereorient'][$i] );
//                         }
//                     }
//                     foreach( $this->data['Parcoursdetecte']['Parcoursdetecte'] as $i => $parcoursdetecteId ) {
//                         if( empty( $parcoursdetecteId ) ) {
//                             unset( $this->data['Parcoursdetecte']['Parcoursdetecte'][$i] );
//                         }
//                     }
                    $this->Jetonsfonctions->release( $this->name, $this->action );

                    $result = true;

                    foreach( $themes as $theme ) {
                        $data = Set::extract( $this->data, "/{$theme}" );
                        if( !empty( $data ) ) {
                            $result = $this->Ep->saveAll( array( $theme => $data ), array( 'atomic' => false ) ) && $result;
                        }
                    }

                    if( $result ) {
                        $this->Ep->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'eps', 'action' => 'ordre', $ep_id ) );
                    }
                    else{
                        $this->Ep->rollback();
                    }
                }
                else {
                    if( $this->action == 'edit' ) {
                        $this->data = array();
                        foreach( $themes as $theme ) {
                            $this->data = Set::insert(
                                $this->data,
                                $theme,
                                Set::classicExtract( ${strtolower($theme)}, "{n}.{$theme}" )
                            );
                        }
                        //$this->data = array( 'EpPartep' => Set::classicExtract( $eppart, '{n}.EpPartep' ) );
                        /*$this->data = array(
                            'Ep' => array(
                                'id' => $ep_id,
                            ),
                            'Demandereorient' => array(
                                'Demandereorient' => Set::extract( $demandereorient, '/Demandereorient/ep_id' )
                            ),
                            'Parcoursdetecte' => array(
                                'Parcoursdetecte' => Set::extract( $parcours, '/Parcoursdetecte/ep_id' )
                            )
                        );*/
                    }
                    /*else {
                        $this->data['Ep']['id'] = $ep_id;
                    }*/
                }
                $this->set( compact( 'ep_id' ) );
                $this->render( $this->action, null, 'add_edit' );
            }

        }
    }
?>