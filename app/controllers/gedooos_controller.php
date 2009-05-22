<?php
    App::import('Sanitize');

    class GedooosController extends AppController
    {
        var $name = 'Gedooos';
        var $uses = array( 'Contratinsertion', 'Adressefoyer' );

        function _ged( $datas, $model ) {
            // Définition des variables & maccros
            // FIXME: chemins
            define ('GEDOOO_WSDL',  'http://gedooo.services.adullact.org:8080/axis2/services/OfficeService?wsdl');
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

// $tmp = array();
            // Définition des variables pour les modèles de doc
            foreach( $datas as $group => $details ) {
                if( !empty( $details ) ) {
                    foreach( $details as $key => $value ) {
// $tmp[strtolower( $group ).'_'.strtolower( $key )] = $value;
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
// debug( $tmp );
// die();
            // fusion des documents
            $oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
            $oFusion->process();
            $oFusion->SendContentToClient();

            // Possibilité de récupérer la fusion dans un fichier
            // $oFusion->SendContentToFile($path.$nomFichier);
        }

        function notification_structure( $contratinsertion_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
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
            $this->_ged( $contratinsertion, 'notification_structure.odt' );
        }

        function contratinsertion( $contratinsertion_id = null ) {
            // TODO: error404/error500 si on ne trouve pas les données
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
            // FIXME
            $contratinsertion['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $contratinsertion['Personne']['dtnai'] ) );
// debug( $contratinsertion['Personne']['dtnai'] );
//             debug( $contratinsertion );
            $this->_ged( $contratinsertion, 'contratinsertion.odt' );
        }
    }
?>