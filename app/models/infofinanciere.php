<?php
    class Infofinanciere extends AppModel
    {
        var $name = 'Infofinanciere';
        var $useTable = 'infosfinancieres';


        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );


        var $validate = array(
            'type_allocation' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'natpfcre' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeopecompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'sensopecompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dttraimoucompta' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'mtmoucompta' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
            ),
            'mtmoucompta' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),

            /*'ddregu' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),

            'heutraimoucompta' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            )*/
        );

        function search( $mescodesinsee, $filtre_zone_geo, $criteres ) {
            /// Conditions de base
            $conditions = array();

            /// Critères
            $mois = Set::extract( $criteres, 'Filtre.moismoucompta' );
            $types = Set::extract( $criteres, 'Filtre.type_allocation' );
            $locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );

            /// Mois du mouvement comptable
            if( !empty( $mois ) && dateComplete( $criteres, 'Filtre.moismoucompta' ) ) {
                $month = $mois['month'];
                $year = $mois['year'];
                $conditions[] = 'EXTRACT(MONTH FROM Infofinanciere.moismoucompta) = '.$month;
                $conditions[] = 'EXTRACT(YEAR FROM Infofinanciere.moismoucompta) = '.$year;
            }

            /// Id du Dossier
            if( !empty( $criteres ) && isset( $criteres['Dossier.id'] ) ) {
                $conditions['Dossier.id'] = $criteres['Dossier.id'];
            }

            /// Type d'allocation
            if( !empty( $types ) ) {
                $conditions[] = 'Infofinanciere.type_allocation ILIKE \'%'.Sanitize::clean( $types ).'%\'';
            }

            /// Par adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            /// Par code postal
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            /// Limitation suivant les zones géographiques visibles par l'utilisateur
            if( $filtre_zone_geo ) {
                if( !empty( $mescodesinsee ) ) {
                    $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mescodesinsee ).'\' )';
                }
                else {
                    $conditions[] = 'FALSE';
                }
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
                    '"Dossier"."matricule"',
                    '"Dossier"."typeparte"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."nir"',
                    '"Personne"."dtnai"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Situationdossierrsa"."etatdosrsa"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"',
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
                ),
                'limit' => 10,
                //'order' => array( '"Personne"."nom"' ),
                'order' => array( '"Dossier"."numdemrsa"' ),
                'conditions' => $conditions
            );

            $typesAllocation = array( 'AllocationsComptabilisees', 'IndusConstates', 'IndusTransferesCG', 'RemisesIndus', 'AnnulationsFaibleMontant', 'AutresAnnulations' );
//             $conditionsNotNull = array();


//             foreach( $typesAllocation as $type ) {
//                 $meu  = Inflector::singularize( Inflector::tableize( $type ) );
//                 $query['fields'][] = '"'.$type.'"."mtmoucompta" AS mt_'.$meu;
//
//                 $join = array(
//                     'table'      => 'infosfinancieres',
//                     'alias'      => $type,
//                     'type'       => 'LEFT OUTER',
//                     'foreignKey' => false,
//                     'conditions' => array(
//                         $type.'.dossier_rsa_id = Dossier.id',
//                         $type.'.type_allocation' => $type,
//                     )
//                 );
//
//                 $query['joins'][] = $join;
//                // $conditionsNotNull[] = $type.'.mtmoucompta IS NOT NULL';
//
//             }

            $query['conditions'] = Set::merge( $query['conditions'], $conditions );
            return $query;

        }

        /**
        *
        * @return array contenant les clés minYear et maxYear
        * @access public
        */

        function range() {
            $first = $this->find( 'first', array( 'order' => 'moismoucompta ASC', 'recursive' => -1 ) );
            $last = $this->find( 'first', array( 'order' => 'moismoucompta DESC', 'recursive' => -1 ) );

            if( !empty( $first ) && !empty( $last ) ) {
                list( $yearFirst, ,  ) = explode( '-', $first[$this->name]['moismoucompta'] );
                list( $yearLast, ,  ) = explode( '-', $last[$this->name]['moismoucompta'] );

                return array( 'minYear' => $yearFirst, 'maxYear' => $yearLast );
            }
            else {
                return array( 'minYear' => date( 'Y' ), 'maxYear' => date( 'Y' ) );
            }
        }
    }
?>
