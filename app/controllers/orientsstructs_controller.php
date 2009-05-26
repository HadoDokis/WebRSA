<?php
    class OrientsstructsController extends AppController
    {

        var $name = 'Orientsstructs';
        var $uses = array( 'Orientstruct',  'Option' , 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Typeorient', 'Structurereferente');


        function beforeFilter() {
            parent::beforeFilter();
                $this->set( 'pays', $this->Option->pays() );
                $this->set( 'qual', $this->Option->qual() );
                $this->set( 'rolepers', $this->Option->rolepers() );
                $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
        }


        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id
                    ),
                'recursive' => 2
                )

            );


            // TODO: si personne n'existe pas -> 404
            $this->set( 'orientstruct', $orientstruct );
            $this->set( 'personne_id', $personne_id );
        }


        function add( $personne_id = null ) {

            $this->assert( valid_int( $personne_id ), 'error404' );

            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'options2', $this->Structurereferente->list1Options() );


            if( !empty( $this->data ) ) {
                $this->Orientstruct->set( $this->data );
                $this->Typeorient->set( $this->data );
                $this->Structurereferente->set( $this->data );

                $validates = $this->Orientstruct->validates();
                $validates = $this->Typeorient->validates() && $validates;
                $validates = $this->Structurereferente->validates() && $validates;


                if( $validates ) {
                    $this->Personne->begin();

                    // Orientation
                    $this->Orientstruct->create();
                    $this->data['Orientstruct']['personne_id'] = $personne_id;
                    $this->data['Orientstruct']['valid_cg'] = true;
                    $this->data['Orientstruct']['date_propo'] = date( 'Y-m-d' );
                    $this->data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
                    $this->data['Orientstruct']['statut_orient'] = 'Orienté';
                    $saved = $this->Orientstruct->save( $this->data['Orientstruct']);

                 if( $saved ) {

                        $this->Personne->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Personne->rollback();
                    }
                }
            }

            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $orientstruct_id = null ) {
            $this->assert( valid_int( $orientstruct_id ), 'error404' );

            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'options2', $this->Structurereferente->list1Options() );

            $orientstruct = $this->Orientstruct->find(
            'first',
            array(
                'conditions' => array( 'Orientstruct.id' => $orientstruct_id ),
                'recursive' => 2
            )
            );
            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                if( $this->Orientstruct->save( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
                }
            }
            // Afficage des données
            else {
                $orientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array( 'Orientstruct.id' => $orientstruct_id ),
                        'recursive' => 2
                    )
                );

                // Mauvais paramètre
                $this->assert( !empty( $orientstruct ), 'error404' );

                // Assignation au formulaire
                $this->data = $orientstruct;
            }

            $this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
