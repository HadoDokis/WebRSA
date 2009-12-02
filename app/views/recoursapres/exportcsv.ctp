<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'N° demande APRE', 'Nom de l\'allocataire', 'Commune de l\'allocataire',  'Date demande APRE', 'Décision comité', 'Date décision comité', 'Demande recours', 'Date recours', 'Observations recours' ) );

    foreach( $recoursapres as $recoursapre ) {

        $row = array(
            Set::classicExtract( $recoursapre, 'Apre.numeroapre' ),
            Set::classicExtract( $recoursapre, 'Personne.qual' ).' '.Set::classicExtract( $recoursapre, 'Personne.nom' ).' '.Set::classicExtract( $recoursapre, 'Personne.prenom' ),
            Set::classicExtract( $recoursapre, 'Adresse.locaadr' ),
            $locale->date( 'Date::short', Set::classicExtract( $recoursapre, 'Apre.datedemandeapre' ) ),
            Set::enum( Set::classicExtract( $recoursapre, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ),
            $locale->date( 'Date::short', Set::classicExtract( $recoursapre, 'Comiteapre.datecomite' ) ),
            Set::enum( Set::classicExtract( $recoursapre, 'ApreComiteapre.recoursapre' ), $options['recoursapre'] ),
            $locale->date( 'Date::short', Set::classicExtract( $recoursapre, 'ApreComiteapre.daterecours' ) ),
            Set::classicExtract( $recoursapre, 'ApreComiteapre.observationrecours' ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'recoursapres-'.date( 'Ymd-Hhm' ).'.csv' );
?>