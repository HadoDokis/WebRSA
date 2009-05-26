<?php
    class TestsController extends AppController {
        var $components = array( 'Wizard' );
        var $uses = array( 'Dossier', 'Foyer', 'Personne', 'Adresse', 'Adressefoyer', 'Option', 'Ressource', 'Ressourcemensuelle',  'Detailressourcemensuelle' );

        /**
        *
        */
        function beforeFilter() {
            $this->Wizard->steps = array( 'allocataire', 'conjoint', 'adresse', 'ressourcesallocataire', 'dossier' );
            $this->Wizard->completeUrl = '/tests/confirm';
            $this->Wizard->cancelUrl = '/tests/wizard';
            parent::beforeFilter();
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
                case 'dossier':
                    $services = array(
                        1 => 'Association agrée',
                        2 => 'Pôle Emploi',
                        3 => 'Service Social du Département',
                    );
                    $this->set( 'services', $services );
                    break;
                case 'ressourcesallocataire':
                    $this->set( 'natress', $this->Option->natress() );
                    $this->set( 'abaneu', $this->Option->abaneu() );
                    break;
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
            if( count( array_filter( $this->data['Personne'] ) ) > 3 ) { // FIXME
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
            $this->Ressource->set( $this->data );
            $this->Ressourcemensuelle->set( $this->data );
            $this->Detailressourcemensuelle->set( $this->data );

            $valid = $this->Ressource->validates();
            $valid = $this->Ressourcemensuelle->validates() && $valid;
            $valid = $this->Detailressourcemensuelle->validates() && $valid;
            if( $valid ) {
                return true;
            }
            return false;
        }

        /**
        *
        */
        function _processRessourcesconjoint() {
            if( count( array_filter( $this->data['Personne'] ) ) > 3 ) { // FIXME
                $this->Ressource->set( $this->data );
                $this->Ressourcemensuelle->set( $this->data );
                $this->Detailressourcemensuelle->set( $this->data );

                $valid = $this->Ressource->validates();
                $valid = $this->Ressourcemensuelle->validates() && $valid;
                $valid = $this->Detailressourcemensuelle->validates() && $valid;
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
            $validates = $this->Personne->validates();

            if( count( array_filter( $data['conjoint']['Personne'] ) ) != 3 ) { // FIXME
                $this->Personne->set( $data['conjoint']['Personne'] );
                $validates = $this->Personne->validates() && $validates;
            }

            $this->Adresse->set( $data['adresse']['Adresse'] );
            $this->Adressefoyer->set( $data['adresse']['Adressefoyer'] );
            $validates = $this->Adresse->validates() && $validates;
            $validates = $this->Adressefoyer->validates() && $validates;

            $this->Ressource->set( $data['ressourcesallocataire'] );
            $this->Ressourcemensuelle->set( $data['ressourcesallocataire'] );
            $this->Detailressourcemensuelle->set( $data['ressourcesallocataire'] );

            $valid = $this->Ressource->validates();
            $valid = $this->Ressourcemensuelle->validates() && $valid;
            $valid = $this->Detailressourcemensuelle->validates() && $valid;

//             if( count( array_filter( $data['conjoint']['Personne'] ) ) != 3 ) { // FIXME
//                 $this->Ressource->create();
//                 $this->Ressource->set( $data['ressourcesconjoint'] );
//                 $this->Ressourcemensuelle->set( $data['ressourcesconjoint'] );
//                 $this->Detailressourcemensuelle->set( $data['ressourcesconjoint'] );
//
//                 $valid = $this->Ressource->validates();
//                 $valid = $this->Ressourcemensuelle->validates() && $valid;
//                 $valid = $this->Detailressourcemensuelle->validates() && $valid;
//             }
/**
    TODO
        *
*/
//             debug( $data );  // FIXME!!!
            // Sauvegarde
            if( $validates ) {
                // Début de la transaction
                $this->Dossier->begin();

                // Tentatives de sauvegarde
                $saved = $this->Dossier->save( $data['dossier']['Dossier'] );
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
                // Conjoint
                if( count( array_filter( $data['conjoint']['Personne'] ) ) != 3 ) { // FIXME
                    $this->Personne->create();
                    $data['conjoint']['Personne']['foyer_id'] = $this->Foyer->id;
                    $saved = $this->Personne->save( $data['conjoint']['Personne'] );
                    $conjoint_id = $this->Personne->id;
                }
                // Ressources demandeur
                $data['ressourcesallocataire']['Ressource']['personne_id'] = $demandeur_id;
                $saved = $this->Ressource->save( $data['ressourcesallocataire'] ) && $saved;
                if( !empty( $data['ressourcesallocataire']['Ressourcemensuelle'] ) ) {
                    $data['ressourcesallocataire']['Ressourcemensuelle']['ressource_id'] = $this->Ressource->id;
                    $saved = $this->Ressourcemensuelle->save( $data['ressourcesallocataire'] ) && $saved;
                    if( !empty( $data['ressourcesallocataire']['Detailressourcemensuelle'] ) ) {
                        $data['ressourcesallocataire']['Detailressourcemensuelle']['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                        $saved = $this->Detailressourcemensuelle->save( $data['ressourcesallocataire'] ) && $saved;
                    }
                }

                // Conjoint
                if( count( array_filter( $data['conjoint']['Personne'] ) ) != 3 ) { // FIXME
                    $data['ressourcesconjoint']['Ressource']['personne_id'] = $conjoint_id;
                    $saved = $this->Ressource->save( $data['ressourcesconjoint'] ) && $saved;
                    if( !empty( $data['ressourcesconjoint']['Ressourcemensuelle'] ) ) {
                        $data['ressourcesconjoint']['Ressourcemensuelle']['ressource_id'] = $this->Ressource->id;
                        $saved = $this->Ressourcemensuelle->save( $data['ressourcesconjoint'] ) && $saved;
                        if( !empty( $data['ressourcesconjoint']['Detailressourcemensuelle'] ) ) {
                            $data['ressourcesconjoint']['Detailressourcemensuelle']['ressourcemensuelle_id'] = $this->Ressourcemensuelle->id;
                            $saved = $this->Detailressourcemensuelle->save( $data['ressourcesconjoint'] ) && $saved;
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
