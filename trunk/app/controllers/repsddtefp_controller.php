<?php
    class RepsddtefpController extends AppController
    {
        var $name = 'Repsddtefp';

        function beforeFilter() {
            parent::beforeFilter();

        }

        function index() {
            if( !empty( $this->data ) ) {
                $annee = Set::classicExtract( $this->data, 'Repddtefp.annee' );
                $semestre = Set::classicExtract( $this->data, 'Repddtefp.semestre' );
                $locaadr = Set::classicExtract( $this->data, 'Repddtefp.ville' );

                $listeSexe = $this->Repddtefp->listeSexe( $annee, $semestre, $locaadr );
                $listeAge = $this->Repddtefp->listeAge( $annee, $semestre, $locaadr );
//                 $listetotale = $this->Repddtefp->listeTotale();
                $this->set( compact( 'listeSexe', 'listeAge', 'locaadr' ) );
            }
        }
    }
?>