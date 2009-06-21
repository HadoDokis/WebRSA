<?php
    class Foyer extends AppModel
    {
        var $name = 'Foyer';

        var $hasOne = array(
            'Dspf'
        );

        var $belongTo = array(
            'Dossier' => array(
                'classname'     => 'Dossier',
                'foreignKey'    => 'dossier_rsa_id'
            )
        );

        var $hasAndBelongsToMany = array(
            'Creance' => array(
                'classname' => 'Creance',
                'joinTable' => 'foyers_creances',
                'foreignKey' => 'foyer_id',
                'associationForeignKey' => 'creance_id'
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
            'ModeContact' => array(
                'classname'     => 'ModeContact',
                'foreignKey'    => 'foyer_id'
            ),
            'AdressesFoyer' => array(
                'classname'     => 'AdressesFoyer',
                'foreignKey'    => 'foyer_id'
            )/*,
            'Creance' => array(
                'classname'     => 'Creance',
                'foreignKey'    => 'foyer_id'
            )*/
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

        /**
            FIXME: spécifique CG93 ?
        */
        function montantForfaitaire( $id ) {
            $F = 454.63;
            $this->Personne->unbindModelAll();
            $this->Personne->bindModel(
                array(
                    'hasMany' => array(
                        'Ressource' => array(
                            'order' => array( 'dfress DESC' )
                        )
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
                $mtpersressmenrsa = Set::extract( $personnes, '{0}.Ressource.{0}.mtpersressmenrsa' );
                $montant = ( ( isset( $mtpersressmenrsa[0] ) && isset( $mtpersressmenrsa[0][0] ) ) ? $mtpersressmenrsa[0][0] : 0 );
                return ( $montant < $F );
            }
            // b) Si 1 foyer = 2 personnes, montant forfaitaire = 150% F
            else if( count( $personnes ) == 2 ) {
                $mtpersressmenrsa = Set::extract( $personnes, '{n}.Ressource.{n}.mtpersressmenrsa' );
                $montant = 0;
                foreach( $mtpersressmenrsa as $mntPersonne ) {
                    $montant += ( isset( $mntPersonne[0] ) ? $mntPersonne[0] : 0 );
                }
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

                    if( isset( $personne['Ressource'] ) && isset( $personne['Ressource'][0] ) && isset( $personne['Ressource'][0]['mtpersressmenrsa'] ) ) {
                        $montant += $personne['Ressource'][0]['mtpersressmenrsa'];
                    }
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