<?php

    App::import( 'Sanitize' );

    class Repddtefp extends AppModel{

        var $name = 'Repddtefp';
        var $useTable = false;


        /**
        *
        */

        function _query( $sql ) {
            $results = $this->query( $sql );
            return Set::classicExtract( $results, '{n}.0' );
        }


        /**
        *
        */

        function _conditionsTemporelles( $annee, $semestre ) {
            if( $semestre == 1 ) {
                $range = array( 1, 2 );
            }
            else if( $semestre == 2 ) {
                $range = array( 3, 4 );
            }
            else {
                // FIXME: throw error
            }
            return 'EXTRACT(YEAR FROM apres.datedemandeapre) = '.$annee.' AND EXTRACT( QUARTER FROM apres.datedemandeapre ) IN ('.implode( ',', $range ).')';
        }

        /**
        *
        */

        function _nbrPersonnesInstruitesParSexe( $annee, $semestre, $sexe, $numcomptt ) {
            $sql = 'SELECT ( CASE WHEN ( EXTRACT( DAY FROM apres.datedemandeapre ) <= 15 ) THEN 1 ELSE 2 END ) AS quinzaine, EXTRACT(MONTH FROM apres.datedemandeapre) AS mois, EXTRACT(YEAR FROM apres.datedemandeapre) AS annee, COUNT(apres.*) AS indicateur
                        FROM apres
                            INNER JOIN personnes ON personnes.id = apres.personne_id
                            INNER JOIN foyers ON personnes.foyer_id = foyers.id
                            INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
                            INNER JOIN adresses ON adresses_foyers.adresse_id = adresses.id
                        WHERE '.$this->_conditionsTemporelles( $annee, $semestre ).'
                            AND personnes.sexe = \''.$sexe.'\'
                            AND adresses.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'
                        GROUP BY annee, mois, quinzaine
                        ORDER BY annee, mois, quinzaine;';

            $results = $this->_query( $sql );
            return $results;
        }

        /**
        *
        */

        function _nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, $ageMin, $ageMax, $numcomptt ) {
            $sql = 'SELECT ( CASE WHEN ( EXTRACT( DAY FROM apres.datedemandeapre ) <= 15 ) THEN 1 ELSE 2 END ) AS quinzaine, EXTRACT(MONTH FROM apres.datedemandeapre) AS mois, EXTRACT(YEAR FROM apres.datedemandeapre) AS annee, COUNT(apres.*) AS indicateur
                        FROM apres
                            INNER JOIN personnes ON personnes.id = apres.personne_id
                            INNER JOIN foyers ON personnes.foyer_id = foyers.id
                            INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
                            INNER JOIN adresses ON adresses_foyers.adresse_id = adresses.id
                        WHERE '.$this->_conditionsTemporelles( $annee, $semestre ).'
                            AND ( EXTRACT ( YEAR FROM AGE( personnes.dtnai ) ) ) BETWEEN '.$ageMin.' AND '.$ageMax.'
                            AND adresses.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'
                        GROUP BY annee, mois, quinzaine
                        ORDER BY annee, mois, quinzaine;';

            $results = $this->_query( $sql );
            return $results;
        }

        /**
        *
        */

        function listeSexe( $annee, $semestre, $numcomptt ) {
            $results['nbrHommesInstruits'] = $this->_nbrPersonnesInstruitesParSexe( $annee, $semestre, 1, $numcomptt );
            $results['nbrFemmesInstruits'] = $this->_nbrPersonnesInstruitesParSexe( $annee, $semestre, 2, $numcomptt );
            return $results;
        }


        function listeAge( $annee, $semestre, $numcomptt ) {
            $results['nbr0_24Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 0, 24, $numcomptt );
            $results['nbr25_34Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 25, 34, $numcomptt );
            $results['nbr35_44Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 35, 44, $numcomptt );
            $results['nbr45_54Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 45, 54, $numcomptt );
            $results['nbr55_59Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 55, 59, $numcomptt );
            $results['nbr60_200Instruits'] = $this->_nbrPersonnesInstruitesParTrancheDAge( $annee, $semestre, 60, 200, $numcomptt );
            return $results;
        }
    }
?>