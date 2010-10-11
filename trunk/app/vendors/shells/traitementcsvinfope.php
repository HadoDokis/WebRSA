<?php
    /**
    *    SELECT *
    *        FROM personnes
    *        WHERE personnes.nir IN ( '2600299039690', '2571137003255', '1570944084206',
    *           '2780766136252', '1660699223030', '2800410387113', '2751275115269',
    *           '2670251108017', '1781006029045', '2680999350650', '1691059512104' );
    */

    class TraitementcsvinfopeShell extends Shell
    {
        var $uses = array( 'Tempinscription', 'Tempcessation', 'Tempradiation', 'Personne', 'Infopoleemploi' );

        /**
        *   Ajout de la clé à la suite du NIR en cas d'absence de celle-ci
        *
        */

        function nirAvecCle( $nir ) {
            $modulo = bcmod( $nir, 97 );
            $cle = ( 97 - $modulo );
            return "$nir$cle";
        }

        /**
        *
        *
        */

        function startup() {
            if( ( count( $this->args ) != 1 ) || ( !in_array( $this->args[0], array( 'c', 'i', 'r', 'C', 'I', 'R' ) ) ) ) {
                echo "Veuillez rentrer en paramètre i pour inscription, c pour cessation ou r pour radiation\n";
                exit( 1 );
            }

            switch( $this->args[0] ) {
                case 'c': case 'C': $this->table = 'Tempcessation'; break;
                case 'i': case 'I': $this->table = 'Tempinscription'; break;
                case 'r': case 'R': $this->table = 'Tempradiation'; break;
            }
        }

        /**
        *
        */

        function main() {
            $personnesTrouvees = 0;
            $personnesTraitees = 0;
            $success = true;

            ///Nombre de personnes avec un NIR mauvais (absence de la clé)
            $results = $this->{$this->table}->find( 'all' );
            echo sprintf( "%s nirs à traiter\n", count( $results ) );

            ///Début de la transaction
            $this->Tempinscription->begin();

            foreach( $results as $result ){
                if( preg_match( '/^[0-9]{13}$/', $result[$this->table]['nir'] ) ) {
                    $result[$this->table]['nir'] = $this->nirAvecCle( $result[$this->table]['nir'] );

                    $personne = $this->Personne->findByNir( $result[$this->table]['nir'], null, null, -1 );
                    if( !empty( $personne ) ) {
                        $personnesTrouvees++;
                        echo $result[$this->table]['nir']."\n";

                        if( $this->table == 'Tempinscription' ) {
                            $infopoleemploi = array(
                                'Infopoleemploi' => array(
                                    'personne_id' => Set::classicExtract( $personne, 'Personne.id' ),
                                    'identifiantpe' => Set::classicExtract( $result, "{$this->table}.identifiantpe" ),
                                    'dateinscription' => Set::classicExtract( $result, "{$this->table}.dateinscription" ),
                                    'categoriepe' => Set::classicExtract( $result, "{$this->table}.categoriepe" )
                                )
                            );
                        }
                        else {
                            $infopoleemploi = $this->Infopoleemploi->findByPersonneId( Set::classicExtract( $personne, 'Personne.id' ), null, null, -1 );
                            if( !empty( $infopoleemploi ) ) {
                                if( $this->table == 'Tempcessation' ) {
                                    foreach( array( 'datecessation', 'motifcessation' ) as $field ) {
                                        $infopoleemploi['Infopoleemploi'][$field] = Set::classicExtract( $result, "{$this->table}.$field" );
                                    }
                                }
                                else if( $this->table == 'Tempradiation' ) {
                                    foreach( array( 'dateradiation', 'motifradiation' ) as $field ) {
                                        $infopoleemploi['Infopoleemploi'][$field] = Set::classicExtract( $result, "{$this->table}.$field" );
                                    }
                                }
                            }
                        }

                        if( !empty( $infopoleemploi ) ) {
                            $this->Infopoleemploi->create( $infopoleemploi );
                            if( $tmpSuccess = $this->Infopoleemploi->save() ) {
                                $personnesTraitees++;
                                $tmpSuccess = $this->{$this->table}->delete( Set::classicExtract( $result, "{$this->table}.id" ) ) && $tmpSuccess;
                            }
                            $success = $tmpSuccess && $success;
                        }
                    }
                }
            }
            $this->hr();

            $message = "%s: $personnesTrouvees personnes trouvées sur ".count($results).", $personnesTraitees personnes traitées.\n";

            /// Fin de la transaction
            if( $success ) {
                echo sprintf( $message, "Script {$this->table} terminé avec succès" ); // FIXME $this->table
                $this->Tempinscription->commit();
                return 0;
            }
            else {
                echo sprintf( $message, "Script {$this->table} terminé avec erreurs" ); // FIXME $this->table
                $this->Tempinscription->rollback();
                return 1;
            }
        }
    }
?>