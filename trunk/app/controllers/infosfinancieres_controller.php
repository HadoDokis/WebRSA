<?php
    class InfosfinancieresController  extends AppController
    {
        var $name = 'Infosfinancieres';
        var $uses = array( 'Infofinanciere', 'Option', 'Dossier', 'Personne', 'Foyer' );


        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'type_allocation', $this->Option->type_allocation() );
            $this->set( 'natpfcre', $this->Option->natpfcre() );
            $this->set( 'typeopecompta', $this->Option->typeopecompta() );
            $this->set( 'sensopecompta', $this->Option->sensopecompta() );
        }


        function index( $dossier_rsa_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );

            // Recherche des adresses du foyer
            $infosfinancieres = $this->Infofinanciere->find(
                'all',
                array(
                    'conditions' => array( 'Infofinanciere.dossier_rsa_id' => $dossier_rsa_id ),
                    'recursive' => 1
                )
            );

            // Assignations à la vue
            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'infosfinancieres', $infosfinancieres );
        }


      /*  function add( $dossier_rsa_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $dossier_rsa_id
                    )
                )
            );

            $this->assert( !empty( $dossier ), 'error404' );

            // Essai de sauvegarde

            if( !empty( $this->data ) ) {
                $this->data['Infofinanciere']['dossier_rsa_id'] = $dossier_rsa_id;
                $this->Infofinanciere->set( $this->data );

                if( $this->Infofinanciere->validates() ) {
                    if( $this->Infofinanciere->save( $this->data ) ) {
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier_rsa_id ) );
                    }
                }
            }

            // Assignations à la vue
            $this->set( 'dossier_id', $dossier['Dossier']['id'] );
            $this->render( $this->action, null, 'add_edit' );
        }*/

        function view( $infofinanciere_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $infofinanciere_id ), 'error404' );

            $infofinanciere = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array(
                        'Infofinanciere.id' => $infofinanciere_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $infofinanciere ), 'error404' );

            // Assignations à la vue
            $this->set( 'dossier_id', $infofinanciere['Infofinanciere']['dossier_rsa_id'] );

            $this->set( 'infofinanciere', $infofinanciere );

        }


       /* function edit( $infofinanciere_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $infofinanciere_id ), 'error404' );

            $infofinanciere = $this->Infofinanciere->find(
                'first',
                array(
                    'conditions' => array(
                        'Infofinanciere.id' => $infofinanciere_id
                    ),
                'recursive' => -1
                )

            );

            $this->assert( !empty( $infofinanciere ), 'error404' );

            if( !empty( $this->data ) ) {
                $this->Infofinanciere->set( $this->data );
                if( $this->Infofinanciere->validates() ) {
                    if( $this->Infofinanciere->save( $this->data ) ) {
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ) );
                    }
                }
            }
            else {
                $this->data = $infofinanciere;
            }

            // Assignations à la vue
            $this->set( 'dossier_id', $infofinanciere['Infofinanciere']['dossier_rsa_id'] );

            $this->render( $this->action, null, 'add_edit' );
        }*/

}
