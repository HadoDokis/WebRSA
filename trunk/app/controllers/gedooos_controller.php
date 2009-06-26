<?php
    @set_time_limit( 0 );
    @ini_set( 'memory_limit', '128M' );

    App::import('Sanitize');

    class GedooosController extends AppController
    {
        var $name = 'Gedooos';
        var $uses = array( 'Cohorte', 'Contratinsertion', 'Typocontrat', 'Adressefoyer', 'Orientstruct', 'Structurereferente', 'Dossier', 'Option', 'Dspp', 'Detaildroitrsa' );


        function _ged( $datas, $model ) {
            // Définition des variables & maccros
            // FIXME: chemins
            $phpGedooDir = dirname( __FILE__ ).'/../vendors/phpgedooo';
            $sMimeType  = "application/pdf";
            $path_model = $phpGedooDir.'/../'.$model;

            // Inclusion des fichiers nécessaires à GEDOOo
            // FIXME
            $phpGedooDir = dirname( __FILE__ ).'/../vendors/phpgedooo';
            require_once( $phpGedooDir.DS.'GDO_Utility.class' );
            require_once( $phpGedooDir.DS.'GDO_FieldType.class' );
            require_once( $phpGedooDir.DS.'GDO_ContentType.class' );
            require_once( $phpGedooDir.DS.'GDO_IterationType.class' );
            require_once( $phpGedooDir.DS.'GDO_PartType.class' );
            require_once( $phpGedooDir.DS.'GDO_FusionType.class' );
            require_once( $phpGedooDir.DS.'GDO_MatrixType.class' );
            require_once( $phpGedooDir.DS.'GDO_MatrixRowType.class' );
            require_once( $phpGedooDir.DS.'GDO_AxisTitleType.class' );

            //initialisation des objets
            $util      = new GDO_Utility();
            $oTemplate = new GDO_ContentType(
                '',
                basename( $path_model ),
                $util->getMimeType( $path_model ),
                'binary',
                $util->ReadFile( $path_model )
            );
            $oMainPart = new GDO_PartType();

            // Définition des variables pour les modèles de doc
            foreach( $datas as $group => $details ) {
                if( !empty( $details ) ) {
                    foreach( $details as $key => $value ) {
// if( is_array( $value ) ) {
//     debug( $key );
//     debug( $value );
// }
                        $oMainPart->addElement(
                            new GDO_FieldType(
                                strtolower( $group ).'_'.strtolower( $key ),
                                $value,
                                'text'
                            )
                        );
                    }
                }
            }

            // fusion des documents
            $oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
            $oFusion->process();
            $oFusion->SendContentToClient();

            // Possibilité de récupérer la fusion dans un fichier
            // $oFusion->SendContentToFile($path.$nomFichier);
        }

        function notification_structure( $personne_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    )
                )
            );

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

            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $personne['User'] = $user['User'];

            // Récupération de la structure referente liée à la personne
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.id' => $personne['Orientstruct']['id']
                    )
                )
            );
            $personne['Orientstruct'] = $orientstruct['Orientstruct'];
            $personne['Structurereferente'] = $orientstruct['Structurereferente'];

            $this->_ged( $personne, 'notification_structure.odt' );
        }

        function contratinsertion( $contratinsertion_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $tc = $this->Typocontrat->find(
                'list',
                array(
                    'fields' => array(
                        'Typocontrat.lib_typo'
                    ),
                )
            );
            $this->set( 'tc', $tc );
            $sect_acti_emp = $this->Option->sect_acti_emp();
            $this->set( 'sect_acti_emp', $sect_acti_emp );
            $emp_occupe = $this->Option->emp_occupe();
            $this->set( 'emp_occupe', $emp_occupe );
            $duree_hebdo_emp = $this->Option->duree_hebdo_emp();
            $this->set( 'duree_hebdo_emp', $duree_hebdo_emp );
            $nat_cont_trav = $this->Option->nat_cont_trav();
            $this->set( 'nat_cont_trav', $nat_cont_trav );
            $duree_cdd = $this->Option->duree_cdd();
            $this->set( 'duree_cdd', $duree_cdd );
            $decision_ci = $this->Option->decision_ci();
            $this->set( 'decision_ci', $decision_ci );

            $sitfam = $this->Option->sitfam();
            $this->set( 'sitfam', $sitfam );
            $typeocclog = $this->Option->typeocclog();
            $this->set( 'typeocclog', $typeocclog );
            $oridemrsa = $this->Option->oridemrsa();
            $this->set( 'oridemrsa', $oridemrsa );



            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            );
            //////////////////////////////////////////////////////////////////////////
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
                        'Adressefoyer.foyer_id' => $contratinsertion['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );

            unset( $contratinsertion['Actioninsertion'] );
            $contratinsertion['Adresse'] = $adresse['Adresse'];

            //////////////////////////////////////////////////////////////////////////
            $foyer = $this->Foyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Foyer.id' => $contratinsertion['Personne']['foyer_id']
                    )
                )
            );
            $contratinsertion['Foyer'] = $foyer['Foyer'];
            //////////////////////////////////////////////////////////////////////////
            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $contratinsertion['Foyer']['dossier_rsa_id']
                    )
                )
            );
            $contratinsertion['Foyer']['dossier_rsa_id'] = $dossier['Dossier']['id'];
            //////////////////////////////////////////////////////////////////////////
            $dspp = $this->Dspp->find(
                'first',
                array(
                    'conditions' => array(
                        'Dspp.personne_id' => $contratinsertion['Personne']['id']
                    )
                )
            );
            $contratinsertion['Dspp']['personne_id'] = $dspp['Personne']['id'];
            //////////////////////////////////////////////////////////////////////////
            $ddrsa = $this->Detaildroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Detaildroitrsa.dossier_rsa_id' => $dossier['Dossier']['id']
                    )
                )
            );
            $dossier['Dossier']['id'] = $ddrsa['Detaildroitrsa']['dossier_rsa_id'];
//             debug($ddrsa['Detaildroitrsa']);
            //////////////////////////////////////////////////////////////////////////
            // Affichage des données réelles et non leurs variables
            foreach( array( 'tc', 'emp_occupe', 'duree_hebdo_emp', 'nat_cont_trav', 'duree_cdd', 'sect_acti_emp' ) as $varName ) {
                $contratinsertion['Contratinsertion'][$varName] = ( isset( $contratinsertion['Contratinsertion'][$varName] ) ? ${$varName}[$contratinsertion['Contratinsertion'][$varName]] : null );
            }

            $contratinsertion['Contratinsertion']['datevalidation_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) );
            $contratinsertion['Contratinsertion']['date_saisi_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['date_saisi_ci'] ) );
            $contratinsertion['Contratinsertion']['typocontrat_id'] = $tc[$contratinsertion['Contratinsertion']['typocontrat_id']];
            $contratinsertion['Contratinsertion']['actions_prev'] = ( $contratinsertion['Contratinsertion']['actions_prev']  ? 'Oui' : 'Non' );
            $contratinsertion['Contratinsertion']['emp_trouv'] = ( $contratinsertion['Contratinsertion']['emp_trouv']  ? 'Oui' : 'Non' );

            // Affichage de la date seulement en cas de " Validation à compter de "
            if( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' ){
                $contratinsertion['Contratinsertion']['decision_ci'] = $decision_ci[$contratinsertion['Contratinsertion']['decision_ci']].' '.$contratinsertion['Contratinsertion']['datevalidation_ci'];
            }
            else{
                $contratinsertion['Contratinsertion']['decision_ci'] = $decision_ci[$contratinsertion['Contratinsertion']['decision_ci']];
            }

            // Données Personne récupérées
            $contratinsertion['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Personne']['dtnai'] ) );

            // Données Foyer récupérées
            $contratinsertion['Foyer']['sitfam'] = ( isset( $sitfam[$foyer['Foyer']['sitfam']] ) ? $sitfam[$foyer['Foyer']['sitfam']] : null );
            $contratinsertion['Foyer']['typeocclog'] = ( isset( $typeocclog[$foyer['Foyer']['typeocclog']] ) ? $typeocclog[$foyer['Foyer']['typeocclog']] : null );

            // Données Dossier récupérées
            $contratinsertion['Dossier']['matricule'] = ( isset( $dossier['Dossier']['matricule'] ) ? $dossier['Dossier']['matricule'] : null );
            $contratinsertion['Dossier']['dtdemrsa'] = strftime( '%d/%m/%Y', strtotime( $dossier['Dossier']['dtdemrsa'] ) );

            $contratinsertion['Detaildroitrsa']['oridemrsa'] = isset( $oridemrsa[$ddrsa['Detaildroitrsa']['oridemrsa']] ) ? $oridemrsa[$ddrsa['Detaildroitrsa']['oridemrsa']] : null ;

            // Données Dspp récupérées
            $contratinsertion['Dspp']['couvsoc'] = ( isset( $dspp['Dspp']['couvsoc'] ) ? 'Oui' : 'Non' );


            $this->_ged( $contratinsertion, 'contratinsertion.odt' );
        }

        function orientstruct( $orientstruct_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données

            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.id' => $orientstruct_id
                    )
                )
            );

            $typeorient = $this->Structurereferente->Typeorient->find(
                'first',
                array(
                    'conditions' => array(
                        'Typeorient.id' => $orientstruct['Orientstruct']['typeorient_id'] // FIXME structurereferente_id
                    )
                )
            );
            // FIXME: seulement pour le cg66 ?
            $modele = $typeorient['Typeorient']['modele_notif'];

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
                        'Adressefoyer.foyer_id' => $orientstruct['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $orientstruct['Adresse'] = $adresse['Adresse'];


            $dossier = $this->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $orientstruct['Personne']['foyer_id']
                    )
                )
            );
            $orientstruct['Dossier_RSA'] = $dossier['Dossier'];

            // FIXME

            $orientstruct['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $orientstruct['Personne']['dtnai'] ) );

            $this->_ged( $orientstruct, 'cg66/'.$modele.'.odt' );
        }

        function _get( $personne_id ) {
            $personne = $this->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    )
                )
            );

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

            // Récupération de l'utilisateur
            $user = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->Session->read( 'Auth.User.id' )
                    )
                )
            );
            $personne['User'] = $user['User'];

            // Récupération de la structure referente liée à la personne
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        // 'Orientstruct.id' => $personne['Orientstruct']['id']
                        'Orientstruct.personne_id' => $personne['Personne']['id'] // FIXME
                    )
                )
            );
            $personne['Orientstruct'] = $orientstruct['Orientstruct'];
            $personne['Structurereferente'] = $orientstruct['Structurereferente'];

            return $personne;
        }

        /**
        *
        *
        *
        */

        function notifications_cohortes() {
            $cohorte = $this->Cohorte->search( 'Orienté', array_values( $this->Session->read( 'Auth.Zonegeographique' ) ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );

            // Définition des variables & maccros
            // FIXME: chemins
            $phpGedooDir = dirname( __FILE__ ).'/../vendors/phpgedooo'; // FIXME: chemin
            $sMimeType  = "application/pdf";
            $sModele = $phpGedooDir.'/../notifications_cohorte.odt';

            // Inclusion des fichiers nécessaires à GEDOOo
            // FIXME
            $phpGedooDir = dirname( __FILE__ ).'/../vendors/phpgedooo';
            require_once( $phpGedooDir.DS.'GDO_Utility.class' );
            require_once( $phpGedooDir.DS.'GDO_FieldType.class' );
            require_once( $phpGedooDir.DS.'GDO_ContentType.class' );
            require_once( $phpGedooDir.DS.'GDO_IterationType.class' );
            require_once( $phpGedooDir.DS.'GDO_PartType.class' );
            require_once( $phpGedooDir.DS.'GDO_FusionType.class' );
            require_once( $phpGedooDir.DS.'GDO_MatrixType.class' );
            require_once( $phpGedooDir.DS.'GDO_MatrixRowType.class' );
            require_once( $phpGedooDir.DS.'GDO_AxisTitleType.class' );

            //
            // Organisation des données
            //
            $u = new GDO_Utility();

            $oMainPart = new GDO_PartType();

            $oIteration = new GDO_IterationType( "notification" );

            foreach( $cohorte as $personne_id ) {
                $oDevPart = new GDO_PartType();

                $datas = $this->_get( $personne_id );
                foreach( $datas as $group => $details ) {
                    if( !empty( $details ) ) {
                        foreach( $details as $key => $value ) {
                            $oDevPart->addElement(
                                new GDO_FieldType(
                                    strtolower( $group ).'_'.strtolower( $key ),
                                    $value,
                                    'text'
                                )
                            );
                        }
                    }
                }

                $oIteration->addPart($oDevPart);
            }
            $oMainPart->addElement($oIteration);

            $bTemplate = $u->ReadFile($sModele);
            $oTemplate = new GDO_ContentType(
                "",
                "modele.ott",
                $u->getMimeType($sModele),
                "binary",
                $bTemplate
            );

            $oFusion = new GDO_FusionType( $oTemplate, $sMimeType, $oMainPart );
            $oFusion->process();
            $oFusion->SendContentToClient();
        }
    }
?>