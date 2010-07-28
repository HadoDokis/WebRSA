<?php
    class DossierssimplifiesController extends AppController
    {
        var $name = 'Dossierssimplifies';
        var $uses = array( 'Dossier', /*'Foyer',*/ /*'Adresse', 'Adressefoyer',*/ 'Personne', 'Option', 'Structurereferente', 'Zonegeographique', 'Typeorient', 'Orientstruct', 'Typocontrat' );
        var $components = array( 'Gedooo' );
        
		var $commeDroit = array(
			'add' => 'Dossierssimplifies:edit'
		);

        /**
        *
        *
        *
        */

        protected function _setOptions() {
            $this->set( 'pays', $this->Option->pays() );
            $this->set( 'qual', $this->Option->qual() );
            $this->set( 'rolepers', array_filter_keys( $this->Option->rolepers(), array( 'DEM', 'CJT' ) ) );
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
            $details = array();

            $typeorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) );
            $this->set( 'typeorient', $typeorient );

            $tDossier = $this->Dossier->findById( $id, null, null, -1 );
            $details = Set::merge( $details, $tDossier );

            $tFoyer = $this->Dossier->Foyer->findByDossierRsaId( $id, null, null, -1 );
            $details = Set::merge( $details, $tFoyer );

            $bindPrestation = $this->Personne->hasOne['Prestation'];
            $this->Personne->unbindModelAll();
            $this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Prestation' => $bindPrestation ) ) );
            $personnesFoyer = $this->Personne->find(
                'all',
                array(
                    'conditions' => array(
                        'Personne.foyer_id' => $tFoyer['Foyer']['id'],
                        'Prestation.rolepers' => array( 'DEM', 'CJT' )
                    ),
                    'recursive' => 0
                )
            );

            $roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
            foreach( $roles as $index => $role ) {
                ///Orientations
                $orient = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array( 'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
                        'recursive' => -1,
                        'order' => 'Orientstruct.date_propo DESC',
                    )
                );
                $personnesFoyer[$index]['Orientstruct'] = $orient['Orientstruct'];

                ///Structures référentes
                $struct = $this->Structurereferente->find(
                    'first',
                    array(
                        'conditions' => array( 'Structurereferente.id' => $personnesFoyer[$index]['Orientstruct']['structurereferente_id'] ),
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Structurereferente'] = $struct['Structurereferente'];

                $details[$role] = $personnesFoyer[$index];
            }

            $this->set( 'personnes', $personnesFoyer );

            $this->_setOptions();
            $this->set( 'details', $details );
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
                    // Foyer
                    $this->data['Foyer']['dossier_rsa_id'] = $this->Dossier->id;
                    $saved = $this->Foyer->save( $this->data ) && $saved;
                    // Détails du droit -> FIXME: le faut-il ? alors rajouter un champ dans le formulaire
//                     $data['dossier']['Detaildroitrsa']['dossier_rsa_id'] = $this->Dossier->id;
//                     $saved = $this->Detaildroitrsa->save( $data['dossier']['Detaildroitrsa'] ) && $saved;
                    // Situation dossier RSA
                    $situationdossierrsa = array( 'Situationdossierrsa' => array( 'dossier_rsa_id' => $this->Dossier->id, 'etatdosrsa' => 'Z' ) );
                    $this->Dossier->Situationdossierrsa->validate = array();
                    $saved = $this->Dossier->Situationdossierrsa->save( $situationdossierrsa ) && $saved;

                    $orientstruct_validate = $this->Orientstruct->validate;

					$mkOrientstructPdf = true;
                    foreach( $this->data['Personne'] as $key => $pData ) {
                        if( !empty( $pData ) ) {
                            $this->Orientstruct->validate = $orientstruct_validate;
                            // Personne
                            $this->Personne->create();
                            $pData['foyer_id'] = $this->Foyer->id;
                            $this->Personne->set( $pData );
                            $saved = $this->Personne->save() && $saved;
                            $personneId = $this->Personne->id;

                            // Prestation, Calculdroitrsa
                            foreach( array( 'Prestation', 'Calculdroitrsa' ) as $tmpModel ) {
								$this->Personne->{$tmpModel}->create();
								$this->data[$tmpModel][$key]['personne_id'] = $personneId;
								$this->Personne->{$tmpModel}->set( $this->data[$tmpModel][$key] );
								$saved = $this->Personne->{$tmpModel}->save( $this->data['Prestation'][$key] ) && $saved;
							}

                            // Orientation
                            $tOrientstruct = Set::extract( $this->data, 'Orientstruct.'.$key );
                            if( !empty( $tOrientstruct ) ) {
                                $tOrientstruct = Set::filter( $tOrientstruct );
                            }

                            if( !empty( $tOrientstruct ) ) {
                                $this->Orientstruct->create();
                                $this->data['Orientstruct'][$key]['personne_id'] = $this->Personne->id;
                                $this->data['Orientstruct'][$key]['valid_cg'] = true;
                                $this->data['Orientstruct'][$key]['date_propo'] = date( 'Y-m-d' );
                                $this->data['Orientstruct'][$key]['date_valid'] = date( 'Y-m-d' );
//                                 $this->data['Orientstruct'][$key]['statut_orient'] = 'Orienté';
                                $saved = $this->Orientstruct->save( $this->data['Orientstruct'][$key] ) && $saved;
                            }
                            else {
                                $this->Orientstruct->create();
                                $this->Orientstruct->validate = array();
                                $this->data['Orientstruct'][$key]['personne_id'] = $this->Personne->id;
//                                 $this->data['Orientstruct'][$key]['statut_orient'] = 'Non orienté'; // FIXME ?
                                $saved = $this->Orientstruct->save( $this->data['Orientstruct'][$key] ) && $saved;
                            }

							$mkOrientstructPdf = $this->Gedooo->mkOrientstructPdf( $this->Orientstruct->getLastInsertId() ) && $mkOrientstructPdf;
							$saved = $mkOrientstructPdf && $saved;
                        }
                    }

                    if( $saved ) {
                        $this->Dossier->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'view', $this->Dossier->id ) );
                    }
					else if( !$mkOrientstructPdf ) {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de la génération du document PDF (le serveur Gedooo est peut-être tombé ou mal configuré)', 'flash/error' );
					}
                    else {
                        $this->Dossier->rollback();
                    }
                }
            }
            $this->_setOptions();
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

            $this->set( 'personne_id', $personne_id);
            $this->set( 'dossiersimple_id', $dossier_id);
            $this->set( 'foyer_id', $personne['Foyer']['id']);
            $this->set( 'typesOrient',   $this->Typeorient->listOptions()  );
            $this->set( 'structures',   $this->Structurereferente->list1Options()  );
            $this->set( 'numdossierrsa',  $dossimple['Dossier']['numdemrsa']  );
            $this->set( 'datdemdossrsa',  $dossimple['Dossier']['dtdemrsa'] );
            $this->set( 'matricule',  $dossimple['Dossier']['matricule'] );
            $this->set( 'orient_id', $personne['Orientstruct'][0]['typeorient_id']);
            $this->set( 'structure_id', $personne['Orientstruct'][0]['structurereferente_id']);

            $this->_setOptions();
            if( !empty( $this->data ) ) {
                if( isset( $personne['Orientstruct'][0]['id'] ) ) {
                    $this->data['Orientstruct'][0]['id'] = $personne['Orientstruct'][0]['id'];
                }

                if( $this->Personne->saveAll( $this->data, array( 'validate' => 'only' ) ) && isset( $this->data['Orientstruct'][0]['typeorient_id'] ) && isset( $this->data['Orientstruct'][0]['structurereferente_id'] ) ) {
                    $this->data['Orientstruct'][0]['statut_orient'] = 'Orienté';
                    $this->data['Orientstruct'][0]['date_propo'] = strftime( '%Y-%m-%d', mktime() ); // FIXME
                    $this->data['Orientstruct'][0]['date_valid'] = strftime( '%Y-%m-%d', mktime() ); // FIXME
                }

				$this->Dossier->begin();
				$mkOrientstructPdf = true;
                if( $saved = $this->Personne->saveAll( $this->data, array( 'atomic' => false ) ) ) {
					$mkOrientstructPdf = $this->Gedooo->mkOrientstructPdf( $this->Orientstruct->id );
					$saved = $mkOrientstructPdf && $saved;
                }

				if( $saved ) {
					$this->Dossier->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossierssimplifies', 'action' => 'view', $this->Dossier->id ) );
				}
				else if( !$mkOrientstructPdf ) {
					$this->Dossier->rollback();
					$this->Session->setFlash( 'Erreur lors de la génération du document PDF (le serveur Gedooo est peut-être tombé ou mal configuré)', 'flash/error' );
				}
				else {
					$this->Dossier->rollback();
				}
            }
            else {
                $this->data = $personne;
            }
        }
    }
?>
