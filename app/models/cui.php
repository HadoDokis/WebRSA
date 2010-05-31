<?php
    class Cui extends AppModel
    {
        var $name = 'Cui';

        var $useTable = 'cuis';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'convention' => array( 'domain' => 'cui' ),
                    'secteur' => array( 'domain' => 'cui' ),
                    'statutemployeur' => array( 'domain' => 'cui' ),
                    'niveauformation' => array( 'domain' => 'cui' ),
                    'avenant' => array( 'domain' => 'cui' ),
                    'avenantcg' => array( 'domain' => 'cui' ),
                    'orgrecouvcotis' => array( 'domain' => 'cui' ),
                    'assurance' => array( 'domain' => 'cui' ),
                    'emploi' => array( 'domain' => 'cui' ),
                    'typecontratcui' => array( 'domain' => 'cui' ),
                    'initiative' => array( 'domain' => 'cui' ),
                    'formation' => array( 'domain' => 'cui' ),
                    'orgapayeur' => array( 'domain' => 'cui' ),
                    'isadresse2' => array( 'domain' => 'cui' ),
                    'atelierchantier' => array( 'domain' => 'cui' ),
                    'assurancechomage' => array( 'domain' => 'cui' ),
                    'iscie' => array( 'domain' => 'cui' ),
                    'dureesansemploi' => array( 'domain' => 'cui' ),
                    'isinscritpe' => array( 'domain' => 'cui' ),
                    'dureeinscritpe' => array( 'type' => 'dureesansemploi', 'domain' => 'cui' ),
                    'niveauemploi' => array( 'domain' => 'cui' ),
                    'ass' => array( 'domain' => 'cui' ),
                    'rsadept' => array( 'domain' => 'cui' ),
                    'rsadeptmaj' => array( 'domain' => 'cui' ),
                    'aah' => array( 'domain' => 'cui' ),
                    'ata' => array( 'domain' => 'cui' ),
                    'dureebenefaide' => array( 'type' => 'dureesansemploi', 'domain' => 'cui' ),
                    'isbeneficiaire' => array( 'domain' => 'cui' ),
                    'handicap' => array( 'domain' => 'cui' ),
                    'typecontrat' => array( 'domain' => 'cui' ),
                    'modulation' => array( 'domain' => 'cui' ),
                    'isaas' => array( 'domain' => 'cui' ),
                    'remobilisation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'aidereprise' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'elaboprojetpro' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'evaluation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'aiderechemploi' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'adaptation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'remiseniveau' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'prequalification' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'nouvellecompetence' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'formqualif' => array( 'domain' => 'cui', 'type' => 'initiative' ),
                    'isperiodepro' => array( 'domain' => 'cui' ),
                    'validacquis' => array( 'domain' => 'cui' ),
                    'iscae' => array( 'domain' => 'cui' ),

                )
            ),
            'Formattable'
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            ),
            'Referent' => array(
                'classname' => 'Referent',
                'foreignKey' => 'referent_id'
            )
        );


        var $validate = array(
            'convention' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'secteur' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'nomemployeur' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'codenaf2' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'identconvcollec' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'statutemployeur' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'effectifemployeur' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'siret' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'isUnique',
                    'message' => 'Ce numéro SIRET existe déjà'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Le numéro SIRET est composé de 14 chiffres'
                )
            ),
            'orgrecouvcotis' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'atelierchantier' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'assurancechomage' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
            'typevoieemployeur' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numvoieemployeur' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'nomvoieemployeur' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'codepostalemployeur' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'villeemployeur' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'numtelemployeur' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => array( 'between', 10, 14 ),
                    'message' => 'Le numéro de téléphone est composé de 10 chiffres'
                )
            ),
            'emailemployeur' => array(
                'rule' => 'email',
                'message' => 'Email non valide',
                'allowEmpty' => true
            ),
            'niveauformation' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'dureesansemploi' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'isisncritpe' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'isisncritpe' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'typecontrat' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )
        );

        // ********************************************************************

        var $queries = array(
            'criteresci' => array(
                'fields' => array(
                    '"Cui"."id"',
                    '"Cui"."personne_id"',
                    '"Cui"."referent_id"',
                    '"Cui"."structurereferente_id"',
                    '"Cui"."datecontrat"',
                    '"Cui"."datedebprisecharge"',
                    '"Cui"."datefinprisecharge"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Cui.personne_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
                )
            )
        );

        /**
        *   Précondition: La personne est-elle bien en Rsa Socle ?
        *   @default: false --> si Rsa Socle pas de msg d'erreur
        */

        function _prepare( $personne_id = null ) {

            $alerteRsaSocle = false;
            $personne = $this->Personne->findById( $personne_id, null, null, 0 );
            $dossier_rsa_id = Set::classicExtract( $personne, 'Foyer.dossier_rsa_id' );

            /// FIXME: on regarde le rsa socle sur les infos financieres OU BIEN sur les calculs droits rsa
            $infosfinancieres = $this->Personne->Foyer->Dossier->Infofinanciere->find(
                'all',
                array(
                    'conditions' => array(
                        'Infofinanciere.dossier_rsa_id' => $dossier_rsa_id
                    ),
                    'recursive' => -1,
                    'order' => 'Infofinanciere.moismoucompta DESC'
                )
            );

            $detaildroitrsa = $this->Personne->Foyer->Dossier->Detaildroitrsa->findByDossierRsaId( $dossier_rsa_id, null, null, -1 );
            $detaildroitrsa_id = Set::classicExtract( $detaildroitrsa, 'Detaildroitrsa.id' );
            $detailscalculsdroitrsa = $this->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->find(
                'all',
                array(
                    'conditions' => array(
                        'Detailcalculdroitrsa.detaildroitrsa_id' => $detaildroitrsa_id
                    ),
                    'recursive' => -1,
                    'order' => 'Detailcalculdroitrsa.dtderrsavers DESC'
                )
            );

            $rsaSocleValues = array();
            if( !empty( $infosfinancieres ) ) {
                $rsaSocleValues = array_unique( Set::extract( $infosfinancieres, '0/Infofinanciere/natpfcre' ) );
            }
            else if( !empty( $detailscalculsdroitrsa ) ) {
                $rsaSocleValues = array_unique( Set::extract( $detailscalculsdroitrsa, '0/Detailcalculdroitrsa/natpf' ) );
            }
            else {
                $alerteRsaSocle = true;
            }

            $rsaSocle = array( 'RSB', 'RSD', 'RSI', 'RSU' ); // valeurs possibles pour les RSA Socles
            if( array_intersects( $rsaSocleValues, array_keys( $rsaSocle ) ) ) {
                $alerteRsaSocle = false;
            }

            return $alerteRsaSocle;
        }


        /** ********************************************************************
        *   BeforeValidate
        *** *******************************************************************/
        function beforeValidate( $options = array() ) {
            $return = parent::beforeValidate( $options );

            foreach( array( 'iscie' ) as $key ) {
                if( isset( $this->data[$this->name][$key] ) ) {
                    $this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( '1' => 'O', '0' => 'N' ) );
                }
            }

            return $return;
        }

    }
?>
