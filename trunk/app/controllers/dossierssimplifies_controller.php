<?php
    class DossierssimplifiesController extends AppController
    {
        var $name = 'Dossierssimplifies';
        var $uses = array( 'Dossier', 'Foyer', /*'Adresse', 'Adressefoyer',*/ 'Personne', 'Option', 'Structurereferente', 'Zonegeographique', 'Typeorient', 'Orientstruct' );

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

            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'structsReferentes', $this->Structurereferente->list1Options() );
        }

        function view( $id = null ) {
            $typesOrient = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array(
                        'Typeorient.parentid' => null
                    )
                )
            );
            $this->set( 'typesOrient', $typesOrient );

            $typesStruct = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array(
                        'Typeorient.parentid NOT' => null
                    )
                )
            );
            $this->set( 'typesStruct', $typesStruct );
            // FIXME: assert
            $dossier = $this->Dossier->find(
                'first',
                array(
                    'recursive' => 2,
                    'conditions' => array(
                        'Dossier.id' => $id
                    )
                )
            );

            foreach( $dossier['Foyer']['Personne'] as $key => $personne ) {
                $orientsstructs = $this->Orientstruct->find(
                    'first',
                    array(
                        'recursive' => 2,
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personne['id']
                        )
                    )
                );
                $dossier['Foyer']['Personne'][$key]['Orientstruct'] = $orientsstructs['Orientstruct'];
                $dossier['Foyer']['Personne'][$key]['Structurereferente'] = $orientsstructs['Structurereferente'];

            }
            $this->set( 'dossier', $dossier );

        }


        function add() {

            $typesOrient = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array(
                        'Typeorient.parentid' => null
                    )
                )
            );
            $this->set( 'typesOrient', $typesOrient );

            $typesStruct = $this->Typeorient->find(
                'list',
                array(
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array(
                        'Typeorient.parentid NOT' => null
                    )
                )
            );
            $this->set( 'typesStruct', $typesStruct );


            if( !empty( $this->data ) ) {
                $this->Dossier->set( $this->data );
                $this->Foyer->set( $this->data );
//                 $this->Adresse->set( $this->data );
//                 $this->Adressefoyer->set( $this->data );
                $this->Orientstruct->set( $this->data );
                $this->Structurereferente->set( $this->data );

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


//                 $validates = $this->Adresse->validates() && $validates;
//                 $validates = $this->Adressefoyer->validates() && $validates;
                $validates = $this->Orientstruct->validates() && $validates;
                $validates = $this->Structurereferente->validates() && $validates;



                if( $validates ) {
                    $this->Dossier->begin();
                    $saved = $this->Dossier->save( $this->data );
                    $this->data['Foyer']['dossier_rsa_id'] = $this->Dossier->id;
                    $saved = $this->Foyer->save( $this->data ) && $saved;

                    foreach( $this->data['Personne'] as $key => $pData ) {
                        if( !empty( $pData ) ) {
                            // Personne
                            $this->Personne->create();
                            $pData['foyer_id'] = $this->Foyer->id;
                            $this->Personne->set( $pData );
                            $saved = $this->Personne->save() && $saved;

                            // Orientation
                            $this->Orientstruct->create();
                            $this->data['Orientstruct'][$key]['personne_id'] = $this->Personne->id;
                            $this->data['Orientstruct'][$key]['valid_cg'] = true;
                            $this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
                            $this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
                            $this->data['Orientstruct'][$key]['statut_orient'] = 'Orienté';
                            $saved = $this->Orientstruct->save( $this->data['Orientstruct'][$key] ) && $saved;

                        }
                    }

//                     $saved = $this->Adresse->save( $this->data ) && $saved;
//                     $this->data['Adressefoyer']['adresse_id'] = $this->Adresse->id;
//                     $this->data['Adressefoyer']['foyer_id'] = $this->Foyer->id;
//                     $this->data['Adressefoyer']['rgadr'] = '01';
//                     $this->data['Adressefoyer']['typeadr'] = 'D';
//                     $saved = $this->Adressefoyer->save( $this->data ) && $saved;

                    if( $saved ) {
                        $this->Dossier->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'view', $this->Dossier->id ) );
                    }
                    else {
                        $this->Dossier->rollback();
                    }
                }
            }
        }
    }
?>
