<?php
    class RessourcesController extends AppController
    {

        var $name = 'Ressources';
        var $uses = array( 'Ressource',  'Option' , 'Personne', 'Ressourcemensuelle',  'Detailressourcemensuelle',);


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'natress', $this->Option->natress() );
            $this->set( 'abaneu', $this->Option->abaneu() );
        }


        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            $ressources = $this->Ressource->find(
                'all',
                array(
                    'conditions' => array(
                        'Ressource.personne_id' => $personne_id
                    )
                )
            ) ;


            // TODO: si personne n'existe pas -> 404
            $this->set( 'ressources', $ressources );
            //$this->set( 'ressourcemensuelle_id', $ressourcemensuelle_id );
            $this->set( 'personne_id', $personne_id );
        }



        function view( $ressource_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $ressource_id ) ) {
                $this->cakeError( 'error404' );
            }

            $ressource = $this->Ressource->find(
                'first',
                array(
                    'conditions' => array(
                        'Ressource.id' => $ressource_id
                    ),
                    'recursive' => 2
                )
            ) ;
               // $this->data = $ressource;

        // TODO: si personne n'existe pas -> 404
            $this->set( 'ressource', $ressource );
            $this->set( 'personne_id', $ressource['Ressource']['personne_id'] );
        }



        function add( $personne_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }
            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->Ressource->set( $this->data );
                $this->Ressourcemensuelle->set( $this->data );
                $this->Detailressourcemensuelle->set( $this->data );

                $validates = $this->Ressource->validates();
                $validates = $this->Ressourcemensuelle->validates() && $validates;
                $validates = $this->Detailressourcemensuelle->validates() && $validates;

                if( $validates ) {
                    $this->Ressource->begin();
                    $saved = $this->Ressource->save( $this->data );
                    foreach( $this->data['Ressourcemensuelle'] as $index => $dataRm ) {
                        // FIXME: new Ressourcemensuelle et new Detailressourcemensuelle
//                         if( isset( $this->data['Ressourcemensuelle'] ) ){ 
                            $dataRm['ressource_id'] = $this->Ressource->id;
                            $this->Ressourcemensuelle->create();
                            $saved = $this->Ressourcemensuelle->save( $dataRm ) && $saved;
    
                            if( isset( $this->data['Detailressourcemensuelle'] ) ){ 
                                $dataDrm = $this->data['Detailressourcemensuelle'][$index];
                                $dataDrm['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                                $this->Detailressourcemensuelle->create();
                                $saved = $this->Detailressourcemensuelle->save( $dataDrm ) && $saved;
                            }
//                         }
                    }
                    if( $saved ) {
                        $this->Ressource->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $personne_id ) );

                    }
                    else {
                        $this->Ressource->rollback();
                        // FIXME: error 500 ?
                    }
                }
            }
//             if( !empty( $this->data ) && $this->Ressourcemensuelle->saveAll( $this->data ) ) {
//                 $this->Session->setFlash( 'Enregistrement effectué' );
// //                 $this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $personne_id ) );
//             }

            $ressource = $this->Ressource->find(
                    'first',
                    array(
                        'conditions'=> array( 'Ressource.personne_id' => $personne_id )
                    )
                );

            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }



        function edit( $ressource_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $ressource_id ) ) {
                $this->cakeError( 'error404' );
            }

            $ressource = $this->Ressource->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Ressource.id' => $ressource_id
                        ),
                        'recursive' => 2

                    )
                );

            $this->set( 'personne_id', $ressource['Ressource']['personne_id'] );
            // TODO -> 404
            if( !empty( $this->data ) ) {
                $this->Ressource->set( $this->data );
                $this->Ressourcemensuelle->set( $this->data );
                $this->Detailressourcemensuelle->set( $this->data );

                $validates = $this->Ressource->validates();
                $validates = $this->Ressourcemensuelle->validates() && $validates;
                $validates = $this->Detailressourcemensuelle->validates() && $validates;

                if( $validates ) {
// debug( $this->data );
                    $this->Ressource->begin();
                    $saved = $this->Ressource->save( $this->data );
                    foreach( $this->data['Ressourcemensuelle'] as $index => $dataRm ) {
                        $this->Ressourcemensuelle->create();
                        $saved = $this->Ressourcemensuelle->save( $dataRm ) && $saved;

                        $dataDrm = $this->data['Detailressourcemensuelle'][$index];
                        $this->Detailressourcemensuelle->create();
                        $saved = $this->Detailressourcemensuelle->save( $dataDrm ) && $saved;
                    }
                    if( $saved ) {
                        $this->Ressource->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $ressource['Ressource']['personne_id'] ) );

                    }
                    else {
                        $this->Ressource->rollback();
                        // FIXME: error 500 ?
                    }
                }
//                 if( $this->Ressourcemensuelle->saveAll( $this->data ) ) {
//                    // debug( 'w00t' );
//
//                     $this->Session->setFlash( 'Enregistrement effectué' );
//                     //$this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $personne_id  ) );
//                 }

            }
            else {
                // FIXME !!!! ça marche, mais c'est un hack
                $ressource['Detailressourcemensuelle'] = array();
                foreach( $ressource['Ressourcemensuelle'] as $kRm => $rm ) {
                    $ressource['Detailressourcemensuelle'][$kRm] = $rm['Detailressourcemensuelle'][0];
                    unset( $ressource['Ressourcemensuelle'][$kRm]['Detailressourcemensuelle'] );
                }

                $this->data = $ressource;
        }
        $this->render( $this->action, null, 'add_edit' );
    }
}
?>
