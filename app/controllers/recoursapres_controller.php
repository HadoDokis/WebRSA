<?php
    App::import('Sanitize');

    class RecoursapresController extends AppController
    {
        var $name = 'Recoursapres';
        var $uses = array( 'Canton', 'Dossier', 'Recoursapre', 'Foyer', 'Adresse', 'Comiteapre', 'Personne', 'ApreComiteapre', 'Apre', 'Option', 'Adressefoyer' );

        var $components = array( 'Gedooo' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        /**
        */


        function beforeFilter() {
            $return = parent::beforeFilter();
            $options = array(
                'decisioncomite' => array(
                    'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
                    'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
                    'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
                ),
                'recoursapre' => array(
                    'N' => __d( 'apre', 'ENUM::RECOURSAPRE::N', true ),
                    'O' => __d( 'apre', 'ENUM::RECOURSAPRE::O', true )
                )
            );
            $this->set( 'options', $options );

            $this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function demande() {
            $this->_index( 'Recoursapre::demande' );
        }

        //---------------------------------------------------------------------

        function visualisation() {
            $this->_index( 'Recoursapre::visualisation' );
        }
        /** ********************************************************************
        *
        *** *******************************************************************/

        function _index( $avisRecours = null ){
            $this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );

            $this->Dossier->begin(); // Pour les jetons
            if( !empty( $this->data ) ) {
                if( !empty( $this->data['ApreComiteapre'] ) ) {
                    $data = Set::extract( $this->data, '/ApreComiteapre' );
                    if( $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                        $saved = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
                            $this->ApreComiteapre->commit();
                            $this->redirect( array( 'action' => 'demande' ) ); // FIXME
                        }
                        else {
                            $this->ApreComiteapre->rollback();
                        }
                    }
                }


                $recoursapres = $this->Recoursapre->search( $avisRecours, $this->data );
                $recoursapres['limit'] = 10;
                $this->paginate = $recoursapres;
                $recoursapres = $this->paginate( 'ApreComiteapre' );

                $this->set( 'recoursapres', $recoursapres );

                $this->Dossier->commit();

            }

            switch( $avisRecours ) {
                case 'Recoursapre::demande':
                    $this->set( 'pageTitle', 'Demandes de recours' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Recoursapre::visualisation':
                    $this->set( 'pageTitle', 'Visualisation des recours' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }

            $this->Dossier->commit(); //FIXME
        }

        /// Export du tableau en CSV
        function exportcsv() {

            $querydata = $this->Recoursapre->search( "Recoursapre::visualisation", array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $recoursapres = $this->ApreComiteapre->find( 'all', $querydata );

            $this->layout = '';
            $this->set( compact( 'recoursapres' ) );
        }


        /**
        * Notifications des Comités d'examen
        **/

        function notificationsrecoursgedooo( $apre_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();
            $natureAidesApres = $this->Option->natureAidesApres();
            $options = $this->ApreComiteapre->allEnumLists();
            $this->set( 'options', $options );

            $isRecours = null;

            $apre = $this->Apre->find(
                'first',
                array(
                    'conditions' => array(
                        'Apre.id' => $apre_id
                    )
                )
            );

            unset( $apre['Apre']['Piecemanquante'] );
            unset( $apre['Apre']['Piecepresente'] );
            unset( $apre['Apre']['Piece'] );
            unset( $apre['Pieceapre'] );
            unset( $apre['Comiteapre'] );
            unset( $apre['Relanceapre'] );

            ///Données nécessaire pour savoir quelles sont les aides liées à l'APRE Complémentaire
            foreach( $this->Apre->aidesApre as $model ) {
                if( ( $apre['Apre']['Natureaide'][$model] == 0 ) ){
                   unset( $apre['Apre']['Natureaide'][$model] );

                }
            }
            $apre['Apre']['Natureaide'] = array_keys( $apre['Apre']['Natureaide'] );

            foreach( $apre['Apre']['Natureaide'] as $i => $aides ){
                $apre['Apre']['Natureaide'][$i] = Set::enum( $aides, $natureAidesApres );
            }

            $apre['Apre']['Natureaide'] = '  - '.implode( "\n  - ", $apre['Apre']['Natureaide'] )."\n";

            ///Données faisant le lien entre l'APRE et son comité
            $aprecomiteapre = $this->ApreComiteapre->find(
                'first',
                array(
                    'conditions' => array(
                        'ApreComiteapre.apre_id' => $apre_id
                    )
                )
            );
            $apre['ApreComiteapre'] = $aprecomiteapre['ApreComiteapre'];

            ///Données concernant le comité de l'APRE
            $comiteapre = $this->Comiteapre->find(
                'first',
                array(
                    'conditions' => array(
                        'Comiteapre.id' => Set::classicExtract( $apre, 'ApreComiteapre.comiteapre_id' )
                    )
                )
            );
            $apre['Comiteapre'] = $comiteapre['Comiteapre'];
            ///Pour la date du comité
            $apre['Comiteapre']['datecomite'] =  date_short( Set::classicExtract( $apre, 'Comiteapre.datecomite' ) );

            ///Données nécessaire pour obtenir l'adresse du bénéficiaire
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
                        'Adressefoyer.foyer_id' => Set::classicExtract( $apre, 'Personne.foyer_id' ),
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $apre['Adresse'] = $adresse['Adresse'];

            ///Pour la qualité des Personnes  Personne + Référent APRE
            $apre['Referentapre']['qual'] = Set::extract( $qual, Set::extract( $apre, 'Referentapre.qual' ) );
            $apre['Personne']['qual'] = Set::extract( $qual, Set::extract( $apre, 'Personne.qual' ) );

            ///Paramètre nécessaire pour le bon choix du document à éditer
            $dest = Set::classicExtract( $this->params, 'named.dest' );

            ///Paramètre pour savoir si demande de recours ou non
            $recoursapre = Set::enum( Set::classicExtract( $apre, 'ApreComiteapre.recoursapre' ), $options['recoursapre'] );
            $this->set( 'recoursapre', $recoursapre );

            ///Pour la date du comité
            $apre['ApreComiteapre']['daterecours'] =  date_short( Set::classicExtract( $apre, 'ApreComiteapre.daterecours' ) );
// debug($apre);
// die();
            if( $dest == 'beneficiaire' ) {
                $this->Gedooo->generate( $apre, 'APRE/DecisionComite/Recours/recours'.$recoursapre.''.$dest.'.odt' );
            }
            else if( $dest == 'referent' ) {
                $this->Gedooo->generate( $apre, 'APRE/DecisionComite/Recours/recours'.$dest.'.odt' );
            }
        }

    }
?>