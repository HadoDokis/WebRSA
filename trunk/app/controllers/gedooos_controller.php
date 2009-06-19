<?php
    App::import('Sanitize');

    class GedooosController extends AppController
    {
        var $name = 'Gedooos';
        var $uses = array( 'Cohorte', 'Contratinsertion', 'Adressefoyer', 'Orientstruct', 'Structurereferente', 'Dossier', 'Option' );


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

            $this->set( 'decision_ci', $this->Option->decision_ci() );
            $sect = $this->Option->sect_acti_emp();
            $this->set( 'sect_acti_emp', $sect );
            $emp_occupe = $this->Option->emp_occupe();
            $this->set( 'emp_occupe', $emp_occupe );
            $duree_hebdo = $this->Option->duree_hebdo_emp();
            $this->set( 'duree_hebdo_emp', $duree_hebdo );
            $nature = $this->Option->nat_cont_trav();
            $this->set( 'nat_cont_trav', $nature );
            $duree_cdd = $this->Option->duree_cdd();
            $this->set( 'duree_cdd', $duree_cdd );


            $contratinsertion = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.id' => $contratinsertion_id
                    )
                )
            );

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

            $contratinsertion['Contratinsertion']['emp_occupe'] = $emp_occupe[$contratinsertion['Contratinsertion']['emp_occupe']];
            $contratinsertion['Contratinsertion']['duree_hebdo_emp'] = $duree_hebdo[$contratinsertion['Contratinsertion']['duree_hebdo_emp']];
            $contratinsertion['Contratinsertion']['nat_cont_trav'] = $nature[$contratinsertion['Contratinsertion']['nat_cont_trav']];
            $contratinsertion['Contratinsertion']['duree_cdd'] = $duree_cdd[$contratinsertion['Contratinsertion']['duree_cdd']];
            $contratinsertion['Contratinsertion']['sect_acti_emp'] = $sect[$contratinsertion['Contratinsertion']['sect_acti_emp']];

            $contratinsertion['Contratinsertion']['datevalidation_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) );
            $contratinsertion['Contratinsertion']['date_saisi_ci'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Contratinsertion']['date_saisi_ci'] ) );
            // FIXME
            $contratinsertion['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Personne']['dtnai'] ) );

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
                        'Orientstruct.id' => $personne['Orientstruct']['id']
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
            $sModele = $phpGedooDir.'/../test_section.odt';

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
            $oTemplate = new GDO_ContentType("",
                            "modele.ott",
                            $u->getMimeType($sModele),
                            "binary",
                            $bTemplate);

            $oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
            $oFusion->process();
            $oFusion->SendContentToClient();
        }
    }
?>