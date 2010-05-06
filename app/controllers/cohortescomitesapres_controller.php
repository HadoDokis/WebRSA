<?php
    App::import('Sanitize');
    class CohortescomitesapresController extends AppController
    {
        var $name = 'Cohortescomitesapres';
        var $uses = array( 'Apre', 'Option', 'Personne', 'ApreComiteapre', 'Cohortecomiteapre', 'Comiteapre', 'Participantcomite', 'Apre', 'ComiteapreParticipantcomite', 'Adressefoyer', 'Tiersprestataireapre', 'Suiviaideapretypeaide', 'Referent' );
        var $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Xhtml' );
        var $components = array( 'Gedooo' );

        function __construct() {
            $this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( /*'aviscomite',*/ 'notificationscomite' ) ) ) );
            parent::__construct();
        }


        /** ********************************************************************
        *
        *** *******************************************************************/

        function beforeFilter() {

            parent::beforeFilter();
            $this->set( 'referent', $this->Referent->find( 'list' ) );

            // FIXME
            //$options = $this->Apre->ApreComiteapre->allEnumLists();
            $options = array(
                'decisioncomite' => array(
                    'ACC' => __d( 'apre', 'ENUM::DECISIONCOMITE::ACC', true ),
                    'AJ' => __d( 'apre', 'ENUM::DECISIONCOMITE::AJ', true ),
                    'REF' => __d( 'apre', 'ENUM::DECISIONCOMITE::REF', true ),
                )
            );
            /*$this->Apre->ApreComiteapre->validate = array(
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
                        'rule' => array( 'between', 0, 7 ),
                        'message' => 'Veuillez entrer une valeur entre 0 et 9999.',
                        'allowEmpty' => false,
                        'required' => false
                    ),
                ),
            );*/
            $this->set( 'options', $options );
// debug( $this->data );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function aviscomite() {
// debug( $this->data );
            $this->_index( 'Cohortecomiteapre::aviscomite' );
        }

        //---------------------------------------------------------------------

        function notificationscomite() {
            $this->_index( 'Cohortecomiteapre::notificationscomite' );
        }
//         //---------------------------------------------------------------------
//
//         function editdecision() {
//             $this->_index( 'Cohortecomiteapre::editdecision' );
//         }
        /** ********************************************************************
        *
        *** *******************************************************************/

        function _index( $avisComite = null ) {
// debug( $this->data );
            $this->set( 'comitesapre', $this->Comiteapre->find( 'list' ) );

            $isRapport = ( Set::classicExtract( $this->params, 'named.rapport' ) == 1 );
            $idRapport = Set::classicExtract( $this->params, 'named.Cohortecomiteapre__id' );

            $this->Dossier->begin(); // Pour les jetons
            if( !empty( $this->data ) ) {
                // Sauvegarde
                if( !empty( $this->data['ApreComiteapre'] ) ) {
                    $data = Set::extract( $this->data, '/ApreComiteapre' );
                    $dataApre = Set::combine( $this->data, 'ApreComiteapre.{n}.apre_id', 'ApreComiteapre.{n}.montantattribue' );
                    $return = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) );

                    if( $return ) {
                        $return = $this->ApreComiteapre->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) );

                        $saved = $return;
                        foreach( $dataApre as $apre_id => $montantattribue ) {
                            $apre = $this->Apre->findById( $apre_id, null, null, -1 );
                            $apre['Apre']['montantaverser'] = ( !empty( $montantattribue ) ? $montantattribue : 0 );
                            $this->Apre->create( $apre );
//                 debug($apre);
                            $saved = $this->Apre->save( $apre ) && $saved;
                        }

                        if( $saved /*&& empty( $this->Apre->ApreComiteapre->validationErrors )*/ ) {
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

// debug( $comitesapres );
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
//                 case 'Cohortecomiteapre::editdecision':
//                     $this->set( 'pageTitle', 'Modifications décisions comités' );
//                     $this->render( $this->action, null, 'editdecision' );
//                     break;
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
        * Modifications du Comité d'examen
        **/

        function editdecision( $apre_id = null ) {

            // TODO: error404/error500 si on ne trouve pas les données
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();


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
            unset( $apre['Apre']['Natureaide'] );
            unset( $apre['Pieceapre'] );
            unset( $apre['Montantconsomme'] );
            foreach( $this->Apre->aidesApre as $model ){
                unset( $apre[$model] );
            }
            unset( $apre['Relanceapre'] );

            // Foyer
            $foyer = $this->Apre->Personne->Foyer->findById( $apre['Personne']['foyer_id'], null, null, -1 );
            $apre['Foyer'] = $foyer['Foyer'];

            // Dossier
            $dossier = $this->Apre->Personne->Foyer->Dossier->findById( $foyer['Foyer']['dossier_rsa_id'], null, null, -1 );
            $apre['Dossier'] = $dossier['Dossier'];

            // Adresse
            $adresse = $this->Apre->Personne->Foyer->Adressefoyer->Adresse->findById( $foyer['Foyer']['id'], null, null, -1 );
            $apre['Adresse'] = $adresse['Adresse'];

            $this->Dossier->begin(); // Pour les jetons
            if( !empty( $this->data ) ) {

                $data = Set::extract( $this->data, '/ApreComiteapre' );

                if( $this->ApreComiteapre->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
                    $saved = $this->ApreComiteapre->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) );
                    if( $saved && empty( $this->Apre->ApreComiteapre->validationErrors ) ) {
                        $this->ApreComiteapre->commit();
                        $this->redirect( array(  'controller' => 'comitesapres', 'action' => 'rapport', Set::classicExtract( $this->data, 'ApreComiteapre.comiteapre_id' ) ) );

                    }
                    else {
                        $this->ApreComiteapre->rollback();
                    }
                }
            }
            else {
                $this->data = $apre;
            }
            $this->Dossier->commit(); // Pour les jetons
            $this->set( 'apre', $apre );
        }

        /**
        * Notifications des Comités d'examen
        **/

        function notificationscomitegedooo( $apre_comiteapre_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $qual = $this->Option->qual();
            $typevoie = $this->Option->typevoie();
            $natureAidesApres = $this->Option->natureAidesApres();
            $options = $this->ApreComiteapre->allEnumLists();
            $this->set( 'options', $options );

			// Recherche de la décision du comté et des données de l'APRE associées
			$apre = $this->Apre->ApreComiteapre->findById( $apre_comiteapre_id, null, null, -1 );
			$this->assert( !empty( $apre ), 'invalidParameter' );

			$unbindings = array(
				'hasAndBelongsToMany' => array( 'Comiteapre', 'Pieceapre' ),
				'hasMany' => array( 'Relanceapre' )
			);
			$this->Apre->unbindModel( $unbindings );

			$apre = Set::merge(
				$apre,
				$this->Apre->findById( Set::classicExtract( $apre, 'ApreComiteapre.apre_id' ), null, null, 1 )
			);

            unset(
				$apre['Apre']['Piecemanquante'],
				$apre['Apre']['Piecepresente'],
				$apre['Apre']['Piece']/*,
				$apre['Apre']['Natureaide']*/
			);
// debug( $apre );
            ///Données nécessaire pour savoir quelles sont les aides liées à l'APRE Complémentaire + la pers chargée du suivi
            $apre['Dataperssuivi'] = array();
            foreach( $this->Apre->aidesApre as $model ) {
                if( ( $apre['Apre']['Natureaide'][$model] == 0 ) ){
                   unset( $apre['Apre']['Natureaide'][$model] );
                }
                else {
                    $personne = $this->Suiviaideapretypeaide->findByTypeaide( $model );
                    if( !empty( $personne['Suiviaideapre'] ) ) {
                        foreach( array_keys( $personne['Suiviaideapre'] ) as $key ) {
                            if( $key != 'id' ) {
                                $apre['Dataperssuivi']["{$key}suivi"] = $personne['Suiviaideapre'][$key];
                            }
                        }
                    }
                }
            }

            $modelFormation = array( 'Formqualif', 'Formpermfimo', 'Permisb', 'Actprof' );
            $modelHorsFormation = array( 'Acqmatprof', 'Amenaglogt', 'Locvehicinsert', 'Acccreaentr' );

            ///Paramètre nécessaire pour connaitre le type de formation du bénéficiaire (Formation / Hors Formation )
            if( array_any_key_exists( $modelFormation, $apre['Apre']['Natureaide'] ) ) {
                $typeformation = 'Formation';
            }
            else {
                $typeformation = 'HorsFormation';
            }

            $apre['Apre']['Natureaide'] = array_keys( $apre['Apre']['Natureaide'] );


            foreach( $modelFormation as $model ){
                $tmpId = Set::classicExtract( $apre, "{$model}.tiersprestataireapre_id" );
                if( !empty( $tmpId ) ) {
                    $dataTiersprestataireapre_id = $tmpId;
                }
            }



            ///Données faisant le lien entre l'APRE, ses Aides et le tiers prestataire lié à l'aide
            if( !empty( $dataTiersprestataireapre_id ) ) {
                $tiersprestataire = $this->Tiersprestataireapre->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Tiersprestataireapre.id' => $dataTiersprestataireapre_id
                        )
                    )
                );
                $apre['Tiersprestataireapre'] = $tiersprestataire['Tiersprestataireapre'];

                ///Pour l'adresse du tiers prestataire
                $apre['Tiersprestataireapre']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $apre, 'Tiersprestataireapre.typevoie' ) );

            }


            ///Données faisant le lien entre l'APRE et son comité
            /*$aprecomiteapre = $this->ApreComiteapre->find(
                'first',
                array(
                    'conditions' => array(
                        'ApreComiteapre.apre_id' => 3801
                    )
                )
            );
            $apre['ApreComiteapre'] = $aprecomiteapre['ApreComiteapre'];*/


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
			if( !empty( $apre['Referent']['qual'] ) ) {
				$apre['Referent']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $apre, 'Referent.qual' ) );
			}
			if( !empty( $apre['Personne']['qual'] ) ) {
				$apre['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $apre, 'Personne.qual' ) );
			}

            ///Pour l'adresse de la personne
			if( !empty( $apre['Adresse']['typevoie'] ) ) {
				$apre['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $apre, 'Adresse.typevoie' ) );
			}

            ///Pour l'adresse de la structure référente
			if( !empty( $apre['Structurereferente']['type_voie'] ) ) {
				$apre['Structurereferente']['type_voie'] = Set::classicExtract( $typevoie, Set::classicExtract( $apre, 'Structurereferente.type_voie' ) );
			}

            ///Paramètre nécessaire pour le bon choix du document à éditer
            $dest = Set::classicExtract( $this->params, 'named.dest' );


                ///Traduction des noms de table en libellés de l'aide
            foreach( $apre['Apre']['Natureaide'] as $i => $aides ){
                $apre['Apre']['Natureaide'][$i] = Set::enum( $aides, $natureAidesApres );
            }
            $apre['Apre']['Natureaide'] = '  - '.implode( "\n  - ", $apre['Apre']['Natureaide'] )."\n";

            ///Paramètre nécessaire pour connaitre le type de paiement au tiers (total/ plusieurs versements )
//             $typepaiement = array( 'Direct', 'Versement' ); //FIXME
            $typepaiement = 'Versement';
// debug($apre);
// debug(Set::classicExtract( $apre, 'ApreComiteapre.decisioncomite' ));

// debug($dest);
// debug($typedecision);
// debug($apre);
// die();
            if( ( $dest == 'beneficiaire' || $dest == 'referent' || $dest == 'tiers' ) && ( $typedecision == 'Refus' || $typedecision == 'Ajournement' ) ) {
                $this->Gedooo->generate( $apre, 'APRE/DecisionComite/Refus/Refus'.$dest.'.odt' );
            }
            else if( $dest == 'beneficiaire' && $typedecision == 'Accord' ) {
                $this->Gedooo->generate( $apre, 'APRE/DecisionComite/'.$typedecision.'/'.$typedecision.''.$typeformation.''.$dest.'.odt' );
            }
            else if( $dest == 'referent' && $typedecision == 'Accord' ) {
                $this->Gedooo->generate( $apre, 'APRE/DecisionComite/'.$typedecision.'/'.$typedecision.''.$dest.'.odt' );
            }
            else if( $dest == 'tiers' && !empty( $typedecision ) ) {
                $this->Gedooo->generate( $apre, 'APRE/DecisionComite/'.$typedecision.'/'.$typedecision.''.$typepaiement.''.$dest.'.odt' );
            }


        }
    }
?>