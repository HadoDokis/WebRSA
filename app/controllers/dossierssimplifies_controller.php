<?php
    class DossierssimplifiesController extends AppController
    {
        var $name = 'Dossierssimplifies';
        var $uses = array( 'Dossier', 'Foyer', /*'Adresse', 'Adressefoyer',*/ 'Personne', 'Option', 'Structurereferente', 'Zonegeographique', 'Typeorient', 'Orientstruct', 'Typocontrat' );

        /**
        *
        *
        *
        */

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'pays', $this->Option->pays() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
            //$this->set( 'lib_struc', $this->Option->lib_struc() ); ///FIXME
            $this->set( 'statut_orient', $this->Option->statut_orient() );
            $this->set( 'options', $this->Typeorient->listOptions() );
            $this->set( 'structsReferentes', $this->Structurereferente->list1Options() );
        }

        /**
        *
        *
        *
        */

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

        /**
        *
        *
        *
        */

        function add() {
            $this->set( 'typesOrient',   $this->Typeorient->listOptions()  );
            $this->set( 'structures',   $this->Structurereferente->list1Options()  );

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
                            $personneId = $this->Personne->id;

                            // Prestation
                            $this->Personne->Prestation->create();
                            $this->data['Prestation'][$key]['personne_id'] = $personneId;
                            $this->Personne->Prestation->set( $this->data['Prestation'][$key] );
                            $saved = $this->Personne->Prestation->save( $this->data['Prestation'][$key] ) && $saved;

                            // Orientation
                            if( Set::filter( $this->data['Orientstruct'][$key] ) != array() ) {
                                $this->Orientstruct->create();
                                $this->data['Orientstruct'][$key]['personne_id'] = $this->Personne->id;
                                $this->data['Orientstruct'][$key]['valid_cg'] = true;
                                $this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
                                $this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
                                $this->data['Orientstruct'][$key]['statut_orient'] = 'Orienté';
                                $saved = $this->Orientstruct->save( $this->data['Orientstruct'][$key] ) && $saved;
                            }
                        }
                    }

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

        /**
        *
        *
        *
        */

        function edit( $personne_id = null, $orient_id = null ){
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            $personne = $this->Personne->findById( $personne_id, null, null, 0 );
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array( 'Orientstruct.personne_id' => $personne_id ),
                    'order' => 'Orientstruct.date_propo DESC'
                )
            );
            $personne = Set::merge( $personne, array( 'Orientstruct' => array( $orientstruct['Orientstruct'] ) ) );

            $dossier_id =  $personne['Foyer']['dossier_rsa_id'] ;
            $dossimple  = $this->Dossier->read(null,$dossier_id );

            $this->set( 'id', $personne_id);
            $this->set( 'dossiersimple_id', $dossier_id);
            $this->set( 'foyer_id', $personne['Foyer']['id']);
            $this->set( 'typesOrient',   $this->Typeorient->listOptions()  );
            $this->set( 'structures',   $this->Structurereferente->list1Options()  );
            $this->set( 'numdossierrsa',  $dossimple['Dossier']['numdemrsa']  );
            $this->set( 'datdemdossrsa',  $dossimple['Dossier']['dtdemrsa'] );
            $this->set( 'orient_id', $personne['Orientstruct'][0]['typeorient_id']);
            $this->set( 'structure_id', $personne['Orientstruct'][0]['structurereferente_id']);


            if( !empty( $this->data ) ) {
                if( isset( $personne['Orientstruct'][0]['id'] ) ) {
                    $this->data['Orientstruct'][0]['id'] = $personne['Orientstruct'][0]['id'];
                }

                if( isset( $personne['Orientstruct'][0]['typeorient_id'] ) && isset( $personne['Orientstruct'][0]['structurereferente_id'] ) ) {
                    $this->data['Orientstruct'][0]['statut_orient'] = 'Orienté';
                    $this->data['Orientstruct'][0]['date_propo'] = strftime( '%Y-%m-%d', mktime() ); // FIXME
                    $this->data['Orientstruct'][0]['date_valid'] = strftime( '%Y-%m-%d', mktime() ); // FIXME
                }

                if( $this->Personne->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'view',   $personne['Foyer']['id'] ) );
                }
            }
            else {
                $this->data = $personne;
            }
        }
    }
?>