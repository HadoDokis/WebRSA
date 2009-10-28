<?php
    App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Critererdv extends AppModel
    {
        var $name = 'Critererdv';
        var $useTable = false;

        function search( $mesCodesInsee, $filtre_zone_geo, $criteresrdv ) {
            /// Conditions de base
            $conditions = array();

            /// Critères
            $statutrdv = Set::extract( $criteresrdv, 'Critererdv.statutrdv' );
            $natpf = Set::extract( $criteresrdv, 'Critererdv.natpf' );
            $typerdv_id = Set::extract( $criteresrdv, 'Critererdv.typerdv_id' );
            $structurereferente_id = Set::extract( $criteresrdv, 'Critererdv.structurereferente_id' );
            $referent_id = Set::extract( $criteresrdv, 'Critererdv.referent_id' );
            $permanence_id = Set::extract( $criteresrdv, 'Critererdv.permanence_id' );
            $locaadr = Set::extract( $criteresrdv, 'Critererdv.locaadr' );
            $numcomptt = Set::extract( $criteresrdv, 'Critererdv.numcomptt' );
            $nom = Set::extract( $criteresrdv, 'Critererdv.nom' );

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Critères sur le RDV - date de demande
            if( isset( $criteresrdv['Critererdv']['daterdv'] ) && !empty( $criteresrdv['Critererdv']['daterdv'] ) ) {
                $valid_from = ( valid_int( $criteresrdv['Critererdv']['daterdv_from']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['day'] ) );
                $valid_to = ( valid_int( $criteresrdv['Critererdv']['daterdv_to']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Rendezvous.daterdv BETWEEN \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_from']['year'], $criteresrdv['Critererdv']['daterdv_from']['month'], $criteresrdv['Critererdv']['daterdv_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_to']['year'], $criteresrdv['Critererdv']['daterdv_to']['month'], $criteresrdv['Critererdv']['daterdv_to']['day'] ) ).'\'';
                }
            }
            /// Statut RDV
            if( !empty( $statutrdv ) ) {
                $conditions[] = 'Rendezvous.statutrdv ILIKE \'%'.Sanitize::clean( $statutrdv ).'%\'';
            }

            /// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresrdv['Critererdv'][$criterePersonne] ) && !empty( $criteresrdv['Critererdv'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \'%'.$criteresrdv['Critererdv'][$criterePersonne].'%\'';
                }
            }

            /// Adresse personne
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            /// Code INSSE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            /// Structure référente
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Rendezvous.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
            }

            /// Référent
            if( !empty( $referent_id ) ) {
                $conditions[] = 'Rendezvous.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
            }

            /// Permanence
            if( !empty( $permanence_id ) ) {
                $conditions[] = 'Rendezvous.permanence_id = \''.Sanitize::clean( $permanence_id ).'\'';
            }

            /// Nature de la prestation
            if( !empty( $natpf ) ) {
                $conditions[] = 'Detailcalculdroitrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'';
            }

            /// Type de rendez vous
            if( !empty( $typerdv_id ) ) {
                $conditions[] = 'Rendezvous.typerdv_id = \''.Sanitize::clean( $typerdv_id ).'\'';
            }
            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Rendezvous"."id"',
                    '"Rendezvous"."personne_id"',
                    '"Rendezvous"."referent_id"',
                    '"Rendezvous"."permanence_id"',
                    '"Rendezvous"."structurereferente_id"',
                    '"Rendezvous"."typerdv_id"',
                    '"Rendezvous"."statutrdv"',
                    '"Rendezvous"."daterdv"',
                    '"Rendezvous"."heurerdv"',
                    '"Rendezvous"."objetrdv"',
                    '"Rendezvous"."commentairerdv"',
                    '"Dossier"."numdemrsa"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."nomcomnai"',
                    '"Personne"."dtnai"',
                    '"Structurereferente"."lib_struc"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Rendezvous.personne_id = Personne.id' ),
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Structurereferente.id = Rendezvous.structurereferente_id' ),
                    ),
                    array(
                        'table'      => 'typesrdv',
                        'alias'      => 'Typerdv',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Typerdv.id = Rendezvous.typerdv_id' ),
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
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'detailsdroitsrsa',
                        'alias'      => 'Detaildroitrsa',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Detaildroitrsa.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'detailscalculsdroitsrsa',
                        'alias'      => 'Detailcalculdroitrsa',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Detailcalculdroitrsa.detaildroitrsa_id = Detaildroitrsa.id' )
                    )
                ),
//                 'group' => array(
//                     'Totalisationacompte.type_totalisation',
//                     'Totalisationacompte.id'
//                 ),
                'order' => array( '"Rendezvous"."daterdv" ASC' ),
                'conditions' => $conditions
            );

            return $query;

        }
    }
?>