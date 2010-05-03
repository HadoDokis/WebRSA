<?php
    $csv->preserveLeadingZerosInExcel = true;

    $csv->addRow( array( 'N° dossier RSA', 'Nom/Prénom allocataire', 'Commune de l\'allocataire',  'Date demande APRE', 'Décision du comité', 'Date de décision', 'Montant attribué', 'Observations' ) );

    foreach( $decisionscomites as $decisioncomite ) {

        $row = array(
            Set::classicExtract( $decisioncomite, 'Dossier.numdemrsa' ),
            Set::classicExtract( $decisioncomite, 'Personne.qual' ).' '.Set::classicExtract( $decisioncomite, 'Personne.nom' ).' '.Set::classicExtract( $decisioncomite, 'Personne.prenom' ) ,
            Set::classicExtract( $decisioncomite, 'Adresse.locaadr' ),
            $locale->date( 'Date::short', Set::classicExtract( $decisioncomite, 'Apre.datedemandeapre' ) ),
            Set::enum( Set::classicExtract( $decisioncomite, 'ApreComiteapre.decisioncomite' ), $options['decisioncomite'] ),
            Set::classicExtract( $decisioncomite, 'Comiteapre.datecomite' ),
            Set::classicExtract( $decisioncomite, 'ApreComiteapre.montantattribue' ),
            Set::classicExtract( $decisioncomite, 'ApreComiteapre.observationcomite' ),
        );
        $csv->addRow($row);
    }

    Configure::write( 'debug', 0 );
    echo $csv->render( 'decisionscomitesapres-'.date( 'Ymd-Hhm' ).'.csv' );
?>