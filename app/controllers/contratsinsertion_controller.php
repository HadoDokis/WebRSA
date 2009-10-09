<?php
    class ContratsinsertionController extends AppController
    {

        var $name = 'Contratsinsertion';
        var $uses = array( 'Contratinsertion', 'Referent', 'Personne', 'Dossier', 'Option', 'Structurereferente', 'Typocontrat', 'Nivetu', 'Dspp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'AdresseFoyer', 'Prestform', 'Refpresta', 'DsppNivetu' );
        var $helpers = array( 'Ajax' );
        var $components = array( 'RequestHandler' );
        var $aucunDroit = array( 'ajax', 'ajaxreferent', 'ajaxreffonct', 'ajaxrefcoord', 'ajaxorient' );

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $this->set( 'raison_ci', $this->Option->raison_ci() );
            $this->set( 'aviseqpluri', $this->Option->aviseqpluri() );
            $this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
            $this->set( 'emp_occupe', $this->Option->emp_occupe() );
            $this->set( 'duree_hebdo_emp', $this->Option->duree_hebdo_emp() );
            $this->set( 'nat_cont_trav', $this->Option->nat_cont_trav() );
            $this->set( 'duree_cdd', $this->Option->duree_cdd() );
            $this->set( 'duree_engag', $this->Option->duree_engag() );
            $this->set( 'legend_sitfam', $this->Option->sitfam() );
            $this->set( 'legend_typeocclog', $this->Option->typeocclog() );
            $this->set( 'legend_couvsoc', $this->Option->couvsoc() );
            $this->set( 'legend_oridemrsa', $this->Option->oridemrsa() );
            $this->set( 'typevoie', $this->Option->typevoie() );


            $this->set( 'fonction_pers', $this->Option->fonction_pers() );

            $this->set( 'nivetus', $this->Nivetu->find( 'list' ) );

//             $this->set( 'actions_prev', $this->Option->actions_prev() );

            $this->set( 'lib_action', $this->Option->lib_action() );
            $this->set( 'actions', $this->Action->grouplist( 'aide' ) );
            $this->set( 'actions', $this->Action->grouplist( 'prest' ) );
            $this->set( 'typo_aide', $this->Option->typo_aide() );

            $this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
            //$this->set( 'codesaction', $this->Action->find( 'list', array( 'fields' => array( 'code', 'id' ) ) ) );
        }

        function index( $personne_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            if( !valid_int( $personne_id ) ) {
                $this->cakeError( 'error404' );
            }

            $orientstruct = $this->Orientstruct->find(
                'all',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id,
                        'Orientstruct.typeorient_id <>' => null,
                        'Orientstruct.statut_orient' => 'Orienté'
                    )
                )
            );

            $contratsinsertion = $this->Contratinsertion->find(
                'all',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne_id
                    )
                )
            ) ;

            // TODO: si personne n'existe pas -> 404
            $this->set( 'orientstruct', $orientstruct );
            $this->set( 'contratsinsertion', $contratsinsertion );
            $this->set( 'personne_id', $personne_id );

        }

        function view( $contratinsertion_id = null ){
            // TODO : vérif param
            // Vérification du format de la variable
            $this->assert( valid_int( $contratinsertion_id ), 'invalidParameter' );


            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            ) ;

            $codesaction = $this->Action->find( 'list', array( 'fields' => array( 'code', 'libelle' ) ) );
            $codesaction = empty( $contratinsertion['Contratinsertion']['engag_object'] ) ? null : $codesaction[$contratinsertion['Contratinsertion']['engag_object']];
            $this->set( 'codesaction', $codesaction );

            $forme = array( 'S' => 'Simple', 'C' => 'Complexe' );
            $this->set( 'forme', $forme );
            // TODO: si personne n'existe pas -> 404
            $this->set( 'contratinsertion', $contratinsertion );
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _libelleTypeorientNiv0( $typeorient_id ) {
            $typeorient_niv1_id = $this->Personne->Orientstruct->Typeorient->getIdLevel0( $typeorient_id );
            $typeOrientation = $this->Personne->Orientstruct->Typeorient->find(
                'first',
                array(
                    'fields' => array( 'Typeorient.lib_type_orient' ),
                    'recursive' => -1,
                    'conditions' => array(
                        'Typeorient.id' => $typeorient_niv1_id
                    )
                )
            );

            $this->assert( !empty( $typeOrientation ), 'error500' );
            return Set::classicExtract( $typeOrientation, 'Typeorient.lib_type_orient' );
        }

        /** ********************************************************************
        *   Ajout
        *** *******************************************************************/

        function add( $personne_id = null ) {
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'invalidParameter' );

            // Peut-on prendre le jeton ?
            $this->Contratinsertion->begin();
            $dossier_id = $this->Personne->dossierId( $personne_id );
            if( !$this->Jetons->check( $dossier_id ) ) {
                $this->Contratinsertion->rollback();
            }
            $this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

            ///Recup personne
            $this->Personne->unbindModel( array( 'hasOne' => array( 'TitreSejour', 'Avispcgpersonne' ), 'hasMany' => array( 'Rendezvous', 'Activite' ) ) );
            $this->Personne->Prestation->unbindModelAll();
            $this->Personne->Dspp->unbindModelAll();
            $this->Personne->Orientstruct->unbindModelAll();

            $personne = $this->Personne->findById( $personne_id, null, null, 2 );
            $this->assert( !empty( $personne ), 'invalidParameter' );


            /// Recherche du type d'orientation de premier niveau
            $this->set( 'typeOrientation', $this->_libelleTypeorientNiv0( Set::classicExtract( $personne, 'Orientstruct.0.typeorient_id' ) ) );

            /// Recherche du type de structure référente
            $this->set( 'typeStruct', preg_replace( '/(\\n.*)$/', '', $this->_serviceSoutien( Set::classicExtract( $personne, 'Orientstruct.0.structurereferente_id' ) ) ) );

            $this->set( 'qual', $personne['Personne']['qual'] );
            $this->set( 'dtnai', $personne['Personne']['dtnai'] );
            $this->set( 'nom', $personne['Personne']['nom'] );
            $this->set( 'prenom', $personne['Personne']['prenom'] );
            $this->set( 'idassedic', $personne['Personne']['idassedic'] );
            $this->set( 'rolepers', $this->Option->rolepers() );
            $dspp_id =  $personne['Dspp']['id'] ;
            $dspp = $this->Dspp->read( null, $dspp_id );
            $this->set( 'couvsoc', $dspp['Dspp']['couvsoc'] );
            $this->set( 'diplomes', $dspp['Dspp']['diplomes'] );

            $foyer_id =  $personne['Personne']['foyer_id'] ;

            $this->Foyer->unbindModel(
                array(
                    'hasMany' => array( 'Personne', 'Modecontact' ),
                    'hasOne' => array( 'Dspf' ),
                    'hasAndBelongsToMany' => array( 'Creance' ),
                )
            );
            $foyer  = $this->Foyer->read( null, $foyer_id );

            $this->set( 'sitfam',  $foyer['Foyer']['sitfam'] );
            $this->set( 'typeocclog',  $foyer['Foyer']['typeocclog'] );

            ///Recup dossier
            $dossier_id =  $foyer['Foyer']['dossier_rsa_id'];
            $this->Dossier->unbindModel( array( 'hasMany' => array( 'Infofinanciere' ) ) );
            $this->Dossier->bindModel(
                array(
                    'hasMany' => array(
                        'Infofinanciere' => array(
                            'className'     => 'Infofinanciere',
                            'foreignKey'    => 'dossier_rsa_id',
                            'order' => array( 'Infofinanciere.moismoucompta DESC' )
                        )
                    )
                )
            );
            $dossier = $this->Dossier->read( null, $dossier_id );
            $this->set( 'oridemrsa', $dossier['Detaildroitrsa']['oridemrsa'] );
            $this->set( 'dtdemrsa', $dossier['Dossier']['dtdemrsa'] );
            $this->set( 'numdemrsa', $dossier['Dossier']['numdemrsa'] );
            $this->set( 'matricule', $dossier['Dossier']['matricule'] );


            /// Récupération de l'adresse lié à la personne
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $personne['Adresse'] = $adresse['Adresse'];



            // Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
            $this->set( 'nbrCi', $nbrCi );
            // Assignation à la vue
            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );

            $this->set( 'sr', $this->Structurereferente->find( 'list' ) );
            $this->set( 'tc', $this->Typocontrat->find( 'list' ) );
            $this->set( 'personne', $personne );
            $this->set( 'personne_id', $personne_id );
            $this->set( 'foyer', $foyer );
            $this->set( 'dossier', $dossier );

            // Essai de sauvegarde
            if( !empty( $this->data ) ) {
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;


                $this->Contratinsertion->set( $this->data );
                $valid = $this->Contratinsertion->validates();

                if( isset( $this->data['Refpresta'] ) ) {
                    $this->Refpresta->create();
                    $this->Refpresta->set( $this->data['Refpresta'] );
                    $valid = $this->Refpresta->validates();
                }

                $valid = $this->Actioninsertion->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;
                $valid = $this->Dspp->saveAll( $this->data, array( 'validate' => 'only' ) ) && $valid;

                if( $valid ) {
                    $saved = true;
                    $this->Actioninsertion->create();
                    if( isset( $this->data['Prestform'] ) ) {
                        $saved = $this->Refpresta->save( $this->data['Refpresta'] ) && $saved;
                        unset( $this->data['Refpresta'] );
                        $this->data['Prestform'][0]['refpresta_id'] = $this->Refpresta->id;
                    }
// debug( $this->data );
                    $saved = $this->Dspp->saveAll( $this->data, array( 'validate' => 'first' ) ) && $saved;
                    $saved = $this->Actioninsertion->saveAll( $this->data, array( 'validate' => 'first' ) ) && $saved;

                    if( $saved ) {
                        $this->Jetons->release( $dossier_id );
                        $this->Contratinsertion->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                       $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
                    }
                    else {
                        $this->Contratinsertion->rollback();
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                    }
                }
            }
            else {

                // Récupération des données socio pro (notamment Niveau etude) lié au contrat
                $this->Dspp->unbindModelAll();
                $this->Dspp->bindModel(
                    array(
                        'hasAndBelongsToMany' => array(
                            'Nivetu' => array(
                                'classname' => 'Nivetu',
                                'joinTable' => 'dspps_nivetus',
                                'foreignKey' => 'dspp_id',
                                'associationForeignKey' => 'nivetu_id'
                            )
                        )
                    )
                );
                $dspp = $this->Dspp->findByPersonneId( $personne_id, null, null, 1 );

                if( empty( $dspp ) ) {
                    $dspp = array(
                        'Dspp' => array(
                            'personne_id' => $personne_id
                        )
                    );
                    $this->Dspp->set( $dspp );
                    if( $this->Dspp->save( $dspp ) ) {
                        $dspp = $this->Dspp->findByPersonneId( $personne_id, null, null, 1 );
                    }
                    else {
                        $this->cakeError( 'error500' );
                    }
                    $this->assert( !empty( $dspp ), 'error500' );
                }
                //$this->assert( !empty( $dspp ), 'error500' ); // FIXME -> error code

                $this->data['Dspp'] = array( 'id' => $dspp['Dspp']['id'], 'personne_id' => $dspp['Dspp']['personne_id'] );
                $this->data['Nivetu'] = ( ( isset( $dspp['Nivetu'] ) ) ? $dspp['Nivetu'] : array() );
                if( !empty( $dspp ) ){
                    $this->data['Contratinsertion']['diplomes'] = $dspp['Dspp']['diplomes'];
                }
                // Récupération du services instructeur lié au contrat
                $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 1 );
                $this->assert( !empty( $user ), 'error500' ); // FIXME

                $this->data['Contratinsertion']['pers_charg_suivi'] = $user['User']['nom'].' '.$user['User']['prenom'];
                $this->set( 'perschargsuivi', $this->data['Contratinsertion']['pers_charg_suivi'] );

                $orientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personne_id
                        ),
                        'order' => 'Orientstruct.date_propo DESC',
                        'recursive' => -1
                    )
                );

                $this->data['Contratinsertion']['structurereferente_id'] = $orientstruct['Orientstruct']['structurereferente_id'];
                $this->data['Contratinsertion']['service_soutien'] = $this->_serviceSoutien( Set::extract( $this->data, 'Contratinsertion.structurereferente_id' ) );

                if( !empty( $orientstruct ) ) {
                    $this->data['Structurereferente']['id'] = $orientstruct['Orientstruct']['structurereferente_id'];
                }

                // Si on est en présence d'un deuxième contrat -> Alors renouvellement
                $this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;

                if( $this->data['Contratinsertion']['rg_ci'] > 1 ){
                     $this->data['Contratinsertion']['typocontrat_id'] = 2;
                }
                else {
                    $this->data['Contratinsertion']['typocontrat_id'] = 1;
                }

            }

            $this->Contratinsertion->commit();

            $referents = $this->Orientstruct->Structurereferente->Referent->find( 'all', array( 'recursive' => -1, 'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom', 'Referent.fonction' ) ) );
            $ids = Set::extract( $referents, '/Referent/id' );
            $values = Set::format( $referents, '{0} {1}', array( '{n}.Referent.nom', '{n}.Referent.prenom' ) );
            $referents = array_combine( $ids, $values );
            $this->set( 'referents', $referents );


            $this->render( $this->action, null, 'add_edit' );
        }

        /**
        *
        */
        ///Ajout pour les structures référentes liées au type d'orientation
        function _selectStruct( $typeorient_id ) {
            $structs = $this->Structurereferente->find(
                'all',
                array(
                    'conditions' => array(
                        'Structurereferente.typeorient_id' => $typeorient_id
                    ),
                    'recursive' => -1
                )
            );
            return $structs;
        }
        function ajaxorient() {
            Configure::write( 'debug', 0 );
//             echo $this->_selectStruct( Set::extract( $this->data, 'Structurereferente.typeorient_id' ) );
//             $this->render( null, 'ajax' );
            $structs = $this->_selectStruct( Set::classicExtract( $this->data, 'Orientstruct.typeorient_id' ) );
            $options = array( '<option value=""></option>' );
            foreach( $structs as $struct ) {
                $options[] = '<option value="'.$struct['Structurereferente']['id'].'">'.$struct['Structurereferente']['lib_struc'].'</option>';
            } ///FIXME: à mettre dans la vue
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }
        ///Ajax pour les structures référentes du bénéficiaire
        function _serviceSoutien( $structureReferente_id ) {
            $structure = $this->Structurereferente->find(
                'first',
                array(
                    'conditions' => array(
                        'Structurereferente.id' => $structureReferente_id
                    ),
                    //'order' => 'Orientstruct.date_propo DESC',
                    'recursive' => -1
                )
            );
            $typevoie = $this->Option->typevoie();
            $type_voie = empty( $structure['Structurereferente']['type_voie'] ) ? null : $typevoie[$structure['Structurereferente']['type_voie']];

            return $structure['Structurereferente']['lib_struc']. "\n" .$structure['Structurereferente']['num_voie'].' '.$type_voie.' '.$structure['Structurereferente']['nom_voie'].' '.$structure['Structurereferente']['code_postal'].' '.$structure['Structurereferente']['ville'];
        }

        function ajax() { // FIXME
            Configure::write( 'debug', 0 );
            echo $this->_serviceSoutien( Set::extract( $this->data, 'Contratinsertion.structurereferente_id' ) );
            $this->render( null, 'ajax' );
        }

        ///Ajax pour les coordonnées du référent
        function _coordReferent( $referent_id ) {
            $referent = $this->Referent->find(
                'first',
                array(
                    'conditions' => array(
                        'Referent.id' => $referent_id
                    ),
                    'recursive' => -1
                )
            );

            return $referent['Referent']['email']. "\n" .$referent['Referent']['numero_poste'];
        }
        function ajaxrefcoord() { // FIXME
            Configure::write( 'debug', 0 );
            echo $this->_coordReferent( Set::extract( $this->data, 'Contratinsertion.referent_id' ) );
            $this->render( null, 'ajax' );
        }

        ///Ajax pour la fonction du référent
        function _fonctReferent( $referent_id ) {
            $referent = $this->Referent->find(
                'first',
                array(
                    'conditions' => array(
                        'Referent.id' => $referent_id
                    ),
                    'recursive' => -1
                )
            );

            return $referent['Referent']['fonction'];
        }

        function ajaxreffonct() { // FIXME
            Configure::write( 'debug', 0 );
            echo $this->_fonctReferent( Set::extract( $this->data, 'Contratinsertion.referent_id' ) );
            $this->render( null, 'ajax' );
        }

        ///Ajax pour lien référent - structure référente
        function _selectReferents( $structurereferente_id ) {
            $referents = $this->Referent->find(
                'all',
                array(
                    'conditions' => array(
                        'Referent.structurereferente_id' => $structurereferente_id
                    ),
                    'recursive' => -1
                )
            );
            return $referents;

        }

        function ajaxreferent() { // FIXME
            Configure::write( 'debug', 0 );
            $referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Contratinsertion.structurereferente_id' ) );
            $options = array( '<option value=""></option>' );
            foreach( $referents as $referent ) {
                $options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
            } ///FIXME: à mettre dans la vue
            echo implode( '', $options );
            $this->render( null, 'ajax' );
        }
/////////////////////////////////////////////////////////////////////////////////////////////

        function edit( $contratinsertion_id = null ) {

            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $this->set( 'typeservice', $this->Serviceinstructeur->find( 'first' ) );

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );



            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );

            // TODO -> 404
            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                    'Contratinsertion.id' => $contratinsertion_id
                    ),
                    'recursive' => 0
                )
            );

            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions'=> array(
                        'Personne.id' => $contratinsertion['Personne']['id']
                    ),
                    'recursive' => 2
                )
            );

            $action = $this->Actioninsertion->find(
                'first',
                array(
                    'conditions'=> array(
                        'Actioninsertion.contratinsertion_id' => $contratinsertion['Contratinsertion']['id']
                    ),
                    'recursive' => 1
                )
            );
            $this->set( 'action', $action );
            // Récupération de l'adresse lié à la personne
            $this->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );
            $adresse = $this->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $personne['Adresse'] = $adresse['Adresse'];
            // Calcul du numéro du contrat d'insertion
            $nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $contratinsertion['Personne']['id']) ) );
            $this->set( 'nbrCi', $nbrCi );
            // Assignation à la vue
            $this->set( 'personne', $personne );

            $this->set( 'typeorient', $this->Typeorient->find(
                'list',
                array (
                    'fields' => array(
                        'Typeorient.id',
                        'Typeorient.lib_type_orient'
                    ),
                    'conditions' => array( 'Typeorient.parentid' => NULL ),
                    'order'  => array( 'Typeorient.lib_type_orient ASC' )
                )
            )  );
            $conditions = array( );

            if ( empty( $contratinsertion )){
                $this->cakeError( 'error404' );
            }

            $personne_id = $contratinsertion['Personne']['id'];
            $personne = $this->Personne->read( null, $personne_id );
            $this->set( 'qual', $personne['Personne']['qual'] );
            $this->set( 'dtnai', $personne['Personne']['dtnai'] );
            $this->set( 'nom', $personne['Personne']['nom'] );
            $this->set( 'prenom', $personne['Personne']['prenom'] );
            $this->set( 'idassedic', $personne['Personne']['idassedic'] );
            $this->set( 'rolepers', $this->Option->rolepers() );

            $dspp_id =  $personne['Dspp']['id'] ;
            $dspp = $this->Dspp->read( null, $dspp_id );
            $this->set( 'couvsoc', $dspp['Dspp']['couvsoc'] );

            $foyer_id =  $personne['Personne']['foyer_id'] ;
            $foyer  = $this->Foyer->read( null, $foyer_id );
            $this->set( 'sitfam',  $foyer['Foyer']['sitfam'] );
            $this->set( 'typeocclog',  $foyer['Foyer']['typeocclog'] );

            $dossier_id =  $personne['Foyer']['dossier_rsa_id'] ;
            $dossier = $this->Dossier->read( null, $dossier_id );
            $this->set( 'oridemrsa', $dossier['Detaildroitrsa']['oridemrsa'] );
            $this->set( 'dtdemrsa', $dossier['Dossier']['dtdemrsa'] );
            $this->set( 'numdemrsa', $dossier['Dossier']['numdemrsa'] );
            $this->set( 'matricule', $dossier['Dossier']['matricule'] );

            $this->set( 'perschargsuivi', $contratinsertion['Contratinsertion']['pers_charg_suivi'] );
            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );
            $this->set( 'foyer', $foyer );
            $this->set( 'dossier', $dossier );

            if( !empty( $this->data ) ) {
                $this->Dspp->create();
                $this->Dspp->set( $this->data );
                $valid = $this->Dspp->validates( $this->data );

                $this->Nivetu->create();
                $this->Nivetu->set( $this->data );

                $valid = $this->Contratinsertion->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) && $valid;

                if( $valid ) {
                    $this->Dspp->begin();
                    $this->data['Actioninsertion'] = Set::filter( $this->data['Actioninsertion'] );
                    if( empty( $this->data['Actioninsertion'] ) ) {
                        unset( $this->data['Actioninsertion'] );
                    }
                    else if( !empty( $this->data['Contratinsertion']['id'] ) && !empty( $this->data['Actioninsertion'] ) ) {
                        $this->data['Actioninsertion']['contratinsertion_id'] = $this->data['Contratinsertion']['id'];
                    }
                    $saved = $this->Contratinsertion->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
                    $saved = $this->Dspp->save( $this->data ) && $saved;
                    if( $saved ) {
                        $this->Dspp->commit();
                        $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                        $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
                    }
                    else {
                        $this->Dspp->rollback();
                    }
                }
            }
            else {
                // Récupération du services instructeur lié au contrat
                $user = $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $this->Session->read( 'Auth.User.id' ) ), 'recursive' => 0 ) );


                $this->data['Contratinsertion']['service_soutien'] = $this->_serviceSoutien( Set::extract( $this->data, 'Contratinsertion.structurereferente_id' ) );


                // Récupération de la dernière structure referente liée au contrat
                $orientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personne_id
                        ),
                        'order' => 'Orientstruct.date_propo DESC',
                        'recursive' => -1
                    )
                );

                $this->data = $contratinsertion;

                /// Remplissage des textarea non enregistrés
                $referent_id = Set::ClassicExtract( $this->data, 'Contratinsertion.referent_id' );
                if( !empty( $referent_id ) ) {
                    $referent = $this->Orientstruct->Structurereferente->Referent->find(
                        'first',
                        array(
                            'fields' => array( 'Referent.email', 'Referent.fonction', 'Referent.numero_poste' ),
                            'Referent.id' => $referent_id,
                            'recursive' => -1
                        )
                    );
                    $this->data['Referent']['email'] = Set::classicExtract( $referent, 'Referent.email' )."\n".Set::classicExtract( $referent, 'Referent.numero_poste' );
                    $this->data['Referent']['fonction'] = Set::classicExtract( $referent, 'Referent.fonction' );
                }


                $dspp = $this->Dspp->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Dspp.personne_id' => $contratinsertion['Personne']['id']
                        )
                    )
                );

                if( !empty( $dspp ) ){
                    $this->data['Dspp']['id'] = $dspp['Dspp']['id'];
                    $this->data['Nivetu'] = $dspp['Nivetu'];
                }
                else{
                    //TODO : cakeError

                }
            }

            ///Partie pour Ajax
            $referents = $this->Orientstruct->Structurereferente->Referent->find( 'all', array( 'recursive' => -1, 'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom', 'Referent.fonction' ) ) );
            $ids = Set::extract( $referents, '/Referent/id' );
            $values = Set::format( $referents, '{0} {1}', array( '{n}.Referent.nom', '{n}.Referent.prenom' ) );
            $referents = array_combine( $ids, $values );
            $this->set( 'referents', $referents );

            /// Recherche du type d'orientation de premier niveau
            $this->set( 'typeOrientation', $this->_libelleTypeorientNiv0( Set::classicExtract( $personne, 'Orientstruct.0.typeorient_id' ) ) );

            /// Recherche du type de structure référente
            $this->set( 'typeStruct', preg_replace( '/(\\n.*)$/', '', $this->_serviceSoutien( Set::classicExtract( $personne, 'Orientstruct.0.structurereferente_id' ) ) ) );

            $this->render( $this->action, null, 'add_edit' );
        }


        function valider( $contratinsertion_id = null ) {
            // Vérification du format de la variable
            if( !valid_int( $contratinsertion_id ) ) {
                $this->cakeError( 'error404' );
            }

            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            );

            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions'=> array(
                        'Personne.id' => $contratinsertion['Personne']['id']
                    ),
                    'recursive' => 2
                )
            );

            // Assignation à la vue
            $this->set( 'personne', $personne );

            if ( empty( $contratinsertion )){
                $this->cakeError( 'error404' );
            }

            $this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

            if( !empty( $this->data ) ) {
                if( $this->Contratinsertion->saveAll( $this->data ) ) {
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                   $this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
                }
            }
            else {
                $this->data = $contratinsertion;
            }

        }

}
?>
