<?php
    App::import( 'Sanitize' );
    class Relance extends AppModel
    {
        var $name = 'Relance';
        var $useTable = false;

        var $validate = array(
            'nbjours' => array(
                'rule' => 'numeric',
                'message' => 'Veuillez entrer un montant valide',
                'allowEmpty' => true
            ),
            'compare' => array(
                'rule' => 'mountComparator',
                'message' => 'Ce champ ne peut rester vide, si vous avez saisi un montant'
            )
        );



        function mountComparator($data) {
            $compare = Set::extract( $this->data, 'Relance.compare' );
            $nbjours = Set::extract( $this->data, 'Relance.nbjours' );
            return ( ( empty( $compare ) && empty( $nbjours ) ) || ( !empty( $compare ) && !empty( $nbjours ) ) );
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