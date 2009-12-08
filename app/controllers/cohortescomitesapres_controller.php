<?php
    App::import('Sanitize');
    class CohortescomitesapresController extends AppController
    {
        var $name = 'Cohortescomitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'ApreComiteapre', 'Cohortecomiteapre', 'Comiteapre', 'Participantcomite', 'Apre', 'Referentapre', 'ComiteapreParticipantcomite', 'Adressefoyer' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $components = array( 'Gedooo' );

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'aviscomite', 'notificationscomite' ) ) ) );
            parent::__construct();
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {
            parent::beforeFilter();
            $this->set( 'referentapre', $this->Referentapre->find( 'list' ) );

            // FIXME
            //$options = $this->Apre->ApreComiteapre->allEnumLists();
            $options = array(
                'decisioncomite' => array(
                    'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
                    'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
                    'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
                )
            );
            $this->Apre->ApreComiteapre->validate = array(
                'decisioncomite' => array(
                    array(
                        'rule'      => array( 'inList', array( 'AJ', 'ACC', 'REF' ) ),
                        'message'   => 'Veuillez choisir une valeur.',
                        'allowEmpty' => false
                    )
                ),
                'montantattribue' => array(
                    array(
                        'rule' => 'numeric',
                        'message' => 'Veuillez entrer une valeur numérique.',
                        'allowEmpty' => false,
                        'required' => false
                    ),
                    array(
                        'rule' => array( 'between', 0, 4 ),
                        'message' => 'Veuillez entrer une valeur entre 0 et 9999.',
                        'allowEmpty' => false,
                        'required' => false
                    ),
                ),
            );
            $this->set( 'options', $options );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function aviscomite() {
            $this->_index( 'Cohortecomiteapre::aviscomite' );
        }

        //---------------------------------------------------------------------

        function notificationscomite() {
            $this->_index( 'Cohortecomiteapre::notificationscomite' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function _index( $avisComite = null ){
            $this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );

            $isRapport = ( Set::classicExtract( $this->params, 'named.rapport' ) == 1 );
            $idRapport = Set::classicExtract( $this->params, 'named.Cohortecomiteapre__id' );

            $this->Dossier->begin(); // Pour les jetons
            if( !empty( $this->data ) ) {
                if( !empty( $this->data['ApreComiteapre'] ) ) {
                    $data = Set::extract( $this->data, '/ApreComiteapre' );
                    if( $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                        $saved = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );
                        if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
                            $this->ApreComiteapre->commit();
                            if( !$isRapport ){
                                $this->redirect( array( 'action' => 'aviscomite' ) ); // FIXME
                            }
                            else if( $isRapport ) {
                                $this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', $idRapport ) );
                            }
                        }
                        else {
                            $this->ApreComiteapre->rollback();
                        }
                    }
                }

                $comitesapres = $this->Cohortecomiteapre->search( $avisComite, $this->data );
                $comitesapres['limit'] = 10;
                $this->paginate = $comitesapres;
                $comitesapres = $this->paginate( 'Comiteapre' );


                $this->set( 'comitesapres', $comitesapres );
            }

            switch( $avisComite ) {
                case 'Cohortecomiteapre::aviscomite':
                    $this->set( 'pageTitle', 'Décisions des comités' );
                    $this->render( $this->action, null, 'formulaire' );
                    break;
                case 'Cohortecomiteapre::notificationscomite':
                    $this->set( 'pageTitle', 'Notifications décisions comités' );
                    $this->render( $this->action, null, 'visualisation' );
                    break;
            }

            $this->Dossier->commit(); //FIXME
        }


        function exportcsv() {

            $querydata = $this->Cohortecomiteapre->search( null, array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );
            $decisionscomites = $this->Comiteapre->find( 'all', $querydata );

            $this->layout = '';
            $this->set( compact( 'decisionscomites' ) );

        }

        /**
        * Notifications des Comités d'examen
        **/

        function notificationscomitegedooo( $apre_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();
            $natureAidesApres = $this->Option->natureAidesApres();
            $options = $this->ApreComiteapre->allEnumLists();
            $this->set( 'options', $options );

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

            ///Paramètre nécessaire pour savoir quelle décision le comité a prise
            $typedecision = Set::enum( Set::classicExtract( $apre, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] );
            $this->set( 'typedecision', $typedecision );

            ///Pour la qualité des Personnes  Personne + Référent APRE
            $apre['Referentapre']['qual'] = Set::extract( $qual, Set::extract( $apre, 'Referentapre.qual' ) );
            $apre['Personne']['qual'] = Set::extract( $qual, Set::extract( $apre, 'Personne.qual' ) );

            ///Paramètre nécessaire pour le bon choix du document à éditer
            $dest = Set::classicExtract( $this->params, 'named.dest' );

// debug($apre);
// die();

            $this->Gedooo->generate( $apre, 'APRE/DecisionComite/'.$typedecision.'/refus'.$dest.'.odt' );
        }
    }
?>