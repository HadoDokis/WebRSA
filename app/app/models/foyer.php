<?php
    class Foyer extends AppModel
    {
        var $name = 'Foyer';

        var $belongsTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasMany = array(
            'Adressefoyer' => array(
                'classname'     => 'Adressefoyer',
                'foreignKey'    => 'foyer_id'
            ),
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'foyer_id'
            ),
            'Modecontact' => array(
                'classname'     => 'Modecontact',
                'foreignKey'    => 'foyer_id'
            ),
            'Evenement' => array(
                'classname'     => 'Evenement',
                'foreignKey'    => 'foyer_id'
            ),
            'Creance' => array(
                'classname'     => 'Creance',
                'foreignKey'    => 'foyer_id'
            ),
            'Paiementfoyer'
        );

        //*********************************************************************

        function dossierId( $foyer_id ) {
            $foyer = $this->findById( $foyer_id, null, null, -1 );
            if( !empty( $foyer ) ) {
                return $foyer['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************

        function nbEnfants( $foyer_id ){

            $sql = "SELECT COUNT(Prestation.id)
                        FROM prestations AS Prestation
                            INNER JOIN personnes AS Personne ON Personne.id = Prestation.personne_id
                        WHERE Personne.foyer_id = {$foyer_id}
                            AND Prestation.natprest = 'RSA'
                            AND Prestation.rolepers = 'ENF'";
            $result = $this->Personne->query( $sql );
           return $result[0][0]['count'];
        }
//         function refreshRessources( $foyer_id ) {
//             $this->Personne->bindModel(
//                 array(
//                     'hasOne' => array( 'Prestation' ),
//                     'hasMany' => array( 'Ressource' )
//                 )
//             );
//             $personnesFoyer = $this->Personne->find(
//                 'all',
//                 array(
//                     'conditions' => array(
//                         'Personne.foyer_id' => $foyer_id
//                     ),
//                     'recursive' => 0
//                 )
//             );
//
//             $saved = true;
//             foreach( $personnesFoyer as $personne ) {
//                 $rolepers = Set::extract( $personne, 'Prestation.rolepers' );
//                 if( $rolepers == 'DEM' || $rolepers == 'CJT' ) {
//                     $saved =  $this->Personne->Ressource->refresh( $personne['Personne']['id'] ) && $saved;
//                 }
//             }
//
//             return $saved;
//         }

        function refreshRessources( $foyer_id ) {
            $query = array(
                'fields' => array(
                    '"Personne"."id'
                ),
                'joins' => array(
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            'Prestation.rolepers' => array( 'DEM', 'CJT' )
                        )
                    )
                ),
                'conditions' => array(
                    'Personne.foyer_id' => $foyer_id
                ),
                'recursive' => -1
            );

            $personnes = $this->Personne->find( 'all', $query );
            $this->Personne->bindModel( array( 'hasMany' => array( 'Ressource' ) ) );

            $saved = true;
            foreach( $personnes as $personne ) {
                $saved =  $this->Personne->Ressource->refresh( $personne['Personne']['id'] ) && $saved;
            }

            return $saved;
        }

        //*********************************************************************

        function refreshSoumisADroitsEtDevoirs( $foyer_id ) {
            $this->Personne->unbindModel(
                array(
                    'hasMany' => array( 'Orientstruct' ),
                    'hasOne' => array( 'Prestation', 'Calculdroitrsa' )
                )
            );
            $query = array(
                'fields' => array(
                    '"Personne"."id"',
                    '"Prestation"."id"',
                    '"Calculdroitrsa"."id"'
                ),
                'joins' => array(
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            'Prestation.rolepers' => array( 'DEM', 'CJT' )
                        )
                    ),
                    array(
                        'table'      => 'calculsdroitsrsa',
                        'alias'      => 'Calculdroitrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Calculdroitrsa.personne_id' )
                    ),
                ),
                'conditions' => array(
                    'Personne.foyer_id' => $foyer_id
                ),
                'recursive' => 1
            );

            $personnesFoyer = $this->Personne->find( 'all', $query );

            $saved = true;
            foreach( $personnesFoyer as $personne ) {
                $personne['Calculdroitrsa']['toppersdrodevorsa'] = $this->Personne->soumisDroitsEtDevoirs( $personne['Personne']['id'] );
                $this->Personne->Calculdroitrsa->create( $personne['Calculdroitrsa'] );
                $saved =  $this->Personne->Calculdroitrsa->save( $personne['Calculdroitrsa'] ) && $saved;

                // Ajout dans la table Orientstruct si aucune entrée
                $nbrOrientstruct = $this->Personne->Orientstruct->find( 'count', array( 'conditions' => array( 'Orientstruct.personne_id' => $personne['Personne']['id'] ) ) );
                if( $personne['Calculdroitrsa']['toppersdrodevorsa'] && $nbrOrientstruct == 0 ) {
                    $orientstruct = array(
                        'Orientstruct' => array(
                            'personne_id' => $personne['Personne']['id'],
                            'statut_orient' => 'Non orienté'
                        )
                    );
                    $this->Personne->Orientstruct->create( $orientstruct );
                    $saved = $this->Personne->Orientstruct->save() && $saved;
                }
            }

            return $saved;
        }

        //*********************************************************************

        /**
            FIXME: spécifique CG93 ?
        */
        function montantForfaitaire( $id ) {
            $F = 454.63;
            $this->Personne->unbindModelAll();
            $this->Personne->bindModel(
                array(
                    /*'hasMany' => array(
                        'Ressource' => array(
                            'order' => array( 'dfress DESC' )
                        )
                    )*/
                    'hasOne' => array(
                        'Calculdroitrsa'
					)
                )
            );

            $personnes = $this->Personne->find(
                'all',
                array(
                    'conditions' => array( 'Personne.foyer_id' => $id )
                )
            );

            // a) Si 1 foyer = 1 personne, montant forfaitaire = F (= 454,63 EUR)
            if( count( $personnes ) == 1 ) {
                $mtpersressmenrsa = Set::extract( $personnes, '/Calculdroitrsa/mtpersressmenrsa' );
                $montant = array_sum( Set::filter( $mtpersressmenrsa ) );
                return ( $montant < $F );
            }
            // b) Si 1 foyer = 2 personnes, montant forfaitaire = 150% F
            else if( count( $personnes ) == 2 ) {
                $mtpersressmenrsa = Set::extract( $personnes, '/Calculdroitrsa/mtpersressmenrsa' );
                $montant = array_sum( Set::filter( $mtpersressmenrsa ) );
                return ( $montant < ( $F * 1.5 ) );
            }
            else {
                $X = 0;
                $Y = 0;
                $montant = 0;

                foreach( $personnes as $personne ) {
                    list( $year, $month, $day ) = explode( '-', $personne['Personne']['dtnai'] );
                    $today = time();
                    $age = date( 'Y', $today ) - $year + ( ( ( $month > date( 'm', $today ) ) || ( $month == date( 'm', $today ) && $day > date( 'd', $today ) ) ) ? -1 : 0 );

                    if( $age >= 25 ) {
                        $X++;
                    }
                    else {
                        $Y++;
                    }

					$mtpersressmenrsa = Set::extract( $personnes, '/Calculdroitrsa/mtpersressmenrsa' );
					$montant += array_sum( Set::filter( $mtpersressmenrsa ) );
                }

                // c) Si 1 foyer = X personnes de plus de 25 ans + Y personnes de moins de 25 ans et X+Y>2 et Y=<2 , montant forfaitaire = 150% F + 30%F(X-2)
                if( $Y <= 2 ) {
                    return ( $montant < ( ( 1.5 * $F ) + ( 0.3 * $F * ( $X - 2 ) ) ) );
                }
                // d) Si 1 foyer = X personnes de plus de 25 ans + Y personnes de moins de 25 ans et X+Y>2 et Y>2 , montant forfaitaire = 150% F + 40%F(X-2)
                else if( $Y > 2 ) {
                    return ( $montant < ( ( 1.5 * $F ) + ( 0.4 * $F * ( $X - 2 ) ) ) );
                }
            }
        }
    }
?>