<?php
    // INFO: pour les tests
    function rand_nir() {
        $str = '';
        for( $i = 1 ; $i <= 15 ; $i++ )
            $str .= rand( 0, 9 );
        return $str;
    }

    function hasConjoint( $data ) { // FIXME
        return ( count( array_filter( $data ) ) > 3 );
    }

    class TestsController extends AppController {
        // INFO: http://bakery.cakephp.org/articles/view/wizard-component-1-2-1
        var $components = array( 'Wizard' );
        var $uses = array( 'Dossier', 'Foyer', 'Personne', 'Adresse', 'Adressefoyer', 'Option', 'Ressource', 'Ressourcemensuelle',  'Detailressourcemensuelle', 'Orientstruct', 'Detaildroitrsa' );

        /**
        *
        */
        function beforeFilter() {
            // INFO: Supprimer la session, et donc les données du wizard
            // $this->Session->destroy();
            $this->Wizard->steps = array( 'allocataire', 'conjoint', 'adresse', 'ressourcesallocataire', array( 'withConjoint' => array( 'ressourcesconjoint', 'dossier' ), 'noConjoint' => array( 'dossier' ) ) );
            $this->Wizard->completeUrl = '/tests/confirm';
            $this->Wizard->cancelUrl = '/tests/wizard';

            // INFO: on peut préremplir le wizard pour les tests
            $this->Session->write(
                'Wizard.Tests.allocataire',
                array(
                    'Personne' => array(
                        'rolepers'  => 'DEM',
                        'qual'      => 'MR',
                        'nom'       => 'Buffin',
                        'prenom'    => 'Christian',
                        'dtnai'   => array(
                            'day'   => 01,
                            'month' => 01,
                            'year'  => 2009
                        ),
                        'rgnai' => 1,
                        'nir' => rand_nir(),
                        'topvalec' => 0,
                        'nati' => 'C',
                        'pieecpres' => 'E'
                    )
                )
            );
            $this->Session->write(
                'Wizard.Tests.conjoint',
                array(
                    'Personne' => array(
                        'rolepers'  => 'CJT',
                        'qual'      => 'MME',
                        'nom'       => 'Buffin',
                        'prenom'    => 'Simone',
                        'dtnai'   => array(
                            'day'   => 01,
                            'month' => 01,
                            'year'  => 2009
                        ),
                        'rgnai' => 1,
                        'nir' => rand_nir(),
                        'topvalec' => 0,
                        'nati' => 'C',
                        'pieecpres' => 'E'
                    )
                )
            );
            $this->Session->write(
                'Wizard.Tests.adresse',
                array(
                    'Adressefoyer' => array(
                        'rgadr'     => '01',
                        'typeadr'   => 'D'
                    ),
                    'Adresse' => array(
                        'numvoie' => 8,
                        'typevoie' => 'rue',
                        'nomvoie' => 'des rosiers',
                        'codepos' => '34000',
                        'locaadr' => 'Montpellier',
                        'pays' => 'FRA'
                    ),
                )
            );
            return parent::beforeFilter();
        }

        /**
        *
        */
        function confirm() {
        }

        /**
        *
        */
        function wizard( $step = null ) {
            switch( $step ) {
                case 'allocataire':
                case 'conjoint':
                    $this->set( 'qual', $this->Option->qual() );
                    $this->set( 'nationalite', $this->Option->nationalite() );
                    $this->set( 'typedtnai', $this->Option->typedtnai() );
                    $this->set( 'pieecpres', $this->Option->pieecpres() );
                    $this->set( 'rolepers', $this->Option->rolepers() );
                    break;
                case 'adresse':
                    $this->set( 'pays', $this->Option->pays() );
                    $this->set( 'rgadr', $this->Option->rgadr() );
                    $this->set( 'typeadr', $this->Option->typeadr() );
                    break;
                case 'ressourcesallocataire':
                    $wizardData = $this->Wizard->read();
                    if( hasConjoint( $wizardData['conjoint']['Personne'] ) ) { // FIXME
                        $this->Wizard->branch( 'withConjoint' );
                    }
                    else {
                        $this->Wizard->branch( 'noConjoint' );
                    }
                case 'ressourcesconjoint':
                    $this->set( 'natress', $this->Option->natress() );
                    $this->set( 'abaneu', $this->Option->abaneu() );
                    break;
                case 'dossier':
                    $this->set( 'oridemrsa', $this->Option->oridemrsa() );
            }

            $this->Wizard->process( $step );
        }

        /**
        *
        */
        function _processAllocataire() {
            $this->Personne->set( $this->data );

            if( $this->Personne->validates() ) {
                return true;
            }
            return false;
        }

        /**
        *
        */
        function _processConjoint() {
            if( hasConjoint( $this->data['Personne'] ) ) { // FIXME
                $this->Personne->set( $this->data );

                if( $this->Personne->validates() ) {
                    return true;
                }
                return false;
            }
            else {
                return true;
            }
        }

        /**
        *
        */
        function _processAdresse() {
            $this->Adresse->set( $this->data );
            $this->Adressefoyer->set( $this->data );

            $valid = $this->Adresse->validates();
            $valid = $this->Adressefoyer->validates() && $valid;
            if( $valid ) {
                return true;
            }
            return false;
        }

        /**
        *
        */
        function _processRessourcesallocataire() {
            $this->Ressource->create();
            $this->Ressourcemensuelle->create();
            $this->Detailressourcemensuelle->create();

            $this->Ressource->set( $this->data['Ressource'] );

            $valid = $this->Ressource->validates();
            if( !empty( $this->data['Ressourcemensuelle'] ) ) {
                $valid = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $valid;
                if( !empty( $this->data['Detailressourcemensuelle'] ) ) {
                    $valid = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $valid;
                }
            }
            if( $valid ) {
                return true;
            }
            return false;
        }

        /**
        *
        */
        function _processRessourcesconjoint() {
            $wizardData = $this->Wizard->read();
            if( hasConjoint( $wizardData['conjoint']['Personne'] ) ) { // FIXME
                $this->Ressource->create();
                $this->Ressourcemensuelle->create();
                $this->Detailressourcemensuelle->create();

                $this->Ressource->set( $this->data['Ressource'] );

                $valid = $this->Ressource->validates();
                if( !empty( $this->data['Ressourcemensuelle'] ) ) {
                    $valid = $this->Ressourcemensuelle->saveAll( $this->data['Ressourcemensuelle'], array( 'validate' => 'only' ) ) && $valid;
                    if( !empty( $this->data['Detailressourcemensuelle'] ) ) {
                        $valid = $this->Detailressourcemensuelle->saveAll( $this->data['Detailressourcemensuelle'], array( 'validate' => 'only' ) ) && $valid;
                    }
                }
                if( $valid ) {
                    return true;
                }
                return false;
            }
            else {
                return true;
            }
        }

        /**
        *
        */
        function _processDossier() {
            $this->Dossier->set( $this->data );
            $this->Foyer->set( $this->data );

            $valid = $this->Dossier->validates();
            $valid = $this->Foyer->validates() && $valid;
            if( $valid ) {
                return true;
            }
            return false;
        }

        /**
        * Wizard Completion Callback
        */
        function _afterComplete() {
            $data = $this->Wizard->read();

            // Revalidation
            $this->Personne->set( $data['allocataire']['Personne'] );
            $valid = $this->Personne->validates();

            if( hasConjoint( $data['conjoint']['Personne'] ) ) { // FIXME
                $this->Personne->set( $data['conjoint']['Personne'] );
                $valid = $this->Personne->validates() && $valid;
            }

            $this->Adresse->set( $data['adresse']['Adresse'] );
            $this->Adressefoyer->set( $data['adresse']['Adressefoyer'] );
            $valid = $this->Adresse->validates() && $valid;
            $valid = $this->Adressefoyer->validates() && $valid;

            // Ressources allocataire
            $this->Ressource->create();
            $this->Ressourcemensuelle->create();
            $this->Detailressourcemensuelle->create();

            $this->Ressource->set( $data['ressourcesallocataire'] );
            $valid = $this->Ressource->validates();
            if( !empty( $data['ressourcesallocataire']['Ressourcemensuelle'] ) ) {
                $valid = $this->Ressourcemensuelle->saveAll( $data['ressourcesallocataire'], array( 'validate' => 'only' ) ) && $valid;
                if( !empty( $data['ressourcesallocataire']['Detailressourcemensuelle'] ) ) {
                    $valid = $this->Detailressourcemensuelle->saveAll( $data['ressourcesallocataire'], array( 'validate' => 'only' ) ) && $valid;
                }
            }

            // Ressources conjoint
            if( hasConjoint( $data['conjoint']['Personne'] ) ) { // FIXME
                $this->Ressource->create();
                $this->Ressourcemensuelle->create();
                $this->Detailressourcemensuelle->create();

                $this->Ressource->set( $data['ressourcesconjoint'] );
                $valid = $this->Ressource->validates();
                if( !empty( $data['ressourcesconjoint']['Ressourcemensuelle'] ) ) {
                    $valid = $this->Ressourcemensuelle->saveAll( $data['ressourcesconjoint'], array( 'validate' => 'only' ) ) && $valid;
                    if( !empty( $data['ressourcesconjoint']['Detailressourcemensuelle'] ) ) {
                        $valid = $this->Detailressourcemensuelle->saveAll( $data['ressourcesconjoint'], array( 'validate' => 'only' ) ) && $valid;
                    }
                }
            }
/**
    TODO
        *
*/
            // Sauvegarde
            if( $valid ) {
                // Début de la transaction
                $this->Dossier->begin();

                // Tentatives de sauvegarde
                $saved = $this->Dossier->save( $data['dossier']['Dossier'] );
                // Détails du droit
                $data['dossier']['Detaildroitrsa']['dossier_rsa_id'] = $this->Dossier->id;
                $saved = $this->Detaildroitrsa->save( $data['dossier']['Detaildroitrsa'] ) && $saved;
                // Foyer
                $saved = $this->Foyer->save( array( 'dossier_rsa_id' => $this->Dossier->id ) ) && $saved;
                // Adresse
                $saved = $this->Adresse->save( $data['adresse']['Adresse'] ) && $saved;
                // Adresse foyer
                $data['adresse']['Adressefoyer']['foyer_id'] = $this->Foyer->id;
                $data['adresse']['Adressefoyer']['adresse_id'] = $this->Adresse->id;
                $saved = $this->Adressefoyer->save( $data['adresse']['Adressefoyer'] ) && $saved;
                // Demandeur
                $data['allocataire']['Personne']['foyer_id'] = $this->Foyer->id;
                $saved = $this->Personne->save( $data['allocataire']['Personne'] );
                $demandeur_id = $this->Personne->id;
                // Type orientation demandeur
                $this->Orientstruct->create();
                $saved = $this->Orientstruct->save( array( 'Orientstruct' => array( 'personne_id' => $demandeur_id, 'statut_orient' => 'Non orienté' ) ) );
                // Conjoint
                if( count( array_filter( $data['conjoint']['Personne'] ) ) != 3 ) { // FIXME
                    $this->Personne->create();
                    $data['conjoint']['Personne']['foyer_id'] = $this->Foyer->id;
                    $saved = $this->Personne->save( $data['conjoint']['Personne'] );
                    $conjoint_id = $this->Personne->id;

                    // Type orientation conjoint
                    $this->Orientstruct->create();
                    $saved = $this->Orientstruct->save( array( 'Orientstruct' => array( 'personne_id' => $conjoint_id, 'statut_orient' => 'Non orienté' ) ) );
                }
                // Ressources demandeur
                $this->Ressource->create();
                $data['ressourcesallocataire']['Ressource']['personne_id'] = $demandeur_id;
                $saved = $this->Ressource->save( $data['ressourcesallocataire'] ) && $saved;

                if( !empty( $data['ressourcesallocataire']['Ressourcemensuelle'] ) ) {
                    foreach( $data['ressourcesallocataire']['Ressourcemensuelle'] as $key => $ressourcemensuelle ) {
                        $ressourcemensuelle['ressource_id'] = $this->Ressource->id;
                        $this->Ressourcemensuelle->create();
                        $saved = $this->Ressourcemensuelle->save( $ressourcemensuelle ) && $saved;
                        if( !empty( $data['ressourcesallocataire']['Detailressourcemensuelle'] ) && !empty( $data['ressourcesallocataire']['Detailressourcemensuelle'][$key] ) ) {
                            $this->Detailressourcemensuelle->create();
                            $data['ressourcesallocataire']['Detailressourcemensuelle'][$key]['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                            $saved = $this->Detailressourcemensuelle->save( $data['ressourcesallocataire']['Detailressourcemensuelle'][$key] ) && $saved;
                        }
                    }
                }

                // Ressources conjoint
                if( hasConjoint( $data['conjoint']['Personne'] ) ) { // FIXME
                    $this->Ressource->create();
                    $data['ressourcesconjoint']['Ressource']['personne_id'] = $conjoint_id;
                    $saved = $this->Ressource->save( $data['ressourcesconjoint'] ) && $saved;

                    if( !empty( $data['ressourcesconjoint']['Ressourcemensuelle'] ) ) {
                        foreach( $data['ressourcesconjoint']['Ressourcemensuelle'] as $key => $ressourcemensuelle ) {
                            $ressourcemensuelle['ressource_id'] = $this->Ressource->id;
                            $this->Ressourcemensuelle->create();
                            $saved = $this->Ressourcemensuelle->save( $ressourcemensuelle ) && $saved;
                            if( !empty( $data['ressourcesconjoint']['Detailressourcemensuelle'] ) && !empty( $data['ressourcesconjoint']['Detailressourcemensuelle'][$key] ) ) {
                                $this->Detailressourcemensuelle->create();
                                $data['ressourcesconjoint']['Detailressourcemensuelle'][$key]['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                                $saved = $this->Detailressourcemensuelle->save( $data['ressourcesconjoint']['Detailressourcemensuelle'][$key] ) && $saved;
                            }
                        }
                    }
                }

                // Fin de la transaction
                if( $saved ) {
                    $this->Dossier->commit();
                }
                // Annulation de la transaction
                else {
                    $this->Dossier->rollback();
                    $this->cakeError( 'error500' ); // FIXME
                }
            }
        }
    }
?>
