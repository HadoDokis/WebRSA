<?php
    class RessourcesController extends AppController
    {

        var $name = 'Ressources';
        var $uses = array( 'Ressource',  'Option' , 'Personne', 'Ressourcemensuelle',  'Detailressourcemensuelle');
        
		var $commeDroit = array(
			'view' => 'Ressources:index',
			'add' => 'Ressources:edit'
		);

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'natress', $this->Option->natress() );
            $this->set( 'abaneu', $this->Option->abaneu() );
            return $return;
        }

        /**
        *
        *
        *
        */

        function index( $personne_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

//             $nPersonnes = $this->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
//             debug( $nPersonnes );
//             $this->assert( ( $nPersonnes == 1 ), 'invalidParameter' );

			$this->Ressource->Personne->unbindModelAll();
			$this->Ressource->Ressourcemensuelle->unbindModel( array( 'belongsTo' => array( 'Ressource' ) ) );
            $ressources = $this->Ressource->find(
                'all',
                array(
                    'conditions' => array(
                        'Ressource.personne_id' => $personne_id
                    ),
					'recursive' => 2
                )
            ) ;

            $this->set( 'ressources', $ressources );
            $this->set( 'personne_id', $personne_id );
        }

        /**
        *
        *
        *
        */

        function view( $ressource_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $ressource_id ), 'invalidParameter' );

            $ressource = $this->Ressource->findById( $ressource_id, null, null, 2 );
            $this->assert( !empty( $ressource ), 'invalidParameter' );

            $this->set( 'ressource', $ressource );
            $this->set( 'personne_id', $ressource['Ressource']['personne_id'] );
        }

        /**
        *
        *
        *
        */

        function add( $personne_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $personne = $this->Personne->findById( $personne_id, null, null, -1 );
            $this->assert( !empty( $personne ), 'invalidParameter' );

            $dossier_id = $this->Personne->dossierId( $personne_id );

            $this->Ressource->begin();
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Ressource->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];
                $this->Ressource->set( $this->data['Ressource'] );

                $validates = $this->Ressource->validates();
                if( isset( $this->data['Ressourcemensuelle'] ) && isset( $this->data['Detailressourcemensuelle'] ) ) {
                    $validates = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                    $validates = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                }

                if( $validates ) {
                    $this->Ressource->begin();
                    $saved = $this->Ressource->save( $this->data );
                    if( isset( $this->data['Ressourcemensuelle'] ) ) {
                        foreach( $this->data['Ressourcemensuelle'] as $index => $dataRm ) {
                            $dataRm['ressource_id'] = $this->Ressource->id;
                            $this->Ressourcemensuelle->create();
                            $saved = $this->Ressourcemensuelle->save( $dataRm ) && $saved;
                            if( isset( $this->data['Detailressourcemensuelle'] ) ){
                                $dataDrm = $this->data['Detailressourcemensuelle'][$index];
                                $dataDrm['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                                $this->Detailressourcemensuelle->create();
                                $saved = $this->Detailressourcemensuelle->save( $dataDrm ) && $saved;
                            }
                        }
                    }
                    if( $saved ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Ressource->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'ressources', 'action' => 'index', $personne_id ) );

                    }
                    else {
                        $this->Ressource->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }

            $ressource = $this->Ressource->findByPersonneId( $personne_id, null, null, -1 );

            $this->Ressource->commit();
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        *
        *
        */

        function edit( $ressource_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $ressource_id ), 'invalidParameter' );

            $ressource = $this->Ressource->findById( $ressource_id, null, null, 2 );
            $this->assert( !empty( $ressource ), 'invalidParameter' );

            $dossier_id = $this->Ressource->dossierId( $ressource_id );

            $this->Ressource->begin();
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Ressource->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            if( !empty( $this->data ) ) {
                $this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];

                $this->Ressource->set( $this->data );
//                 $this->Ressourcemensuelle->set( $this->data );
//                 $this->Detailressourcemensuelle->set( $this->data );

                $validates = $this->Ressource->validates();
// debug( $this->Ressource->validationErrors );
// debug( $this->data['Ressource'] ); // FIXME: pourquoi ne validates pas ?
// debug( $validates );
                if( array_key_exists( 'Ressourcemensuelle', $this->data ) ) {
                    $validates = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                    if( array_key_exists( 'Detailressourcemensuelle', $this->data ) ) {
                        $validates = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $validates;
                    }
                }
// debug( $this->data );
                if( $validates ) {
                    $this->Ressource->begin();
                    $saved = $this->Ressource->save( $this->data );
                    if( !$this->data['Ressource']['topressnul'] ) {
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

                    $saved = $this->Ressource->refresh( $ressource['Ressource']['personne_id'] ) && $saved;

                    if( $saved ) {
                        $this->Jetons->release( $dossier_id );
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
					if( isset( $rm['Detailressourcemensuelle'][0] ) ) {
						$ressource['Detailressourcemensuelle'][$kRm] = $rm['Detailressourcemensuelle'][0];
					}
                    unset( $ressource['Ressourcemensuelle'][$kRm]['Detailressourcemensuelle'] );
                }

                $this->data = $ressource;
            }

            $this->Ressource->commit();
            $this->set( 'personne_id', $ressource['Ressource']['personne_id'] );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
