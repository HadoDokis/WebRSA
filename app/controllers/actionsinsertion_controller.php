<?php
    class ActionsinsertionController extends AppController
    {

        var $name = 'Actionsinsertion';
        var $uses = array( 'Actioninsertion', 'Contratinsertion', 'Aidedirecte', 'Prestform', 'Option', 'Refpresta', 'Action' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'lib_action', $this->Option->lib_action() );
            $this->set( 'actions', $this->Action->grouplist( 'aide' ) );
            $this->set( 'actions', $this->Action->grouplist( 'prest' ) );
            $this->set( 'typo_aide', $this->Option->typo_aide() );

        }

        function index( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $contratinsertion_id ), 'invalidParameter' );

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    ),
                    'recursive' => -1
                )
            );

            // Si contrat n'existe pas -> 404
            if( empty( $contratinsertion ) ) {
                $this->cakeError( 'error404' );
            }

            $actionsinsertion = $this->Actioninsertion->find(
                'all',
                array(
                    'conditions' => array(
                        'Actioninsertion.contratinsertion_id' => $contratinsertion_id
                    ),
                    'recursive' => 2
                )
            );

            $actions = $this->Action->find(
                'list',
                array(
                    'fields' => array(
                        'Action.code',
                        'Action.libelle'
                    )
                )
            );

            $this->set( 'actions', $actions );
            $this->set( 'actionsinsertion', $actionsinsertion );
            $this->set( 'contratinsertion_id', $contratinsertion_id);
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );


        }


        function edit( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $contratinsertion_id ), 'invalidParameter' );

            $contratinsertion = $this->Actioninsertion->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    ),
                    'recursive' => 2
                )
            );

            // Si action n'existe pas -> 404
            if( empty( $contratinsertion ) ) {
                $this->cakeError( 'error404' );
            }

            if( !empty( $this->data ) ) {

                if( $this->Actioninsertion->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Actioninsertion']['personne_id']) );
                }
            }
            else {

                $this->data = $contratinsertion;
            }
            $this->render( $this->action, null, 'add_edit' );



        }


}
?>