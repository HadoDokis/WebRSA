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


        function index( $personne_id = null ) {
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
                $this->Ressource->set( $this->data['Ressource'] );

                $validates = $this->Ressource->validates();
                if( isset( $this->data['Ressourcemensuelle'] ) && isset( $this->data['Detailressourcemensuelle'] ) ) {
                    $validates = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                    $validates = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                }

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
//                 $this->Ressourcemensuelle->set( $this->data );
//                 $this->Detailressourcemensuelle->set( $this->data );

                $validates = $this->Ressource->validates();
                if( array_key_exists( 'Ressourcemensuelle', $this->data ) ) {
                    $validates = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                    if( array_key_exists( 'Detailressourcemensuelle', $this->data ) ) {
                        $validates = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                    }
                }

                if( $validates ) {
                    $this->Ressource->begin();
                    $saved = $this->Ressource->save( $this->data );
                    if( $this->data['Ressource']['topressnul'] ) { // FIXME ? ->  la signification, ce ne serait pas le contraire ?
                        if( array_key_exists( 'Ressourcemensuelle', $this->data ) ) {
                            foreach( $this->data['Ressourcemensuelle'] as $index => $dataRm ) {
                                $this->Ressourcemensuelle->create();
                                $dataRm['ressource_id'] = $this->Ressource->id;
                                $saved = $this->Ressourcemensuelle->save( $dataRm ) && $saved;

                                if( array_key_exists( 'Detailressourcemensuelle', $this->data ) ) {
                                    $dataDrm = $this->data['Detailressourcemensuelle'][$index];
                                    $dataDrm['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                                    $this->Detailressourcemensuelle->create();
                                    $saved = $this->Detailressourcemensuelle->save( $dataDrm ) && $saved;
                                }
                            }
                        }
                    }
                    else {
                        $rm = $this->Ressourcemensuelle->find(
                            'list',
                            array(
                                'fields' => array( 'Ressourcemensuelle.id' ),
                                'conditions' => array( 'Ressourcemensuelle.ressource_id' => $this->Ressource->id )
                            )
                        );
                        if( !empty( $rm ) ) {
                            $saved = $this->Detailressourcemensuelle->deleteAll(
                                array(
                                    'Detailressourcemensuelle.ressourcemensuelle_id' => $rm
                                )
                            ) && $saved;

                            $saved = $this->Ressourcemensuelle->deleteAll(
                                array(
                                    'Ressourcemensuelle.id' => $rm
                                )
                            ) && $saved;
                        }
                    }

                    if( $saved ) {
                        $this->Ressource->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $ressource['Ressource']['personne_id'] ) );

                    }
                    else {
                        $this->Ressource->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }

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
            // TODO: toppersdrodevorsa
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
