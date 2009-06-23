<?php
    class ModescontactController extends AppController
    {

        var $name = 'Modescontact';
        var $uses = array( 'Modecontact',  'Option' , 'Foyer');


        function beforeFilter() {
            parent::beforeFilter();
                $this->set( 'nattel', $this->Option->nattel() );
                $this->set( 'matetel', $this->Option->matetel() );
                $this->set( 'autorutitel', $this->Option->autorutitel() );
                $this->set( 'autorutiadrelec', $this->Option->autorutiadrelec() );
        }


        function index( $foyer_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            // Recherche des personnes du foyer
            $modescontact = $this->Modecontact->find(
                'all',
                array(
                    'conditions' => array( 'Modecontact.foyer_id' => $foyer_id ),
                    'recursive' => 2
                )
            );

            // Assignations à la vue
            $this->set( 'foyer_id', $foyer_id );
            $this->set( 'modescontact', $modescontact );
        }

        function add( $foyer_id = null ){
            $this->assert( valid_int( $foyer_id ), 'invalidParameter' );

            $dossier_id = $this->Foyer->dossierId( $foyer_id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Modecontact->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Modecontact->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            if( !empty( $this->data ) ) {
                $this->Modecontact->set( $this->data );
                if( $this->Modecontact->validates() ) {
                    if( $this->Modecontact->save( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Modecontact->commit();
                        $this->Session->setFlash( 'Enregistrement réussi', 'flash/success' );
                        $this->redirect( array( 'controller' => 'modescontact', 'action' => 'index', $foyer_id ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }

            $this->set( 'foyer_id', $foyer_id );
            $this->data['Modecontact']['foyer_id'] = $foyer_id;

            $this->Modecontact->commit();
            $this->render( $this->action, null, 'add_edit' );
        }

        function edit( $id = null ){
            $this->assert( valid_int( $id ), 'invalidParameter' );

            $dossier_id = $this->Foyer->dossierId( $id );
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

            $this->Modecontact->begin();

            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Modecontact->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->Modecontact->set( $this->data );
                if( $this->Modecontact->validates() ) {
                    if( $this->Modecontact->save( $this->data ) ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Modecontact->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array(  'controller' => 'modescontact','action' => 'index', $this->data['Modecontact']['foyer_id'] ) );
                    }
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            // Afficage des données
            else {
                $modecontact = $this->Modecontact->find(
                    'first',
                    array(
                        'conditions' => array( 'Modecontact.id' => $id ),
                        'recursive' => 2
                    )
                );
                $this->assert( !empty( $modecontact ), 'invalidParameter' );

                // Assignation au formulaire
                $this->data = $modecontact;
            }

            $this->Modecontact->commit();
            $this->render( $this->action, null, 'add_edit' );

        }

        function view( $modecontact_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $modecontact_id ), 'error404' );

            $modecontact = $this->Modecontact->find(
                'first',
                array(
                    'conditions' => array(
                        'Modecontact.id' => $modecontact_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $modecontact ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_id', $modecontact['Modecontact']['dossier_rsa_id'] );
            $this->set( 'modecontact', $modecontact );

        }
}
?>
