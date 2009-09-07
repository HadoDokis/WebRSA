<?php

    class Cohortepdo extends AppModel {
        var $name = 'Cohortepdo';
        var $useTable = false;

        function search( $statutAvis, $mesCodesInsee, $filtre_zone_geo, $criterespdo, $lockedDossiers ) {
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

           /// Conditions de base
            $conditions = array(
                'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) '
            );
            if( !empty( $statutAvis ) ) {
                $conditions[] = 'Derogation.avisdero = \''.Sanitize::clean( $statutAvis ).'\'';
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
            $typedero = Set::extract( $criterespdo, 'Cohortepdo.typedero' );
            $avisdero = Set::extract( $criterespdo, 'Cohortepdo.avisdero' );
            $ddavisdero = Set::extract( $criterespdo, 'Cohortepdo.ddavisdero' );


            // Type de PDO
            if( !empty( $typedero ) ) {
                $conditions[] = 'Derogation.typedero ILIKE \'%'.Sanitize::clean( $typedero ).'%\'';
            }

            // Décision CG
            if( !empty( $avisdero ) ) {
                $conditions[] = 'Derogation.avisdero ILIKE \'%'.Sanitize::clean( $avisdero ).'%\'';
            }

            // Type de PDO
            if( !empty( $ddavisdero ) && dateComplete( $criterespdo, 'Cohortepdo.ddavisdero' ) ) {
                $ddavisdero = $ddavisdero['year'].'-'.$ddavisdero['month'].'-'.$ddavisdero['day'];
                $conditions[] = 'Derogation.ddavisdero = \''.$ddavisdero.'\'';
            }

            $query = array(
                'fields' => array(
                    '"Derogation"."id"',
                    '"Derogation"."avispcgpersonne_id"',
                    '"Derogation"."typedero"',
                    '"Derogation"."avisdero"',
                    '"Derogation"."ddavisdero"',
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
                        'table'      => 'avispcgpersonnes',
                        'alias'      => 'Avispcgpersonne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Avispcgpersonne.id = Derogation.avispcgpersonne_id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Avispcgpersonne.personne_id' )
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
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Situationdossierrsa.dossier_rsa_id = Dossier.id'
                        )
                    ),
                ),
                'recursive' => -1,
                'conditions' => $conditions,
                'order' => array( '"Personne"."nom"' )
            );

            return $query;
        }
    }
?>