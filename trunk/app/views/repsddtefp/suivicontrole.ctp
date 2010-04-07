<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<h1><?php echo $this->pageTitle='APRE: Suivi et contrôle de l\'enveloppe';?></h1>



<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'RepddtefpMoisMonth', [ 'RepddtefpQuinzaine' ], '', true );
    })
</script>
<!--/************************************************************************/ -->
<?php
    /*if( isset( $apres ) ) {
		// FIXME: utiliser xpaginator ?
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ), array( 'class' => 'pagination' ) );

        $pages = implode(
			'&nbsp;&nbsp;',
			array(
				$paginator->first( '<<' ),
				$paginator->prev( '<' ),
				$paginator->numbers(),
				$paginator->next( '>' ),
				$paginator->last( '>>' )
			)
		);

        $pagination .= $html->tag( 'p', $pages, array( 'class' => 'pagination' ) );
    }
    else {
        $pagination = '';
    }*/
    $pagination = $xpaginator->paginationBlock( 'Apre', $this->passedArgs );
?>
<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'ReportingApre' ).toggle(); return false;" )
        ).'</li></ul>';
    }

?>
<!-- /************************************************************************/ -->

<?php
    echo $form->create( 'ReportingApre', array( 'url' => Router::url( null, true ), 'id' => 'ReportingApre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );
?>
<?php
    echo $form->input( 'Repddtefp.annee', array( 'label' => 'Année', 'type' => 'select', 'options' => array_range( date( 'Y' ), 2008 ), 'empty' => true ) );

    echo $form->input( 'Repddtefp.mois', array( 'label' => 'Mois', 'type' => 'date', 'dateFormat' => 'M', 'empty' => true ) );

    echo $form->input( 'Repddtefp.quinzaine', array( 'label' => 'Quinzaine', 'type' => 'select', 'options' => $quinzaine, 'empty' => true   ) );

    echo $form->input( 'Repddtefp.statutapre', array( 'label' => 'Statut de l\'APRE', 'type' => 'select', 'options' => $options['statutapre'], 'empty' => true   ) );
?>

<?php
    echo $form->input( 'Repddtefp.numcomptt', array( 'label' => __d( 'apre', 'Repddtefp.numcomptt', true ), 'type' => 'select', 'options' => $mesCodesInsee,  'empty' => true ) );

    echo $form->submit( 'Calculer' );
    echo $form->end();
?>

<?php if( !empty( $this->data ) ):?>
    <h2 class="noprint">Résultats de la recherche</h2>


    <?php if( is_array( $apres ) && count( $apres ) > 0  ):?>
        <?php echo $pagination;?>
        <?php
            $annee = Set::classicExtract( $this->data, 'Repddtefp.annee' );
            echo '<h2>Données pour l\'année : '.$annee.'</h2>';
            $mois = Set::classicExtract( $this->data, 'Repddtefp.mois.month' );
            if( !empty( $mois ) ) {
                echo '<h2>Données pour le mois : '.$mois.'</h2>';
            }
            $quinzaine = Set::classicExtract( $this->data, 'Repddtefp.quinzaine' );
            if( !empty( $quinzaine ) ) {
                echo '<h2>Données pour la quinzaine : '.$quinzaine.'</h2>';
            }

        ?>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Confondus</th>
                        <th>Complémentaires</th>
                        <th>Forfaitaires</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Nombre d'APREs</th>
                        <td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'nombre_apres' );?></td>
                        <td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'nombre_apres_c' );?></td>
                        <td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'nombre_apres_f' );?></td>
                    </tr>
                    <tr>
                        <th>Nombre de bénéficiaires</th>
                        <td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'nombre_beneficiaires' );?></td>
                        <td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'nombre_beneficiaires_c' );?></td>
                        <td class="number"><?php echo Set::classicExtract( $detailsEnveloppe, 'nombre_beneficiaires_f' );?></td>
                    </tr>
                    <tr>
                        <th>Consommation de l'enveloppe</th>
                        <td class="number"><?php echo $locale->money( Set::classicExtract( $detailsEnveloppe, 'montantconsomme' ) );?></td>
                        <td class="number"><?php echo $locale->money( Set::classicExtract( $detailsEnveloppe, 'montantconsomme_c' ) );?></td>
                        <td class="number"><?php echo $locale->money( Set::classicExtract( $detailsEnveloppe, 'montantconsomme_f' ) );?></td>
                    </tr>
                </tbody>
            </table>
           <table id="searchResults" >
            <thead>
                <tr>
                    <th>Liste Bénéficiaire</th>
                    <th>Sexe</th>
                    <th>Age</th>
                    <th>Domiciliation</th>
                    <th>Montants des aides</th>
                    <th>Nature des aides</th>
                    <th>Nature de la reprise</th>
                    <th>Secteur professionnel</th>
                    <th>Statut de l'APRE</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $even = true;
                $montantTotal = 0;
            ?>
            <?php foreach( $apres as $index => $apre ):?>
                <?php
// debug($apre);
                    ///Calcul de l'age des bénéficiaires
                    if( !empty( $apre ) ){
                        $dtnai = Set::classicExtract( $apre, 'Personne.dtnai' );
                        $today = ( date( 'Y' ) );
                        if( !empty( $dtnai ) ){
                            $age = ($today - $dtnai);
                        }
                    }


                    ///récupération des aides liées à l'APRE
                    $aidesApre = array();
                    $mtforfait = null;
                    $naturesaide = Set::classicExtract( $apre, 'Apre.Natureaide' );

                    foreach( $naturesaide as $natureaide => $nombre ) {
                        if( $nombre > 0 ) {
                            $aidesApre[] = h( Set::classicExtract( $natureAidesApres, $natureaide ) );
                        }
                    }

                    /**
                    **  Mise en place de l'impossibilité de modifier/relancer/imprimer les APREs forfaitaires
                    **  +
                    **  Conditionnement des éléments à afficher selon le statut de l'APRE
                    **/
                    $statutApre = Set::classicExtract( $apre, 'Apre.statutapre' );
                    if( $statutApre == 'C' ){
                        $mtforfait = $mtforfait;
                    }
                    else if( $statutApre == 'F' ) {
                        $mtforfait = Set::classicExtract( $apre, 'Apre.mtforfait' );
                    }
// debug($apre);
                    echo $html->tableCells(
                        array(
                            h( Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
                            h( Set::enum( Set::classicExtract( $apre, 'Personne.sexe' ), $sexe ) ),
                            h( $age ),
                            h( Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
                            h( $locale->money( Set::classicExtract( $apre, 'Apre.mtforfait' ) + Set::classicExtract( $apre, 'Apre.montantaides' ) ) ),
                            ( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ),
                            h( Set::enum( Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ), $options['activitebeneficiaire'] ) ),
                            h( Set::enum( Set::classicExtract( $apre, 'Apre.secteuractivite' ), $sect_acti_emp ) ),
                            h( Set::enum( $statutApre , $options['statutapre'] ) ),
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );

                    ///Nb total des montants versés
                    $montantTotal += $mtforfait;




// debug($apre);
//                             if( Set::extract( $apres, ( $index - 1 ).'.Dossier.numdemrsa' ) != Set::extract( $apre, 'Dossier.numdemrsa' ) ) {
//                             $rowspan = 1;
//                             for( $i = ( $index + 1 ) ; $i < count( $apres ) ; $i++ ) {
//                                 if( Set::extract( $apre, 'Dossier.numdemrsa' ) == Set::extract( $apres, $i.'.Dossier.numdemrsa' ) )
//                                     $rowspan++;
//                             }
//                             if( $rowspan == 1 ) {
//                                 echo $html->tableCells(
//                                     array(
//                                         h( Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ),
//                                         h( Set::enum( Set::classicExtract( $apre, 'Personne.sexe' ), $sexe ) ),
//                                         h( $age ),
//                                         h( Set::classicExtract( $apre, 'Adresse.locaadr' ) ),
//                                         h( $locale->money( $mtforfait ) ),
//                                         ( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ),
//                                         h( Set::enum( Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ), $options['activitebeneficiaire'] ) ),
//                                         h( Set::enum( Set::classicExtract( $apre, 'Apre.secteuractivite' ), $sect_acti_emp ) ),
//                                         h( Set::enum( $statutApre , $options['statutapre'] ) ),
//                                     ),
//                                     array( 'class' => ( $even ? 'even' : 'odd' ) ),
//                                     array( 'class' => ( !$even ? 'even' : 'odd' ) )
//                                 );
//                             }
//                             // Nouvelle entrée avec rowspan
//                             else {
//                                 echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
//                                         <td rowspan="'.$rowspan.'">'.h( Set::enum( Set::classicExtract( $apre, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $apre, 'Personne.nom' ).' '.Set::classicExtract( $apre, 'Personne.prenom' ) ).'</td>
//                                         <td rowspan="'.$rowspan.'">'.h( Set::enum( Set::classicExtract( $apre, 'Personne.sexe' ), $sexe ) ).'</td>
//                                         <td rowspan="'.$rowspan.'">'.h( $age ).'</td>
//                                         <td rowspan="'.$rowspan.'">'.Set::classicExtract( $apre, 'Adresse.locaadr' ).'</td>
//
//                                         <td>'.h( $locale->money( $mtforfait ) ).'</td>
//                                         <td>'.( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ).'</td>
//                                         <td>'.h( Set::enum( Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ), $options['activitebeneficiaire'] ) ).'</td>
//                                         <td>'.h( Set::enum( Set::classicExtract( $apre, 'Apre.secteuractivite' ), $sect_acti_emp ) ).'</td>
//                                         <td>'.h( Set::enum( $statutApre , $options['statutapre'] )  ).'</td>
//                                     </tr>';
//                             }
//                         }
//                         // Suite avec rowspan
//                         else {
//                             echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
//                                     <td>'.h( $locale->money( $mtforfait ) ).'</td>
//                                         <td>'.( empty( $aidesApre ) ? null :'<ul><li>'.implode( '</li><li>', $aidesApre ).'</li></ul>' ).'</td>
//                                         <td>'.h( Set::enum( Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ), $options['activitebeneficiaire'] ) ).'</td>
//                                         <td>'.h( Set::enum( Set::classicExtract( $apre, 'Apre.secteuractivite' ), $sect_acti_emp ) ).'</td>
//                                         <td>'.h( Set::enum( $statutApre , $options['statutapre'] )  ).'</td>
//                                 </tr>';
//                         }
                ?>
            <?php endforeach; ?>

            </tbody>
        </table>

        <ul class="actionMenu">
            <li><?php
                echo $html->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
                );
            ?></li>
        </ul>
        <?php echo $pagination;?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif;?>