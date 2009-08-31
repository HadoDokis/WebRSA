<?php
    class SuivisinsertionController extends AppController
    {

        var $name = 'Suivisinsertion';
        var $uses = array( 'Foyer', 'Dossier', 'Suiviinstruction', 'Contratinsertion', 'Orientstruct', 'Structurereferente', 'Typocontrat', 'Typeorient', 'Actioninsertion', 'Option' );
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

        function index( $id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $id ), 'error404' );

            $details = array();

            $tDossier = $this->Dossier->findById( $id, null, null, -1 );
            $details = Set::merge( $details, $tDossier );

            $tFoyer = $this->Dossier->Foyer->findByDossierRsaId( $id, null, null, -1 );
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
            $this->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Dspp', 'Prestation' => $bindPrestation ) ) );
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


                // Actions insertions engagées par la personne
                $tActioninsertion = $this->Actioninsertion->find(
                    'first',
                    array(
                        'conditions' => array( 'Actioninsertion.contratinsertion_id' => $personnesFoyer[$index]['Contratinsertion']['id'] ),
                        'recursive' => -1,
//                         'order' => array( 'Actioninsertion.rg_ci DESC' )
                    )
                );
                $personnesFoyer[$index]['Actioninsertion'] = $tActioninsertion['Actioninsertion'];

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

            $this->set( 'details', $details );

        }

    }
?>