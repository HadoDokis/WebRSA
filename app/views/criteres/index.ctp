<?php  echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par Orientation';?>

<h1>Recherche par Orientation</h1>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CritereDateValid', $( 'CritereDateValidFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'CritereDtdemrsa', $( 'CritereDtdemrsaFromDay' ).up( 'fieldset' ), false );
    });
</script>

<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }
?>

<?php echo $form->create( 'Critere', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par dossier</legend>
        <?php echo $form->input( 'Critere.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php echo $form->input( 'Critere.etatdosrsa', array( 'label' => 'Situation dossier rsa', 'type' => 'select', 'options' => $etatdosrsa, 'empty' => true ) );?>
        <?php echo $form->input( 'Critere.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );?>
        <?php echo $form->input( 'Critere.dtdemrsa', array( 'label' => 'Filtrer par date d\'ouverture de droit', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de demande RSA</legend>
            <?php
                $dtdemrsa_from = Set::check( $this->data, 'Critere.dtdemrsa_from' ) ? Set::extract( $this->data, 'Critere.dtdemrsa_from' ) : strtotime( '-1 week' );
                $dtdemrsa_to = Set::check( $this->data, 'Critere.dtdemrsa_to' ) ? Set::extract( $this->data, 'Critere.dtdemrsa_to' ) : strtotime( 'now' );
            ?>
            <?php echo $form->input( 'Critere.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ) );?>
            <?php echo $form->input( 'Critere.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_to ) );?>
        </fieldset>
    </fieldset>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Critere.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Critere.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Critere.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
        <!-- <?php echo $form->input( 'Critere.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE ', 'type' => 'text' ) );?> -->
        <?php echo $form->input( 'Adresse.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
    </fieldset>
    <fieldset>
        <legend>Recherche par orientation</legend>
        <?php echo $form->input( 'Critere.date_valid', array( 'label' => 'Filtrer par date d\'orientation', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date d'orientation</legend>
                <?php
                    $date_valid_from = Set::check( $this->data, 'Critere.date_valid_from' ) ? Set::extract( $this->data, 'Critere.date_valid_from' ) : strtotime( '-1 week' );
                    $date_valid_to = Set::check( $this->data, 'Critere.date_valid_to' ) ? Set::extract( $this->data, 'Critere.date_valid_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Critere.date_valid_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_valid_from ) );?>
                <?php echo $form->input( 'Critere.date_valid_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_valid_to ) );?>
            </fieldset>

        <?php echo $form->input( 'Critere.typeorient_id', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'select' , 'options' => $typeorient, 'empty' => true ) );?>
        <?php echo $form->input( 'Critere.structurereferente_id', array( 'label' => 'Nom de la structure', 'type' => 'select' , 'options' => $sr, 'empty' => true  ) );?>
        <?php echo $form->input( 'Critere.statut_orient', array( 'label' => 'Statut de l\'orientation', 'type' => 'select', 'options' => $statuts, 'empty' => true ) );?>
         <?php echo $form->input( 'Critere.serviceinstructeur_id', array( 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $orients ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $orients ) && count( $orients ) > 0  ):?>

        <?php require( 'index.pagination.ctp' )?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                 <tr>
                    <th><?php echo $paginator->sort( 'Numéro dossier', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'N° Téléphone', 'Modecontact.numtel' );?></th>
                    <th><?php echo $paginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date d\'ouverture droits', 'Dossier.dtdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Date d\'orientation', 'Orientstruct.date_valid' );?></th>
                    <th><?php echo $paginator->sort( 'Structure référente', 'Structurereferente.lib_struc' );?></th>
                    <th><?php echo $paginator->sort( 'Statut orientation', 'Orientstruct.statut_orient' );?></th>
                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $orients as $index => $orient ):?>
                    <?php
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Etat du droit</th>
                                    <td>'.Set::classicExtract( $etatdosrsa, Set::classicExtract( $orient, 'Situationdossierrsa.etatdosrsa' ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Commune de naissance</th>
                                    <td>'. $orient['Personne']['nomcomnai'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $orient['Personne']['dtnai']).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$orient['Adresse']['numcomptt'].'</td>
                                </tr>
                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( $orient['Dossier']['numdemrsa'] ),
                                h( $orient['Personne']['qual'].' '.$orient['Personne']['nom'].' '.$orient['Personne']['prenom'] ),
                                h( $orient['Modecontact']['numtel'] ),
                                h( $orient['Adresse']['locaadr'] ),
                                h( date_short( $orient['Dossier']['dtdemrsa'] ) ),
                                h( date_short( $orient['Orientstruct']['date_valid'] ) ),
                                h( isset( $sr[$orient['Orientstruct']['structurereferente_id']] ) ? $sr[$orient['Orientstruct']['structurereferente_id']] : null ),
                                h( $orient['Orientstruct']['statut_orient'] ),
                                array(
                                    $html->viewLink(
                                        'Voir le dossier « '.$orient['Dossier']['numdemrsa'].' »',
                                        array( 'controller' => 'personnes', 'action' => 'view', $orient['Personne']['id'] )
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

        <?php if( Set::extract( $paginator, 'params.paging.Orientstruct.count' ) > 65000 ):?>
            <p class="noprint" style="border: 1px solid #556; background: #ffe;padding: 0.5em;"><?php echo $html->image( 'icons/error.png' );?> <strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>
        <?php endif;?>
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
                    array( 'controller' => 'criteres', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
        <?php require( 'index.pagination.ctp' )?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>