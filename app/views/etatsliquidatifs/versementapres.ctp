<?php
    $this->pageTitle = 'Versements pour les APREs complémentaires pour l\'état liquidatif';

    echo $html->tag( 'h1', $this->pageTitle );

    ///Fin pagination


    if( empty( $apres ) ) {
        echo $html->tag( 'p', 'Aucune APRE à sélectionner.', array( 'class' => 'notice' ) );
    }
    else {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= ' '.$paginator->numbers().' ';
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $html->tag( 'p', $pages );

        $headers = array(
            $paginator->sort( 'N° Dossier', 'Dossier.numdemrsa' ),
            $paginator->sort( 'N° APRE', 'Apre.numeroapre' ),
            $paginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' ),
            $paginator->sort( 'Nom bénéficiaire', 'Personne.nom' ),
            $paginator->sort( 'Prénom bénéficiaire', 'Personne.prenom' ),
            $paginator->sort( 'Adresse', 'Adresse.locaadr' ),
            $paginator->sort( 'Montant attribué par le comité', 'Apre.montantaverser' ),
            'Nb paiement souhaité',
            'Nb paiement effectué',
//             'Montant à verser',
            'Montant à verser',
            'Montant déjà versé',
        );

        ///
        $thead = $html->tag( 'thead', $html->tableHeaders( $headers ) );

        echo $xform->create( 'ApreEtatliquidatif' );
        // FIXME
        //echo '<div>'.$xform->input( 'Etatliquidatif.id', array( 'type' => 'hidden', 'value' => $this->params['pass'][0] ) ).'</div>';

        /// Corps du tableau
        $rows = array();
        $ajaxes = array();
        foreach( $apres as $i => $apre ) {
            $params = array( 'id' => "apre_{$i}", 'class' => ( ( $i % 2 == 1 ) ? 'odd' : 'even' ) );
            $rows[] = $html->tag( 'tr', $apreversement->cells( $i, $apre, $nbpaiementsouhait ), $params );

            /**
            *   Ajax
            **/
            $ajaxes[] = $ajax->observeField(
                "Apre{$i}Nbpaiementsouhait",
                array(
                    'update' => "apre_{$i}",
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxmontant',
                            $this->params['pass'][0],
                            Set::classicExtract( $apre, 'Apre.id' ),
                            $i
                        ),
                        true
                    )
                )
            );

        }
        $tbody = $html->tag( 'tbody', implode( '', $rows ) );

// debug($etatliquidatif);

        echo $pagination;
        echo $html->tag( 'table', $thead.$tbody );
        echo $pagination;

        $buttons = array();
        $buttons[] = $xform->submit( 'Valider la liste', array( 'div' => false ) );
        $buttons[] = $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        echo $html->tag( 'div', implode( '', $buttons ), array( 'class' => 'submit' ) );

        echo $xform->end();
        echo implode( '', $ajaxes );
    }
?>