<?php
    App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Critere extends AppModel
    {
        var $name = 'Critere';
        var $useTable = false;

 
        function search( $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array();

            /// Critere zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $locaadr = Set::extract( $criteres, 'Critere.locaadr' );
            $numcomptt = Set::extract( $criteres, 'Critere.numcomptt' );
            $statut_orient = Set::extract( $criteres, 'Critere.statut_orient' );
            $typeorient_id = Set::extract( $criteres, 'Critere.typeorient_id' );
            $structurereferente_id = Set::extract( $criteres, 'Critere.structurereferente_id' );
            $serviceinstructeur_id = Set::extract( $criteres, 'Critere.serviceinstructeur_id' );

            /// Critères sur l'orientation - date d'orientation
            if( isset( $criteres['Critere']['date_valid'] ) && !empty( $criteres['Critere']['date_valid'] ) ) {
                $valid_from = ( valid_int( $criteres['Critere']['date_valid_from']['year'] ) && valid_int( $criteres['Critere']['date_valid_from']['month'] ) && valid_int( $criteres['Critere']['date_valid_from']['day'] ) );
                $valid_to = ( valid_int( $criteres['Critere']['date_valid_to']['year'] ) && valid_int( $criteres['Critere']['date_valid_to']['month'] ) && valid_int( $criteres['Critere']['date_valid_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Orientstruct.date_valid BETWEEN \''.implode( '-', array( $criteres['Critere']['date_valid_from']['year'], $criteres['Critere']['date_valid_from']['month'], $criteres['Critere']['date_valid_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteres['Critere']['date_valid_to']['year'], $criteres['Critere']['date_valid_to']['month'], $criteres['Critere']['date_valid_to']['day'] ) ).'\'';
                }
            }

            // ...
            if( !empty( $dtdemrsa ) && dateComplete( $criteres, 'Critere.dtdemrsa' ) ) {
                $dtdemrsa = $dtdemrsa['year'].'-'.$dtdemrsa['month'].'-'.$dtdemrsa['day'];
                $conditions[] = 'Dossier.dtdemrsa = \''.$dtdemrsa.'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // ...
            if( !empty( $statut_orient ) ) {
                $conditions[] = 'Orientstruct.statut_orient = \''.Sanitize::clean( $statut_orient ).'\'';
            }

            // ...
            if( !empty( $typeorient_id ) ) {
                $conditions[] = 'Orientstruct.typeorient_id = \''.Sanitize::clean( $typeorient_id ).'\'';
            }

            // ...
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Orientstruct.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
            }

            // ... FIXME
            if( !empty( $serviceinstructeur_id ) ) {

                $conditions[] = 'Serviceinstructeur.lib_service ILIKE \''.Sanitize::clean( $serviceinstructeur_id ).'\'';
            }

            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

            $query = array(
                'fields' => array(
                    '"Orientstruct"."id"',
                    '"Orientstruct"."personne_id"',
                    '"Orientstruct"."typeorient_id"',
                    '"Orientstruct"."structurereferente_id"',
                    '"Orientstruct"."propo_algo"',
                    '"Orientstruct"."valid_cg"',
                    '"Orientstruct"."date_propo"',
                    '"Orientstruct"."date_valid"',
                    '"Orientstruct"."statut_orient"',
                    '"Orientstruct"."date_impression"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"',
                    '"Modecontact"."numtel"',
                    '"Serviceinstructeur"."id"',
                    '"Serviceinstructeur"."lib_service"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
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
                        'table'      => 'modescontact',
                        'alias'      => 'Modecontact',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Modecontact.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'typesorients',
                        'alias'      => 'Typeorient',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Typeorient.id = Orientstruct.typeorient_id' )
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Orientstruct.structurereferente_id = Structurereferente.id' )
                    ),
                    array(
                        'table'      => 'suivisinstruction',
                        'alias'      => 'Suiviinstruction',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Suiviinstruction.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'servicesinstructeurs',
                        'alias'      => 'Serviceinstructeur',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>