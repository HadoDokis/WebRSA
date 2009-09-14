<?php  echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par Orientation';?>

<h1>Recherche par Orientation</h1>

<script type="text/javascript">
    function toutCocher() {
        $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
            $( checkbox ).checked = true;
        });
    }

    function toutDecocher() {
        $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
            $( checkbox ).checked = false;
        });
    }

    document.observe("dom:loaded", function() {
        Event.observe( 'toutCocher', 'click', toutCocher );
        Event.observe( 'toutDecocher', 'click', toutDecocher );
    });
</script>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
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
        <?php echo $form->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php echo $form->input( 'Filtre.dtdemrsa', array( 'label' => __( 'dtdemrsa', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => date( 'Y' ) - 10, 'empty' => true ) );?>
        <?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.typeorient_id', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'select' , 'options' => $typeorient, 'empty' => true ) );?>
        <?php echo $form->input( 'Filtre.structurereferente_id', array( 'label' => 'Nom de la structure', 'type' => 'select' , 'options' => $sr, 'empty' => true  ) );?>
        <?php echo $form->input( 'Filtre.statut_orient', array( 'label' => 'Statut de l\'orientation', 'type' => 'select', 'options' => $statuts, 'empty' => true ) );?>
         <?php echo $form->input( 'Filtre.serviceinstructeur_id', array( 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $orients ) ):?>
    <div class="submit noprint">
        <?php echo $form->button( 'Imprimer cette page', array( 'onclick' => 'printit();' ) );?>
    </div>
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
                    <th><?php echo $paginator->sort( 'Date d\'orientation', 'Orientstruct.date_propo' );?></th>
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
                                h( date_short( $orient['Orientstruct']['date_propo'] ) ),
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
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>