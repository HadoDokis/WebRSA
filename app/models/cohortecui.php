<?php
    class Cohortecui extends AppModel
    {
        var $name = 'Cohortecui';
        var $useTable = false;

        function search( $statutValidation, $mesCodesInsee, $filtre_zone_geo, $criterescui, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array(/* '1 = 1' */);


            if( !empty( $statutValidation ) ) {
                if( $statutValidation == 'Decisioncui::nonvalide' ) {
                    $conditions[] = '( ( Cui.decisioncui <> \'V\' ) AND /*( Cui.decisioncui <> \'E\' ) ) OR ( */Cui.decisioncui IS NOT NULL )'; ///FIXME: pourquoi avoir mis <>E !!!
                }
                else if( $statutValidation == 'Decisioncui::enattente' ) {
                    $conditions[] = 'Cui.decisioncui = \'E\'';
                }
                else if( $statutValidation == 'Decisioncui::valides' ) {
                    $conditions[] = 'Cui.decisioncui IS NOT NULL';
                    $conditions[] = 'Cui.decisioncui = \'V\'';
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
            $datecontrat = Set::extract( $criterescui, 'Filtre.datecontrat' );
            $decisioncui = Set::extract( $criterescui, 'Filtre.decisioncui' );
            $datevalidationcui = Set::extract( $criterescui, 'Filtre.datevalidationcui' );
            $locaadr = Set::extract( $criterescui, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criterescui, 'Filtre.numcomptt' );
            $nir = Set::extract( $criterescui, 'Filtre.nir' );
            $natpf = Set::extract( $criterescui, 'Filtre.natpf' );
            $personne_suivi = Set::extract( $criterescui, 'Filtre.pers_charg_suivi' );
            $forme_ci = Set::extract( $criterescui, 'Filtre.forme_ci' );
            $structurereferente_id = Set::extract( $criterescui, 'Filtre.structurereferente_id' );
            $referent_id = Set::extract( $criterescui, 'Filtre.referent_id' );
            $matricule = Set::extract( $criterescui, 'Filtre.matricule' );


            /// Critères sur le CI - date de saisi contrat
            if( isset( $criterescui['Filtre']['datecontrat'] ) && !empty( $criterescui['Filtre']['datecontrat'] ) ) {
                $valid_from = ( valid_int( $criterescui['Filtre']['datecontrat_from']['year'] ) && valid_int( $criterescui['Filtre']['datecontrat_from']['month'] ) && valid_int( $criterescui['Filtre']['datecontrat_from']['day'] ) );
                $valid_to = ( valid_int( $criterescui['Filtre']['datecontrat_to']['year'] ) && valid_int( $criterescui['Filtre']['datecontrat_to']['month'] ) && valid_int( $criterescui['Filtre']['datecontrat_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Cui.datecontrat BETWEEN \''.implode( '-', array( $criterescui['Filtre']['datecontrat_from']['year'], $criterescui['Filtre']['datecontrat_from']['month'], $criterescui['Filtre']['datecontrat_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterescui['Filtre']['datecontrat_to']['year'], $criterescui['Filtre']['datecontrat_to']['month'], $criterescui['Filtre']['datecontrat_to']['day'] ) ).'\'';
                }
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criterescui['Filtre'][$criterePersonne] ) && !empty( $criterescui['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criterescui['Filtre'][$criterePersonne] ).'\'';
                }
            }

            // ...
            if( !empty( $decisioncui ) ) {
                $conditions[] = 'Cui.decisioncui = \''.Sanitize::clean( $decisioncui ).'\'';
            }

            // ...
            if( !empty( $datevalidationcui ) && dateComplete( $criterescui, 'Filtre.datevalidationcui' ) ) {
                $datevalidationcui = $datevalidationcui['year'].'-'.$datevalidationcui['month'].'-'.$datevalidationcui['day'];
                $conditions[] = 'Cui.datevalidationcui = \''.$datevalidationcui.'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // ...
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
            }

            // Nature de la prestation
            if( !empty( $natpf ) ) {
                //$conditions[] = 'detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'';
                $conditions[] = 'Detaildroitrsa.id IN (
                                    SELECT detailscalculsdroitsrsa.detaildroitrsa_id
                                        FROM detailscalculsdroitsrsa
                                        WHERE detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'
                                )';
            }

            /// Critères sur l'adresse - canton
            if( Configure::read( 'CG.cantons' ) ) {
                if( isset( $criterescui['Canton']['canton'] ) && !empty( $criterescui['Canton']['canton'] ) ) {
                    $this->Canton =& ClassRegistry::init( 'Canton' );
                    $conditions[] = $this->Canton->queryConditions( $criterescui['Canton']['canton'] );
                }
            }

            // NIR
            if( !empty( $nir ) ) {
                $conditions[] = 'Personne.nir ILIKE \'%'.Sanitize::clean( $nir ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // Personne chargée du suiv
            if( !empty( $personne_suivi ) ) {
                $conditions[] = 'Cui.pers_charg_suivi = \''.Sanitize::clean( $personne_suivi ).'\'';
            }

            // Forme du contrat
            if( !empty( $forme_ci ) ) {
                $conditions[] = 'Cui.forme_ci = \''.Sanitize::clean( $forme_ci ).'\'';
            }

            /// Structure référente
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Cui.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
            }

            /// Référent
            if( !empty( $referent_id ) ) {
                $conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
            }

            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Cui"."id"',
                    '"Cui"."personne_id"',
                    '"Cui"."referent_id"',
                    '"Cui"."structurereferente_id"',
                    '"Cui"."decisioncui"',
                    '"Cui"."datevalidationcui"',
                    '"Cui"."datecontrat"',
                    '"Cui"."observcui"',
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
                        'conditions' => array( 'Personne.id = Cui.personne_id' )
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
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
                    ),
                    array(
                        'table'      => 'detailsdroitsrsa',
                        'alias'      => 'Detaildroitrsa',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Detaildroitrsa.dossier_rsa_id = Dossier.id' )
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>