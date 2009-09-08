<?php

    class Cohortepdo extends AppModel {
        var $name = 'Cohortepdo';
        var $useTable = false;

        function search( $statutValidationAvis, $mesCodesInsee, $filtre_zone_geo, $criterespdo, $lockedDossiers ) {
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

           /// Conditions de base
            $conditions = array( );

            if( !empty( $statutValidationAvis ) ) {
                if( $statutValidationAvis == 'Decisionpdo::nonvalide' ) {
                    $conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';
                    $conditions[] = 'Propopdo.decisionpdo ILIKE \'%P%\'';

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
            $typepdo = Set::extract( $criterespdo, 'Cohortepdo.typepdo' );
            $decisionpdo = Set::extract( $criterespdo, 'Cohortepdo.decisionpdo' );
            $datedecisionpdo = Set::extract( $criterespdo, 'Cohortepdo.datedecisionpdo' );


            // Type de PDO
            if( !empty( $typepdo ) ) {
                $conditions[] = 'Propopdo.typepdo ILIKE \'%'.Sanitize::clean( $typepdo ).'%\'';
            }

            // Décision CG
            if( !empty( $decisionpdo ) ) {
                $conditions[] = 'Propopdo.decisionpdo ILIKE \'%'.Sanitize::clean( $decisionpdo ).'%\'';
            }

            // Type de PDO
            if( !empty( $datedecisionpdo ) && dateComplete( $criterespdo, 'Cohortepdo.datedecisionpdo' ) ) {
                $datedecisionpdo = $datedecisionpdo['year'].'-'.$datedecisionpdo['month'].'-'.$datedecisionpdo['day'];
                $conditions[] = 'Propopdo.datedecisionpdo = \''.$datedecisionpdo.'\'';
            }
// debug( $conditions );
            $query = array(
                'fields' => array(
                    '"Propopdo"."id"',
                    '"Propopdo"."dossier_rsa_id"',
                    '"Propopdo"."typepdo"',
                    '"Propopdo"."decisionpdo"',
                    '"Propopdo"."datedecisionpdo"',
                    '"Propopdo"."commentairepdo"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
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
                    '"Adresse"."codepos"',
                    '"Situationdossierrsa"."etatdosrsa"',
                ),
                'joins' => array(
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.dossier_rsa_id = Dossier.id' )
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
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Situationdossierrsa.dossier_rsa_id = Dossier.id',
                            //'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' )' 
                        )
                    )
                ),
                'recursive' => -1,
                'conditions' => $conditions,
                'order' => array( '"Personne"."nom"' )
            );

            return $query;
        }
    }
?>