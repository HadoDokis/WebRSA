<?php
    class Cohorteindu extends AppModel
    {
        var $name = 'Cohorteindu';
        var $useTable = false;

//         var $validate = array(
//             'mtmoucompta' => array(
//                 array(
//                     'rule' => 'date',
//                     'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
//                 )
//             )
//         );

        function search( $mesCodesInsee, $filtre_zone_geo, $criteresindu, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array(/* '1 = 1' */);

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
            $natpfcre = Set::extract( $criteresindu, 'Filtre.natpfcre' );
            $locaadr = Set::extract( $criteresindu, 'Filtre.locaadr' );
            $nom = Set::extract( $criteresindu, 'Filtre.nom' );
            $typeparte = Set::extract( $criteresindu, 'Filtre.typeparte' );
            $structurereferente_id = Set::extract( $criteresindu, 'Filtre.structurereferente_id' );
            $mtmoucompta = Set::extract( $criteresindu, 'Filtre.mtmoucompta' );

            // Type d'indu
            if( !empty( $natpfcre ) ) {
                $conditions[] = 'Infofinanciere.natpfcre = \''.Sanitize::clean( $natpfcre ).'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // Nom allocataire
            if( !empty( $nom ) ) {
                $conditions[] = 'Personne.nom ILIKE \'%'.Sanitize::clean( $nom ).'%\'';
            }

            // Suivi
            if( !empty( $typeparte ) ) {
                $conditions[] = 'Dossier.typeparte = \''.Sanitize::clean( $typeparte ).'\'';
            }

            // Montant indu
            if( !empty( $mtmoucompta ) ) {
                $conditions[] = 'Infofinanciere.mtmoucompta = \''.Sanitize::clean( $mtmoucompta ).'\'';
            }


            // Structure référente
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Structurereferente.id = \''.$structurereferente_id.'\'';
            }


            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Infofinanciere"."id"',
                    '"Infofinanciere"."dossier_rsa_id"',
                    '"Infofinanciere"."moismoucompta"',
                    '"Infofinanciere"."type_allocation"',
                    '"Infofinanciere"."natpfcre"',
                    '"Infofinanciere"."rgcre"',
                    '"Infofinanciere"."numintmoucompta"',
                    '"Infofinanciere"."typeopecompta"',
                    '"Infofinanciere"."sensopecompta"',
                    '"Infofinanciere"."mtmoucompta"',
                    '"Infofinanciere"."ddregu"',
                    '"Infofinanciere"."dttraimoucompta"',
                    '"Infofinanciere"."heutraimoucompta"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Dossier"."matricule"',
                    '"Dossier"."typeparte"',
                    '"Situationdossierrsa"."id"',
                    '"Situationdossierrsa"."etatdosrsa"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."codepos"',
                    '"Structurereferente"."id"',
                    '"Structurereferente"."lib_struc"',
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Infofinanciere.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id' )
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
                        'table'      => 'contratsinsertion',
                        'alias'      => 'Contratinsertion',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Contratinsertion.personne_id = Personne.id' )
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Contratinsertion.structurereferente_id = Structurereferente.id' )
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
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>