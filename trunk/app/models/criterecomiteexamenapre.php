<?php
    App::import( 'Sanitize' );

    // ************************************************************************

    class Criterecomiteexamenapre extends AppModel
    {
        var $name = 'Criterecomiteexamenapre';
        var $useTable = false;

        function search( $mesCodesInsee, $filtre_zone_geo, $criterescomite ) {
            /// Conditions de base
            $conditions = array(
            );

            /// Critères
            $conditions[] = 'Comiteexamenapre.id IS NOT NULL';

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Critères sur le Comité - date du comité
            if( isset( $criterescomite['Criterecomiteexamenapre']['datecomite'] ) && !empty( $criterescomite['Criterecomiteexamenapre']['datecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Criterecomiteexamenapre']['datecomite_from']['year'] ) && valid_int( $criterescomite['Criterecomiteexamenapre']['datecomite_from']['month'] ) && valid_int( $criterescomite['Criterecomiteexamenapre']['datecomite_from']['day'] ) );
                $valid_to = ( valid_int( $criterescomite['Criterecomiteexamenapre']['datecomite_to']['year'] ) && valid_int( $criterescomite['Criterecomiteexamenapre']['datecomite_to']['month'] ) && valid_int( $criterescomite['Criterecomiteexamenapre']['datecomite_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteexamenapre.datecomite BETWEEN \''.implode( '-', array( $criterescomite['Criterecomiteexamenapre']['datecomite_from']['year'], $criterescomite['Criterecomiteexamenapre']['datecomite_from']['month'], $criterescomite['Criterecomiteexamenapre']['datecomite_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescomite['Criterecomiteexamenapre']['datecomite_to']['year'], $criterescomite['Criterecomiteexamenapre']['datecomite_to']['month'], $criterescomite['Criterecomiteexamenapre']['datecomite_to']['day'] ) ).'\'';
                }
            }

            /// Critères sur le Comité - heure du comité
            if( isset( $criterescomite['Criterecomiteexamenapre']['heurecomite'] ) && !empty( $criterescomite['Criterecomiteexamenapre']['heurecomite'] ) ) {
                $valid_from = ( valid_int( $criterescomite['Criterecomiteexamenapre']['heurecomite_from']['hour'] ) && valid_int( $criterescomite['Criterecomiteexamenapre']['heurecomite_from']['min'] ) );
                $valid_to = ( valid_int( $criterescomite['Criterecomiteexamenapre']['heurecomite_to']['hour'] ) && valid_int( $criterescomite['Criterecomiteexamenapre']['heurecomite_to']['min'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Comiteexamenapre.heurecomite BETWEEN \''.implode( ':', array( $criterescomite['Criterecomiteexamenapre']['heurecomite_from']['hour'], $criterescomite['Criterecomiteexamenapre']['heurecomite_from']['min'] ) ).'\' AND \''.implode( ':', array( $criterescomite['Criterecomiteexamenapre']['heurecomite_to']['hour'], $criterescomite['Criterecomiteexamenapre']['heurecomite_to']['min'] ) ).'\'';
                }
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Comiteexamenapre"."id"',
                    '"Comiteexamenapre"."datecomite"',
                    '"Comiteexamenapre"."heurecomite"',
                    '"Comiteexamenapre"."lieucomite"',
                    '"Comiteexamenapre"."intitulecomite"',
                    '"Comiteexamenapre"."observationcomite"'
                ),
                'recursive' => -1,
                'joins' => array(
//                     array(
//                         'table'      => 'apres',
//                         'alias'      => 'Apre',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Apre.comiteexamenapre_id = Comiteexamenapre.id' ),
//                     ),
//                     array(
//                         'table'      => 'personnes',
//                         'alias'      => 'Personne',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Personne.id = Apre.personne_id' ),
//                     ),
//                     array(
//                         'table'      => 'prestations',
//                         'alias'      => 'Prestation',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array(
//                             'Personne.id = Prestation.personne_id',
//                             'Prestation.natprest = \'RSA\'',
// //                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
//                             '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
//                         )
//                     ),
//                     array(
//                         'table'      => 'foyers',
//                         'alias'      => 'Foyer',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Personne.foyer_id = Foyer.id' )
//                     ),
//                     array(
//                         'table'      => 'adresses_foyers',
//                         'alias'      => 'Adressefoyer',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
//                     ),
//                     array(
//                         'table'      => 'adresses',
//                         'alias'      => 'Adresse',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
//                     ),
//                     array(
//                         'table'      => 'dossiers_rsa',
//                         'alias'      => 'Dossier',
//                         'type'       => 'INNER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
//                     )
                ),
                'order' => array( '"Comiteexamenapre"."datecomite" ASC' ),
                'conditions' => $conditions
            );

            return $query;

        }
    }
?>