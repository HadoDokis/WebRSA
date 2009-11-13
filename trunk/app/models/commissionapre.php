<?php
    App::import( 'Sanitize' );
    class Commissionapre extends AppModel {
        var $name = 'Commissionapre';
        var $useTable = false;

        function search( $statutCommissionapre, $mesCodesInsee, $filtre_zone_geo, $criteresapres, $lockedDossiers ) {


           /// Conditions de base
            $conditions = array( );

            if( !empty( $statutCommissionapre ) ) {
                if( $statutCommissionapre == 'Commissionapre::nouvelles' ) {

                }
                else if( $statutCommissionapre == 'Commissionapre::enattente' ) {

                }
                else if( $statutCommissionapre == 'Commissionapre::valide' ) {

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

            /// Critères
            $datedemandeapre = Set::extract( $criteresapres, 'Commissionapre.datedemandeapre' );
            $matricule = Set::extract( $criteresapres, 'Commissionapre.matricule' );
            $numcomptt = Set::extract( $criteresapres, 'Commissionapre.numcomptt' );

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresapres['Commissionapre'][$criterePersonne] ) && !empty( $criteresapres['Commissionapre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \'%'.replace_accents( $criteresapres['Commissionapre'][$criterePersonne] ).'%\'';
                }
            }

            // N° CAF
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule ILIKE \'%'.Sanitize::clean( $matricule ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            /// Critères sur l'adresse - canton
            if( Configure::read( 'CG.cantons' ) ) {
                if( isset( $criteresapres['Canton']['canton'] ) && !empty( $criteresapres['Canton']['canton'] ) ) {
                    $this->Canton =& ClassRegistry::init( 'Canton' );
                    $conditions[] = $this->Canton->queryConditions( $criteresapres['Canton']['canton'] );
                }
            }


            /// Critères sur les PDOs - date de décision
            if( isset( $criteresapres['Commissionapre']['datedemandeapre'] ) && !empty( $criteresapres['Commissionapre']['datedemandeapre'] ) ) {
                $valid_from = ( valid_int( $criteresapres['Commissionapre']['datedemandeapre_from']['year'] ) && valid_int( $criteresapres['Commissionapre']['datedemandeapre_from']['month'] ) && valid_int( $criteresapres['Commissionapre']['datedemandeapre_from']['day'] ) );
                $valid_to = ( valid_int( $criteresapres['Commissionapre']['datedemandeapre_to']['year'] ) && valid_int( $criteresapres['Commissionapre']['datedemandeapre_to']['month'] ) && valid_int( $criteresapres['Commissionapre']['datedemandeapre_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Propopdo.datedemandeapre BETWEEN \''.implode( '-', array( $criteresapres['Commissionapre']['datedemandeapre_from']['year'], $criteresapres['Commissionapre']['datedemandeapre_from']['month'], $criteresapres['Commissionapre']['datedemandeapre_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Commissionapre']['datedemandeapre_to']['year'], $criteresapres['Commissionapre']['datedemandeapre_to']['month'], $criteresapres['Commissionapre']['datedemandeapre_to']['day'] ) ).'\'';
                }
            }

            $query = array(
                'fields' => array(
                    '"Apre"."id"',
                    '"Apre"."personne_id"',
                    '"Apre"."referentapre_id"',
                    '"Apre"."numeroapre"',
                    '"Apre"."typedemandeapre"',
                    '"Apre"."datedemandeapre"',
                    '"Apre"."naturelogement"',
                    '"Apre"."typecontrat"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Dossier"."typeparte"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"'
                ),
                'joins' => array(

                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Apre.personne_id = Personne.id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
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
                            '( Prestation.rolepers = \'DEM\' )',
                        )
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
                'recursive' => -1,
                'conditions' => $conditions,
                'order' => array( '"Personne"."nom"' ),
//                 'limit' => $_limit
            );

            return $query;
        }
    }
?>