<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche d\'APREs';?>

<h1>Etat des demandes d'APRE</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDatedemandeapre', $( 'FiltreDatedemandeapreFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'FiltreDaterelance', $( 'FiltreDaterelanceFromDay' ).up( 'fieldset' ), false );

    });
</script>

<?php

    if( isset( $apres ) ) {
        $paginator->options( array( 'url' => $this->params['named'] ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= $paginator->numbers();
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }

?>
<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Critereapre' ).toggle(); return false;" )
        ).'</li></ul>';
    }

?>

<?php
    echo $xform->create( 'Critereapre', array( 'url'=> Router::url( null, true ), 'id' => 'Critereapre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $xform->input( 'Filtre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.numdemrsa', array( 'label' => 'N° dossier RSA ', 'type' => 'text', 'maxlength' => 11 ) );?>
        <?php echo $form->input( 'Filtre.matricule', array( 'label' => 'N° CAF ', 'type' => 'text'/*, 'maxlength' => 11*/ ) );?>
        <?php echo $xform->input( 'Filtre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Filtre.nir', array( 'label' => 'NIR ', 'maxlength' => 15 ) );?>
    </fieldset>
    <fieldset>
        <legend>Recherche par demande APRE</legend>
            <?php echo $xform->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
            <?php echo $xform->input( 'Filtre.datedemandeapre', array( 'label' => 'Filtrer par date de demande APRE', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de la saisie de la demande</legend>
                <?php
                    $datedemandeapre_from = Set::check( $this->data, 'Filtre.datedemandeapre_from' ) ? Set::extract( $this->data, 'Filtre.datedemandeapre_from' ) : strtotime( '-1 week' );
                    $datedemandeapre_to = Set::check( $this->data, 'Filtre.datedemandeapre_to' ) ? Set::extract( $this->data, 'Filtre.datedemandeapre_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Filtre.datedemandeapre_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_from ) );?>
                <?php echo $xform->input( 'Filtre.datedemandeapre_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_to ) );?>
            </fieldset>
                <?php echo $xform->enum( 'Filtre.eligibiliteapre', array(  'label' => 'Eligibilité de l\'APRE', 'options' => $options['eligibiliteapre'] ) );?>

             <?php echo $xform->enum( 'Filtre.typedemandeapre', array(  'label' => 'Type de demande', 'options' => $options['typedemandeapre'] ) );?>
             <?php echo $xform->enum( 'Filtre.activitebeneficiaire', array(  'label' => 'Activité du bénéficiaire', 'options' => $options['activitebeneficiaire'] ) );?>
            <?php echo $xform->enum( 'Filtre.natureaidesapres', array(  'label' => 'Nature de l\'aide', 'options' => $natureAidesApres, 'empty' => true ) );?>

            <?php echo $xform->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
            <?php
                if( Configure::read( 'CG.cantons' ) ) {
                    echo $xform->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
                }
            ?>
    </fieldset>
    <fieldset>
        <legend>Recherche par Relance</legend>
        <?php echo $xform->input( 'Filtre.daterelance', array( 'label' => 'Filtrer par date de relance', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de la saisie de la relance</legend>
                <?php
                    $daterelance_from = Set::check( $this->data, 'Filtre.daterelance_from' ) ? Set::extract( $this->data, 'Filtre.daterelance_from' ) : strtotime( '-1 week' );
                    $daterelance_to = Set::check( $this->data, 'Filtre.daterelance_to' ) ? Set::extract( $this->data, 'Filtre.daterelance_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Filtre.daterelance_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_from ) );?>
                <?php echo $xform->input( 'Filtre.daterelance_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_to ) );?>
            </fieldset>

            <?php echo $xform->enum( 'Filtre.etatdossierapre', array(  'label' => 'Etat du dossier APRE', 'options' => $options['etatdossierapre'] ) );?>
            <?php echo $xform->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>

<!-- Résultats -->
<?php if( isset( $apres ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>
    <?php echo $pagination;?>
    <?php if( is_array( $apres ) && count( $apres ) > 0  ):?>

        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° Dossier RSA', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'N° demande APRE', 'Apre.numeroapre' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' );?></th>
                    <th><?php echo $paginator->sort( 'Eligibilité', 'Apre.etatdossierapre' );?></th>
                    <th><?php echo $paginator->sort( 'Etat du dossier APRE', 'Apre.etatdossierapre' );?></th>
                    <th><?php echo $paginator->sort( 'Date de relance', 'Relanceapre.daterelance' );?></th>
                    <th><?php echo $paginator->sort( 'Date comité examen', 'Comiteapre.datecomite' );?></th>

                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $apres as $index => $apre ):?>
                    <?php
                        $title = $apre['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° CAF</th>
                                    <td>'.$apre['Dossier']['matricule'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $apre['Personne']['dtnai'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$apre['Adresse']['numcomptt'].'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.$apre['Personne']['nir'].'</td>
                                </tr>
                            </tbody>
                        </table>';

                        $aidesApre = array();
                        $naturesaide = Set::classicExtract( $apre, 'Apre.Natureaide' );
                        foreach( $naturesaide as $natureaide => $nombre ) {
                            if( $nombre > 0 ) {
                                $aidesApre[] = h( Set::classicExtract( $natureAidesApres, $natureaide ) );
                            }
                        }

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $apre, 'Dossier.numdemrsa' ) ),
                                h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                                h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ),
                                h( $apre['Adresse']['locaadr'] ),
                                h( $locale->date( 'Date::short', Set::extract( $apre, 'Apre.datedemandeapre' ) ) ),
                                h( Set::enum( Set::classicExtract( $apre, 'Apre.eligibiliteapre' ), $options['eligibiliteapre'] ) ),
                                h( Set::enum( Set::classicExtract( $apre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] ) ),
                                h( $locale->date( 'Date::short', Set::classicExtract( $apre, 'Relanceapre.daterelance' ) ) ),
                                h( $locale->date( 'Date::short', Set::classicExtract( $apre, 'Comiteapre.datecomite' ) ) ),
                                array(
                                    $html->viewLink(
                                        'Voir le dossier « '.$title.' »',
                                        array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $apre['Apre']['personne_id'] )
                                    ),
                                    array( 'class' => 'noprint' )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
                            ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php echo $pagination;?>
        <ul class="actionMenu">
            <li><?php
                echo $html->printLinkJs(
                    'Imprimer le tableau',
                    array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
                );
            ?></li>
            <li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'criteresapres', 'action' => 'exportcsv', $this->action, implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>


    <?php else:?>
        <p>Vos critères n'ont retourné aucune demande d'APRE.</p>
    <?php endif?>

<?php endif?>