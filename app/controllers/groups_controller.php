<?php
    class GroupsController extends AppController
    {

        var $name = 'Groups';
        var $uses = array( 'Group' );

        function index() {

            $groups = $this->Group->find(
                'all',
                array(
                    'recursive' => -1
                )

            );

            $this->set('groups', $groups);
        }

        function add() {

            if( !empty( $this->data ) ) {
                if( $this->Group->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
                }
            }

            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $group_id = null ) {
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $group_id ), 'error404' );

   
            if( !empty( $this->data ) ) {
                if( $this->Group->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'groups', 'action' => 'index' ) );
                }
            }
            else {
                $group = $this->Group->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Group.id' => $group_id,
                        )
                    )
                );
                $this->data = $group;
            }

            $this->render( $this->action, null, 'add_edit' );
        }

    }

?>