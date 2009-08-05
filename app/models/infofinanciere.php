<?php
    class Infofinanciere extends AppModel
    {
        var $name = 'Infofinanciere';
        var $useTable = 'infosfinancieres';


        var $hasMany = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'id'
            )
        );


        var $validate = array(
            'type_allocation' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'natpfcre' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeopecompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'sensopecompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dttraimoucompta' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'mtmoucompta' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
            ),
            'mtmoucompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),

            /*'ddregu' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),

            'heutraimoucompta' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            )*/
        );

        function search( $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array();

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $mois = Set::extract( $criteres, 'Filtre.moismoucompta' );

            // ...
            if( !empty( $mois ) && dateComplete( $criteres, 'Filtre.moismoucompta' ) ) {
                $mois = $mois['month'];
                $conditions[] = 'EXTRACT(MONTH FROM Infofinanciere.moismoucompta) = '.$mois;
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Infofinanciere"."id"',
                    '"Infofinanciere"."dossier_rsa_id"',
                    '"Infofinanciere"."moismoucompta"',
                    '"Infofinanciere"."type_allocation"',
                    '"Infofinanciere"."natpfcre"',
                    '"Infofinanciere"."rgcre"',
                    '"Infofinanciere"."numintmoucompta"',
                    '"Infofinanciere"."typeopecompta"',
                    '"Infofinanciere"."sensopecompta"',
                    '"Infofinanciere"."mtmoucompta"',
                    '"Infofinanciere"."ddregu"',
                    '"Infofinanciere"."dttraimoucompta"',
                    '"Infofinanciere"."heutraimoucompta"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."matricule"',
                    '"Dossier"."typeparte"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."nir"',
                    '"Personne"."dtnai"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
//                     '"Adresse"."locaadr"',
//                     '"Adresse"."codepos"',
                    '"Situationdossierrsa"."etatdosrsa"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Infofinanciere.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            '( Prestation.rolepers = \'DEM\' )',
                        )
                    )/*,
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
                    )*/
                ),
                'limit' => 10,
                'conditions' => $conditions
            );


            return $query;

        }
    }
?>
