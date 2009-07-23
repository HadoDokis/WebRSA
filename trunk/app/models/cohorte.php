<?php
    App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Cohorte extends AppModel
    {
        var $name = 'Cohorte';
        var $useTable = false;

        function search( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit = PHP_INT_MAX ) {
            /// Conditions de base
            $conditions = array(
                'prestations.toppersdrodevorsa = true',
                'orientsstructs.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\''
            );

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'adresses.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'dossiers_rsa.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $oridemrsa = Set::extract( $criteres, 'Filtre.oridemrsa' );
            $locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
            $dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );

            // Origine de la demande
            if( !empty( $oridemrsa ) ) {
                $conditions[] = 'detailsdroitsrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'adresses.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // Localité adresse
            if( !empty( $dtdemrsa ) && $dtdemrsa != 0 ) {
                $dtdemrsa_from = Set::extract( $criteres, 'Filtre.dtdemrsa_from' );
                $dtdemrsa_to = Set::extract( $criteres, 'Filtre.dtdemrsa_to' );
                // FIXME: vérifier le bon formattage des dates
                $dtdemrsa_from = $dtdemrsa_from['year'].'-'.$dtdemrsa_from['month'].'-'.$dtdemrsa_from['day'];
                $dtdemrsa_to = $dtdemrsa_to['year'].'-'.$dtdemrsa_to['month'].'-'.$dtdemrsa_to['day'];

                $conditions[] = 'dossiers_rsa.dtdemrsa BETWEEN \''.$dtdemrsa_from.'\' AND \''.$dtdemrsa_to.'\'';
            }

            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );
            $sql = 'SELECT DISTINCT personnes.id
                    FROM personnes
                        INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = \'RSA\' AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' ) )
                        INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
                        INNER JOIN dossiers_rsa ON ( foyers.dossier_rsa_id = dossiers_rsa.id )
                        INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
                        INNER JOIN adresses ON ( adresses_foyers.adresse_id = adresses.id)
                        INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id )
                        LEFT OUTER JOIN suivisinstruction ON ( suivisinstruction.dossier_rsa_id = dossiers_rsa.id )
                        INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
                        INNER JOIN detailsdroitsrsa ON ( detailsdroitsrsa.dossier_rsa_id = dossiers_rsa.id )
                    WHERE '.implode( ' AND ', $conditions ).'
                    LIMIT '.$limit;
//                     WHERE
//                         prestations.toppersdrodevorsa = true
//                         AND orientsstructs.statut_orient = 'Non orienté'
//                         AND adresses.numcomptt = '93066'
//                         AND dossiers_rsa.dtdemrsa = '2009-06-01'
            $cohorte = $this->Dossier->query( $sql );

            return Set::extract( $cohorte, '{n}.0.id' );
        }
    }
?>