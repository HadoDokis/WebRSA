<?php
    class DossierssimplifiesController extends AppController
    {
        var $name = 'Dossierssimplifies';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Option', 'Structurereferente', 'Zonegeographique', 'Typeorient' );

        function beforeFilter() {
            // FIXME
            $this->assert( ( $this->Session->read( 'Auth.User.username' ) == 'cg66' ), 'error404' );

            // FIXME
            $services = array(
                1 => 'Association agréée',
                2 => 'Pôle Emploi',
                3 => 'Service Social du Département',
            );

            $this->set( 'pays', $this->Option->pays() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
            //$this->set( 'lib_struc', $this->Option->lib_struc() ); ///FIXME
        }

        function index() {

            $dossier = $this->Dossier->find(
                'first',
                array(
                    'fields' => array(
                        'Dossier.id',
                        'Dossier.numdemrsa',

                    ),
                'recursive' => 1
            )
            );

            $this->set( 'dossier', $dossier );


//             $this->Dossier->find(
//                 'first',
//                 array(
//                     'conditions' => array( 'Dossier.id' => $id ),
//                     'recursive' => 2
//                 )
//             );

//             $this->assert( !empty( $dossier ), 'error404' );
// 
//             $this->set( 'dossier', $dossier );
        }


        function add() {
            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'options2', $this->Structurereferente->list1Options() );

            if( !empty( $this->data ) ) {
                $this->Dossier->set( $this->data );
                $this->Foyer->set( $this->data );
                $this->Adresse->set( $this->data );
                $this->Adressefoyer->set( $this->data );

                $validates = $this->Dossier->validates();
                $validates = $this->Foyer->validates() && $validates;

                $tPers1 = $this->data['Personne'][1];
                unset( $tPers1['rolepers'] );
                unset( $tPers1['dtnai'] ); // FIXME ... créer array_filter_deep
                $t = array_filter( $tPers1 );
                if( empty( $t ) ) {
                    unset( $this->data['Personne'][1] );
                }
                $validates = $this->Personne->saveAll( $this->data['Personne'], array( 'validate' => 'only' ) ) & $validates;

// debug( $validates);
//                 foreach( $this->data['Personne'] as $personne ) {
//                     $this->Personne->create();
//                     $this->Personne->set( array( 'Personne' => $personne ) );
//                     $validates = $this->Personne->validates() && $validates;
//                 }

                $validates = $this->Adresse->validates() && $validates;
                $validates = $this->Adressefoyer->validates() && $validates;



                if( $validates ) {
                    $this->Dossier->begin();
                    $saved = $this->Dossier->save( $this->data );
                    $this->data['Foyer']['dossier_rsa_id'] = $this->Dossier->id;
                    $saved = $this->Foyer->save( $this->data ) && $saved;

                    foreach( $this->data['Personne'] as $pData ) {
                        if( !empty( $pData ) ) {
                            $this->Personne->create();
                            $pData['foyer_id'] = $this->Foyer->id;
                            $this->Personne->set( $pData );

                            $saved = $this->Personne->saveAll() && $saved;
                        }
                    }

                    $saved = $this->Adresse->save( $this->data ) && $saved;

                    $this->data['Adressefoyer']['adresse_id'] = $this->Adresse->id;
                    $this->data['Adressefoyer']['foyer_id'] = $this->Foyer->id;
                    $this->data['Adressefoyer']['rgadr'] = '01';
                    $this->data['Adressefoyer']['typeadr'] = 'D';
                    $saved = $this->Adressefoyer->save( $this->data ) && $saved;
                    $saved = $this->Structurereferente->save( $this->data ) && $saved;

                    if( $saved ) {
                        $this->Dossier->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        //$this->redirect( array( 'controller' => 'dossiers', 'action' => 'index' ) );
                        $this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'index' ) );
                    }
                    else {
                        $this->Dossier->rollback();
                    }
                }
            }
        }
    }
?>
