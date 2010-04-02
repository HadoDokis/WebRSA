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
                    $conditions[] = 'Situationdossierrsa.dossier_rsa_id NOT IN ( SELECT propospdos.dossier_rsa_id FROM propospdos )';
                }
                else if( $statutValidationAvis == 'Decisionpdo::enattente' ) {
                    $conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';
                    $conditions[] = 'Propopdo.motifpdo = \'E\'';
//                     $conditions[] = 'Propopdo.decisionpdo_id = \'E\'';
                }
                else if( $statutValidationAvis == 'Decisionpdo::valide' ) {
//                     $conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';
                    $conditions[] = 'Propopdo.motifpdo <> \'E\'';
                    $conditions[] = 'Propopdo.decisionpdo_id IS NOT NULL';
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
            $typepdo_id = Set::extract( $criterespdo, 'Cohortepdo.typepdo_id' );
            $decisionpdo_id = Set::extract( $criterespdo, 'Cohortepdo.decisionpdo_id' );
            $motifpdo = Set::extract( $criterespdo, 'Cohortepdo.motifpdo' );
            $datedecisionpdo = Set::extract( $criterespdo, 'Cohortepdo.datedecisionpdo' );
            $matricule = Set::extract( $criterespdo, 'Cohortepdo.matricule' );
            $numcomptt = Set::extract( $criterespdo, 'Cohortepdo.numcomptt' );

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criterespdo['Cohortepdo'][$criterePersonne] ) && !empty( $criterespdo['Cohortepdo'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \'%'.replace_accents( $criterespdo['Cohortepdo'][$criterePersonne] ).'%\'';
                }
            }

            // Type de PDO
            if( !empty( $typepdo_id ) ) {
                $conditions[] = 'Propopdo.typepdo_id = \''.$typepdo_id.'\'';
            }

            // Motif de la PDO
            if( !empty( $motifpdo ) ) {
                $conditions[] = 'Propopdo.motifpdo ILIKE \'%'.Sanitize::clean( $motifpdo ).'%\'';
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
				if( isset( $criterespdo['Canton']['canton'] ) && !empty( $criterespdo['Canton']['canton'] ) ) {
					$this->Canton =& ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criterespdo['Canton']['canton'] );
				}
			}

            // Décision CG
            if( !empty( $decisionpdo_id ) ) {
                $conditions[] = 'Propopdo.decisionpdo_id = \''.$decisionpdo_id.'\'';
            }

            /// Critères sur les PDOs - date de décision
            if( isset( $criterespdo['Cohortepdo']['datedecisionpdo'] ) && !empty( $criterespdo['Cohortepdo']['datedecisionpdo'] ) ) {
                $valid_from = ( valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_from']['day'] ) );
                $valid_to = ( valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdo['Cohortepdo']['datedecisionpdo_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Propopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdo['Cohortepdo']['datedecisionpdo_from']['year'], $criterespdo['Cohortepdo']['datedecisionpdo_from']['month'], $criterespdo['Cohortepdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdo['Cohortepdo']['datedecisionpdo_to']['year'], $criterespdo['Cohortepdo']['datedecisionpdo_to']['month'], $criterespdo['Cohortepdo']['datedecisionpdo_to']['day'] ) ).'\'';
                }
            }

            $query = array(
                'fields' => array(
                    '"Propopdo"."id"',
                    '"Propopdo"."dossier_rsa_id"',
                    '"Propopdo"."typepdo_id"',
                    '"Propopdo"."decisionpdo_id"',
                    '"Propopdo"."typenotifpdo_id"',
                    '"Propopdo"."datedecisionpdo"',
                    '"Propopdo"."motifpdo"',
                    '"Propopdo"."commentairepdo"',
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
                    '"Adresse"."codepos"',
                    '"Situationdossierrsa"."etatdosrsa"',
                ),
                'joins' => array(
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
                    ),
                    array(
                        'table'      => 'propospdos',
                        'alias'      => 'Propopdo',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'typesnotifspdos',
                        'alias'      => 'Typenotifpdo',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.typenotifpdo_id = Typenotifpdo.id' )
                    ),
                    array(
                        'table'      => 'decisionspdos',
                        'alias'      => 'Decisionpdo',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.decisionpdo_id = Decisionpdo.id' )
                    ),
                    array(
                        'table'      => 'typespdos',
                        'alias'      => 'Typepdo',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Propopdo.typepdo_id = Typepdo.id' )
                    ),
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