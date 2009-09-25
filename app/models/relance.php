<?php
    App::import( 'Sanitize' );

    class Relance extends AppModel
    {
        var $name = 'Relance';
        var $useTable = false;

        var $validate = array(
            'compare' => array(
                array(
                    'rule' => array( 'allEmpty', 'nbjours' ),
                    'message' => 'Si nombre de jours depuis l\'orientation est renseigné, opérateurs doit l\'être aussi'
                )
            ),
            'nbjours' => array(
                array(
                    'rule' => array( 'allEmpty', 'compare' ),
                    'message' => 'Si opérateurs est renseigné, nombre de jours depuis l\'orientation doit l\'être aussi'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer un chiffre valide',
                    'allowEmpty' => true
                )
            )
        );

        var $_querydata = array(
            'gedooo' => array(
                'fields' => array(
                    '"Orientstruct"."id"',
                    '"Orientstruct"."personne_id"',
                    '"Orientstruct"."typeorient_id"',
                    '"Orientstruct"."structurereferente_id"',
                    '"Orientstruct"."date_valid"',
                    '"Orientstruct"."statut_orient"',
                    '"Orientstruct"."daterelance"',
                    '"Orientstruct"."statutrelance"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."numvoie"',
                    '"Adresse"."typevoie"',
                    '"Adresse"."nomvoie"',
                    '"Adresse"."compladr"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                    '"Structurereferente"."lib_struc"',
                    '"Structurereferente"."code_postal"',
                    '"Structurereferente"."num_voie"',
                    '"Structurereferente"."nom_voie"',
                    '"Structurereferente"."ville"',
                    '"Structurereferente"."type_voie"',
                    '"Serviceinstructeur"."lib_service"',
                    '"Serviceinstructeur"."num_rue"',
                    '"Serviceinstructeur"."type_voie"',
                    '"Serviceinstructeur"."nom_rue"',
                    '"Serviceinstructeur"."code_postal"',
                    '"Serviceinstructeur"."ville"',
                    '"User"."nom"',
                    '"User"."prenom"'
                ),
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
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
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Structurereferente.id = Orientstruct.structurereferente_id' )
                    )
                ),
                'recursive' => -1
            )
        );


        function mountComparator($data) {
            $compare = Set::extract( $this->data, 'Relance.compare' );
            $nbjours = Set::extract( $this->data, 'Relance.nbjours' );
            return ( ( empty( $compare ) && empty( $nbjours ) ) || ( !empty( $compare ) && empty( $nbjours ) ) );
        }


        function querydata( $type, $params = array() ) {
            $types = array_keys( $this->_querydata );
            if( !in_array( $type, $types ) ) {
                trigger_error( 'Invalid parameter "'.$type.'" for '.$this->name.'::prepare()', E_USER_WARNING );
            }
            else {
                $querydata = $this->_querydata[$type];
                $conditions = array(
                    'Orientstruct.statut_orient = \'Orienté\'',
                    'Orientstruct.statutrelance ILIKE \'R\''
                );

                $jetons = Set::extract( $params, 'Jetons.id' );
                if( !empty( $jetons ) ) {
                    $conditions['Dossier.id NOT IN'] = $jetons;
                }

                if( Set::extract( $params, 'User.filtre_zone_geo' ) ) {
                    $zonesgeographiques = Set::extract( $params, 'Zonegeographique' );
                    if( !empty( $zonesgeographiques ) ) {
                        $conditions['Adresse.numcomptt'] = $zonesgeographiques;
                    }
                    else {
                        $conditions[] = 'FALSE';
                    }
                }

                $serviceinstructeur_id = Set::extract( $params, 'User.serviceinstructeur_id' );
                if( !empty( $serviceinstructeur_id ) ) {
                    $querydata['joins'][] = array(
                        'table'      => 'servicesinstructeurs',
                        'alias'      => 'Serviceinstructeur',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Serviceinstructeur.id = '.$serviceinstructeur_id )
                    );
                }

                $user_id = Set::extract( $params, 'User.id' );
                if( !empty( $user_id ) ) {
                    $querydata['joins'][] = array(
                        'table'      => 'users',
                        'alias'      => 'User',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'User.id = '.$user_id )
                    );
                }

                $querydata['conditions'] = $conditions;

                return $querydata;
            }
        }


        function search( $statutRelance, $mesCodesInsee, $filtre_zone_geo, $criteresrelance, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array();

            if( !empty( $statutRelance ) ) {
                if( $statutRelance == 'Relance::arelancer' ) {
                    $conditions[] = 'Orientstruct.statut_orient = \'Orienté\'';
                    $conditions[] = 'Orientstruct.statutrelance NOT LIKE \'R\'';
                }
                else if( $statutRelance == 'Relance::relance' ){
                    $conditions[] = 'Orientstruct.statut_orient = \'Orienté\'';
                    $conditions[] = 'Orientstruct.statutrelance ILIKE \'R\'';
                }
            }

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères sur le RDV - date de demande
            if( isset( $criteresrelance['Relance']['daterelance'] ) && !empty( $criteresrelance['Relance']['daterelance'] ) ) {
                $valid_from = ( valid_int( $criteresrelance['Relance']['daterelance_from']['year'] ) && valid_int( $criteresrelance['Relance']['daterelance_from']['month'] ) && valid_int( $criteresrelance['Relance']['daterelance_from']['day'] ) );
                $valid_to = ( valid_int( $criteresrelance['Relance']['daterelance_to']['year'] ) && valid_int( $criteresrelance['Relance']['daterelance_to']['month'] ) && valid_int( $criteresrelance['Relance']['daterelance_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Orientstruct.daterelance BETWEEN \''.implode( '-', array( $criteresrelance['Relance']['daterelance_from']['year'], $criteresrelance['Relance']['daterelance_from']['month'], $criteresrelance['Relance']['daterelance_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresrelance['Relance']['daterelance_to']['year'], $criteresrelance['Relance']['daterelance_to']['month'], $criteresrelance['Relance']['daterelance_to']['day'] ) ).'\'';
                }
            }

            /// Critères
            $nbjours = Set::extract( $criteresrelance, 'Relance.nbjours' );
            $compare = Set::extract( $criteresrelance, 'Relance.compare' );

            /// Nb de jours depuis l'orientation
//             if( !empty( $nbjours ) && dateComplete( $criteresrelance, 'Relance.nbjours' ) ) {
//                 $conditions[] = '( DATE( NOW() ) - "Orientstruct"."date_valid" ) = \''.$nbjours.'\'';
//             }

            if( !empty( $compare ) && !empty( $nbjours ) ) {
                $conditions[] = '( DATE( NOW() ) - "Orientstruct"."date_valid" ) '.$compare.' '.Sanitize::clean( $nbjours );
            }
//             debug( $conditions );
            /// Requête
//             $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Orientstruct"."id"',
                    '"Orientstruct"."personne_id"',
                    '"Orientstruct"."typeorient_id"',
                    '"Orientstruct"."structurereferente_id"',
                    '"Orientstruct"."date_valid"',
                    '"Orientstruct"."statut_orient"',
                    '"Orientstruct"."daterelance"',
                    '"Orientstruct"."statutrelance"',
                    '( DATE( NOW() ) - "Orientstruct"."date_valid" ) AS "Orientstruct__nbjours"', // FIXME
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
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
                ),
                'limit' => 10,
                'conditions' => $conditions,
                'order' => 'Orientstruct.date_valid ASC'
            );

            return $query;
        }
    }
?>