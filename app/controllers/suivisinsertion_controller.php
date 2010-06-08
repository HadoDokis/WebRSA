<?php
    class SuivisinsertionController extends AppController
    {

        var $name = 'Suivisinsertion';
        var $uses = array( 'Foyer', 'Dossier', 'Suiviinstruction', 'Contratinsertion', 'Orientstruct', 'Structurereferente', 'Typocontrat', 'Typeorient', 'Actioninsertion', 'Option', 'Rendezvous', 'Aidedirecte', 'Cui' );
//         var $helpers = array( 'Locale', 'Csv' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'decision_ci', $this->Option->decision_ci() );

        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $dossier_rsa_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $dossier_rsa_id ), 'error404' );

            $details = array();

            $tDossier = $this->Dossier->findById( $dossier_rsa_id, null, null, -1 );
            $this->assert( !empty( $tDossier ), 'invalidParameter' );
            $details = Set::merge( $details, $tDossier );

            $tFoyer = $this->Dossier->Foyer->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );
            $this->assert( !empty( $tFoyer ), 'invalidParameter' );
            $details = Set::merge( $details, $tFoyer );

            // Récupération du services instructeur lié au contrat
            $user = $this->User->findById( $this->Session->read( 'Auth.User.id' ), null, null, 0 );
            $this->assert( !empty( $user ), 'error500' ); // FIXME
            $details = Set::merge( $details, $user );

            /**
                Personnes
            */
            $bindPrestation = $this->Personne->hasOne['Prestation'];
            $this->Personne->unbindModelAll();
            $this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Dsp', 'Prestation' => $bindPrestation ) ) );
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
                // Contrat insertion lié à la personne
                $tContratinsertion = $this->Contratinsertion->find(
                    'first',
                    array(
                        'conditions' => array( 'Contratinsertion.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
                        'recursive' => -1,
                        'order' => array( 'Contratinsertion.rg_ci DESC' )
                    )
                );
                $personnesFoyer[$index]['Contratinsertion'] = $tContratinsertion['Contratinsertion'];


                // Contrat insertion lié à la personne
                $tCui = $this->Cui->find(
                    'first',
                    array(
                        'conditions' => array( 'Cui.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
                        'recursive' => -1,
                        'order' => array( 'Cui.datecontrat DESC' )
                    )
                );
                $personnesFoyer[$index]['Cui'] = $tCui['Cui'];

                // Actions insertions engagées par la personne
//                 $this->Actioninsertion->unbindModelAll();
//                 $this->Actioninsertion->bindModel( array( 'hasMany' => array( 'Aidedirecte', 'Prestform' ) ) );
                $tActioninsertion = $this->Actioninsertion->find(
                    'all',
                    array(
                        'conditions' => array( 'Actioninsertion.contratinsertion_id' => $personnesFoyer[$index]['Contratinsertion']['id'] ),
                        'recursive' => -1,
                        'order' => 'Actioninsertion.dd_action DESC'
                    )
                );
                $personnesFoyer[$index]['Actioninsertion'] = Set::extract( $tActioninsertion, '/Actioninsertion' );

                // Premier Rendez-vous
                $tRendezvous = $this->Rendezvous->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Rendezvous.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Rendezvous.daterdv ASC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Rendezvous']['premier'] = $tRendezvous['Rendezvous'];

                // Dernier Rendez-vous
                $tRendezvous = $this->Rendezvous->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Rendezvous.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Rendezvous.daterdv DESC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Rendezvous']['dernier'] = $tRendezvous['Rendezvous'];


                // Première Orientation
                $tOrientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Orientstruct.date_valid ASC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Orientstruct']['premiere'] = $tOrientstruct['Orientstruct'];

                // Dernière Orientation
                $tOrientstruct = $this->Orientstruct->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
                        ),
                        'order' => 'Orientstruct.date_valid DESC',
                        'recursive' => -1
                    )
                );
                $personnesFoyer[$index]['Orientstruct']['derniere'] = $tOrientstruct['Orientstruct'];

                $details[$role] = $personnesFoyer[$index];
            }

            // Structure référentes
            $structuresreferentes = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
            $typesorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
            $typoscontrat = $this->Typocontrat->find( 'list', array( 'fields' => array( 'id', 'lib_typo' ) ) );
            $this->set( 'structuresreferentes', $structuresreferentes );
            $this->set( 'typesorient', $typesorient );
            $this->set( 'typoscontrat', $typoscontrat );

            $this->set( 'dossier_rsa_id', $dossier_rsa_id );
            $this->set( 'details', $details );

        }

    }
?>