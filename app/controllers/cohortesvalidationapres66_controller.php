<?php
    class Cohortesvalidationapres66Controller extends AppController
    {
        public $name = 'Cohortesvalidationapres66';

        public $uses = array(
            'Cohortevalidationapre66',
            'Apre',
            'Apre66',
            'Aideapre66',
            'Zonegeographique',
            'Dossier',
            'Canton'
        );

        public $helpers = array( 'Csv', 'Ajax', 'Default2' );

        var $components = array( 'Prg' => array( 'actions' => array( 'validees' ) ) );

//         public $paginate = array( 'limit' => 20 );


        /**
        *
        */
        public function _setOptions() {
            $this->set( 'options',  $this->Apre66->allEnumLists() );
            $this->set( 'optionsaideapre66',  $this->Aideapre66->allEnumLists() );
            $this->set( 'referents',  $this->Apre->Referent->find( 'list' ) );
        }



        /**
        *
        */

        public function apresavalider() {
            $this->_index( 'Validationapre::apresavalider' );
        }

        /**
        *
        */

        public function validees() {
            $this->_index( 'Validationapre::validees' );
        }


        /**
        *
        */

        protected function _index( $statutValidation = null ) {
            $this->assert( !empty( $statutValidation ), 'invalidParameter' );

            if( Configure::read( 'CG.cantons' ) ) {
                $this->set( 'cantons', $this->Canton->selectList() );
            }

            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            if( !empty( $this->data ) ) {

//     debug($this->data);

                /**
                *
                * Sauvegarde
                *
                */

                // On a renvoyé  le formulaire de la cohorte
                if( !empty( $this->data['Aideapre66'] ) ) {

                    // Ajout des règles de validation
                    $this->Apre66->Aideapre66->validationDecisionAllowEmpty( false );

                    $valid = $this->Apre66->Aideapre66->saveAll( $this->data['Aideapre66'], array( 'validate' => 'only', 'atomic' => false ) );


                    if( $valid ) {
                        $this->Aideapre66->begin();
                        $saved = $this->Apre66->Aideapre66->saveAll( $this->data['Aideapre66'], array( 'validate' => 'first', 'atomic' => false ) );

                        if( $saved ) {
                            // FIXME ?
                            foreach( array_unique( Set::extract( $this->data, 'Aideapre66.{n}.dossier_id' ) ) as $dossier_id ) {
                                $this->Jetons->release( array( 'Dossier.id' => $dossier_id ) );
                            }
                            $this->Aideapre66->commit();
                        }
                        else {
                            $this->Aideapre66->rollback();
                        }
                    }
                }

                /**
                *
                * Filtrage
                *
                */

                if( ( $statutValidation == 'Validationapre::apresavalider' ) || ( ( $statutValidation == 'Validationapre::validees' ) && !empty( $this->data ) ) ) {
                    $this->Dossier->begin(); // Pour les jetons

                    $this->paginate = $this->Cohortevalidationapre66->search( $statutValidation, $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
                    $this->paginate['limit'] = 10;
                    $cohortevalidationapre66 = $this->paginate( 'Apre' );

                    $this->Dossier->commit();
                    foreach( $cohortevalidationapre66 as $key => $value ) {
/*
                        if( empty( $value['Aideapre66']['decisionapre'] ) ) {
                            $cohortevalidationapre66[$key]['Aideapre66']['proposition_decisionapre'] = '';
                        }
                        else {
                            $cohortevalidationapre66[$key]['Aideapre66']['proposition_decisionapre'] = $value['Aideapre66']['decisionapre'];
                        }*/

                        if( empty( $value['Aideapre66']['datemontantaccorde'] ) ) {
                            $cohortevalidationapre66[$key]['Aideapre66']['proposition_datemontantaccorde'] = date( 'Y-m-d' );
                        }
                        else{
                            $cohortevalidationapre66[$key]['Aideapre66']['proposition_datemontantaccorde'] = $value['Aideapre66']['datemontantaccorde'];
                        }

                    }
                    $this->set( 'cohortevalidationapre66', $cohortevalidationapre66 );

                }

            }

            $this->_setOptions();
            if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
                $this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
            }
            else {
                $this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
            }


            switch( $statutValidation ) {
                case 'Validationapre::apresavalider':
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Validationapre::validees':
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }


        }
    }
?>