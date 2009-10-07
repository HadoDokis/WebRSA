<?php
    class Cohorteci extends AppModel
    {
        var $name = 'Cohorteci';
        var $useTable = false;

        function search( $statutValidation, $mesCodesInsee, $filtre_zone_geo, $criteresci, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array(/* '1 = 1' */);


            if( !empty( $statutValidation ) ) {
                if( $statutValidation == 'Decisionci::nonvalide' ) {
                    $conditions[] = '( ( Contratinsertion.decision_ci <> \'V\' ) AND ( Contratinsertion.decision_ci <> \'E\' ) ) OR ( Contratinsertion.decision_ci IS NULL )';
                }
                else if( $statutValidation == 'Decisionci::enattente' ) {
                    $conditions[] = 'Contratinsertion.decision_ci = \'E\'';
                }
                else if( $statutValidation == 'Decisionci::valides' ) {
                    $conditions[] = 'Contratinsertion.decision_ci IS NOT NULL';
                    $conditions[] = 'Contratinsertion.decision_ci = \'V\'';
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
            $date_saisi_ci = Set::extract( $criteresci, 'Filtre.date_saisi_ci' );
            $decision_ci = Set::extract( $criteresci, 'Filtre.decision_ci' );
            $datevalidation_ci = Set::extract( $criteresci, 'Filtre.datevalidation_ci' );
            $locaadr = Set::extract( $criteresci, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteresci, 'Filtre.numcomptt' );
            $personne_suivi = Set::extract( $criteresci, 'Filtre.pers_charg_suivi' );
            $forme_ci = Set::extract( $criteresci, 'Filtre.forme_ci' );


            /// Critères sur le CI - date de saisi contrat
            if( isset( $criteresci['Filtre']['date_saisi_ci'] ) && !empty( $criteresci['Filtre']['date_saisi_ci'] ) ) {
                $valid_from = ( valid_int( $criteresci['Filtre']['date_saisi_ci_from']['year'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_from']['month'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_from']['day'] ) );
                $valid_to = ( valid_int( $criteresci['Filtre']['date_saisi_ci_to']['year'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_to']['month'] ) && valid_int( $criteresci['Filtre']['date_saisi_ci_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Contratinsertion.date_saisi_ci BETWEEN \''.implode( '-', array( $criteresci['Filtre']['date_saisi_ci_from']['year'], $criteresci['Filtre']['date_saisi_ci_from']['month'], $criteresci['Filtre']['date_saisi_ci_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresci['Filtre']['date_saisi_ci_to']['year'], $criteresci['Filtre']['date_saisi_ci_to']['month'], $criteresci['Filtre']['date_saisi_ci_to']['day'] ) ).'\'';
                }
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresci['Filtre'][$criterePersonne] ) && !empty( $criteresci['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \'%'.$criteresci['Filtre'][$criterePersonne].'%\'';
                }
            }

            // ...
            if( !empty( $decision_ci ) ) {
                $conditions[] = 'Contratinsertion.decision_ci = \''.Sanitize::clean( $decision_ci ).'\'';
            }

            // ...
            if( !empty( $datevalidation_ci ) && dateComplete( $criteresci, 'Filtre.datevalidation_ci' ) ) {
                $datevalidation_ci = $datevalidation_ci['year'].'-'.$datevalidation_ci['month'].'-'.$datevalidation_ci['day'];
                $conditions[] = 'Contratinsertion.datevalidation_ci = \''.$datevalidation_ci.'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // Personne chargée du suiv
            if( !empty( $personne_suivi ) ) {
                $conditions[] = 'Contratinsertion.pers_charg_suivi = \''.Sanitize::clean( $personne_suivi ).'\'';
            }

            // Forme du contrat
            if( !empty( $forme_ci ) ) {
                $conditions[] = 'Contratinsertion.forme_ci = \''.Sanitize::clean( $forme_ci ).'\'';
            }
//             //Critère recherche par Contrat insertion: par service instructeur
//             if( isset( $params['Cohorteci']['serviceinstructeur_id'] ) && !empty( $params['Cohorteci']['serviceinstructeur_id'] ) ){
//                 $conditions['Serviceinstructeur.id'] = $params['Cohorteci']['serviceinstructeur_id'];
//             }

/**
SELECT DISTINCT contratsinsertion.id
    FROM contratsinsertion
        INNER JOIN personnes ON ( personnes.id = contratsinsertion.personne_id )
        INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = 'RSA' AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' ) )
        INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
        INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = '01' )
        INNER JOIN adresses ON ( adresses_foyers.adresse_id = adresses.id)
    WHERE
        contratsinsertion.date_saisi_ci = '2009-01-01'
        AND contratsinsertion.decision_ci = 'E'
        AND contratsinsertion.datevalidation_ci = '2009-01-01'
        AND adresses.locaadr ILIKE '%denis%'
*/

            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Contratinsertion"."id"',
                    '"Contratinsertion"."personne_id"',
                    '"Contratinsertion"."typocontrat_id"',
                    '"Contratinsertion"."structurereferente_id"',
                    '"Contratinsertion"."rg_ci"',
                    '"Contratinsertion"."decision_ci"',
                    '"Contratinsertion"."dd_ci"',
                    '"Contratinsertion"."df_ci"',
                    '"Contratinsertion"."datevalidation_ci"',
                    '"Contratinsertion"."date_saisi_ci"',
                    '"Contratinsertion"."pers_charg_suivi"',
                    '"Contratinsertion"."observ_ci"',
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
                    '"Adresse"."numcomptt"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Contratinsertion.personne_id' )
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
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
                    ),
//                     array(
//                         'table'      => 'structuresreferentes',
//                         'alias'      => 'Structurereferente',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Contratinsertion.structurereferente_id = Structurereferente.id' )
//                     ),
//                     array(
//                         'table'      => 'suivisinstruction',
//                         'alias'      => 'Suiviinstruction',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Suiviinstruction.dossier_rsa_id = Dossier.id' )
//                     ),
//                     array(
//                         'table'      => 'servicesinstructeurs',
//                         'alias'      => 'Serviceinstructeur',
//                         'type'       => 'LEFT OUTER',
//                         'foreignKey' => false,
//                         'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
//                     )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>