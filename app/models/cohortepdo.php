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
                    $conditions[] = 'Situationdossierrsa.dossier_rsa_id NOT IN ( SELECT propospdos.dossier_rsa_id FROM propospdos /*WHERE propospdos.decisionpdo = \'P\'*/ )';
                }
                else if( $statutValidationAvis == 'Decisionpdo::valide' ) {
                    $conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';
                    $conditions[] = 'Propopdo.decisionpdo IS NOT NULL';
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
            $motifpdo = Set::extract( $criterespdo, 'Cohortepdo.motifpdo' );
            $datedecisionpdo = Set::extract( $criterespdo, 'Cohortepdo.datedecisionpdo' );
            $matricule = Set::extract( $criterespdo, 'Cohortepdo.matricule' );


            // Type de PDO
            if( !empty( $typepdo ) ) {
                $conditions[] = 'Propopdo.typepdo ILIKE \'%'.Sanitize::clean( $typepdo ).'%\'';
            }

            // Motif de la PDO
            if( !empty( $motifpdo ) ) {
                $conditions[] = 'Propopdo.motifpdo ILIKE \'%'.Sanitize::clean( $motifpdo ).'%\'';
            }

            // N° CAF
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule ILIKE \'%'.Sanitize::clean( $matricule ).'%\'';
            }

            // Décision CG
            if( !empty( $decisionpdo ) ) {
                $conditions[] = 'Propopdo.decisionpdo ILIKE \'%'.Sanitize::clean( $decisionpdo ).'%\'';
            }

            /// Critères sur les PDOs - date de décision
            if( isset( $criterespdo['Cohortepdo']['datedecisionpdo'] ) && !empty( $criterespdo['Cohortepdo']['datedecisionpdo'] ) ) {
                $valid_from = ( valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['day'] ) );
                $valid_to = ( valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Propopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdo['Cohortepdo']['datedecisionpdo_from']['year'], $criterespdo['Cohortepdo']['datedecisionpdo_from']['month'], $criterespdo['Cohortepdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdo['Cohortepdo']['datedecisionpdo_to']['year'], $criterespdo['Cohortepdo']['datedecisionpdo_to']['month'], $criterespdo['Cohortepdo']['datedecisionpdo_to']['day'] ) ).'\'';
                }
            }
            // Type de PDO
//             if( !empty( $datedecisionpdo ) && dateComplete( $criterespdo, 'Cohortepdo.datedecisionpdo' ) ) {
//                 $datedecisionpdo = $datedecisionpdo['year'].'-'.$datedecisionpdo['month'].'-'.$datedecisionpdo['day'];
//                 $conditions[] = 'Propopdo.datedecisionpdo = \''.$datedecisionpdo.'\'';
//             }
// debug( $conditions );
            $query = array(
                'fields' => array(
                    '"Propopdo"."id"',
                    '"Propopdo"."dossier_rsa_id"',
                    '"Propopdo"."typepdo"',
                    '"Propopdo"."decisionpdo"',
                    '"Propopdo"."datedecisionpdo"',
                    '"Propopdo"."motifpdo"',
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
                        'table'      => 'propospdos',
                        'alias'      => 'Propopdo',
                        'type'       => 'LEFT OUTER',
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
                'order' => array( '"Personne"."nom"' ),
//                 'limit' => $_limit
            );

            return $query;
        }
    }
?>