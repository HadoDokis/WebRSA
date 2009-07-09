<?php
    class OrientsstructsController extends AppController
    {

        var $name = 'Orientsstructs';
        var $uses = array( 'Orientstruct',  'Option' , 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Typeorient', 'Structurereferente');

        /**
        *
        *
        *
        */

        function beforeFilter() {
            $return = parent::beforeFilter();
            $this->set( 'pays', $this->Option->pays() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
            return $return;
        }

        /**
        *
        *
        *
        */

        function index( $personne_id = null ){
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $orientstructs = $this->Orientstruct->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'recursive' => 2
                )
            );
//             debug( $orientstruct );

            $this->set( 'orientstructs', $orientstructs );
            $this->set( 'personne_id', $personne_id );
        }

        /**
        *
        *
        *
        */

        function add( $personne_id = null ) {
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $dossier_id = $this->Personne->dossierId( $personne_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Orientstruct->begin();
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Orientstruct->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'options2', $this->Structurereferente->list1Options() );

            if( !empty( $this->data ) ) {
// debug( $this->data );
                $this->Orientstruct->set( $this->data );
//                 $this->Typeorient->set( $this->data );
//                 $this->Structurereferente->set( $this->data );

                $validates = $this->Orientstruct->validates();
//                 $validates = $this->Typeorient->validates() && $validates;
//                 $validates = $this->Structurereferente->validates() && $validates;


                if( $validates ) {
                    // Orientation
                    $this->Orientstruct->create();

                    $this->data['Orientstruct']['personne_id'] = $personne_id;
                    $this->data['Orientstruct']['valid_cg'] = true;
                    $this->data['Orientstruct']['date_propo'] = date( 'Y-m-d' );
                    $this->data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
                    $this->data['Orientstruct']['statut_orient'] = 'Orienté';

                    $saved = $this->Orientstruct->Personne->Prestation->save( $this->data );
                    $saved = $this->Orientstruct->save( $this->data['Orientstruct'] ) && $saved;

                    if( $saved ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Orientstruct->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Orientstruct->rollback();
                    }
                }
            }
            else {
                $personne = $this->Personne->findByid( $personne_id, null, null, 0 );
                $this->data['Prestation'] = $personne['Prestation'];
            }

            //$this->Orientstruct->commit();
            $this->set( 'personne_id', $personne_id );
            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        *
        *
        */

        function edit( $orientstruct_id = null ) {
            $this->assert( valid_int( $orientstruct_id ), 'invalidParameter' );

            $orientstruct = $this->Orientstruct->findById( $orientstruct_id, null, null, 2 );
            $this->assert( !empty( $orientstruct ), 'invalidParameter' );

            $dossier_id = $this->Orientstruct->dossierId( $orientstruct_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Orientstruct->begin();
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Orientstruct->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'options2', $this->Structurereferente->list1Options() );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->Orientstruct->set( $this->data );
                $this->Orientstruct->Personne->Prestation->set( $this->data );
                $valid = $this->Orientstruct->Personne->Prestation->validates();
                $valid = $this->Orientstruct->validates() && $valid;

                if( $valid ) {
                    if( $this->Orientstruct->Personne->Prestation->save( $this->data ) && $this->Orientstruct->save( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Orientstruct->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
                    }
                    else {
                        $this->Orientstruct->commit();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            // Afficage des données
            else {
                // Assignation au formulaire
                $this->data = Set::merge( array( 'Orientstruct' => $orientstruct['Orientstruct'] ), array( 'Prestation' => $orientstruct['Personne']['Prestation'] ) );
            }

            $this->Orientstruct->commit();
            $this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );
            $this->render( $this->action, null, 'add_edit' );
        }
    }
?>
