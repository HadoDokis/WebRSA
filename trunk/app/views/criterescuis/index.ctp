<?php
    $domain = 'criterecui';
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'criterecui', "Criterescuis::{$this->action}", true )
    )
?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CuiDatecontrat', $( 'CuiDatecontratFromDay' ).up( 'fieldset' ), false );
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

    echo $xform->create( 'Criterescuis', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>
<fieldset>
    <legend>Recherche de Contrat Unique d'Insertion</legend>
        <?php echo $form->input( 'Cui.datecontrat', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de saisie du contrat</legend>
            <?php
                $datecontrat_from = Set::check( $this->data, 'Cui.datecontrat_from' ) ? Set::extract( $this->data, 'Cui.datecontrat_from' ) : strtotime( '-1 week' );
                $datecontrat_to = Set::check( $this->data, 'Cui.datecontrat_to' ) ? Set::extract( $this->data, 'Cui.datecontrat_to' ) : strtotime( 'now' );
            ?>
            <?php echo $form->input( 'Cui.datecontrat_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecontrat_from ) );?>
            <?php echo $form->input( 'Cui.datecontrat_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecontrat_to ) );?>
        </fieldset>
</fieldset>
    <?php
        ///Formulaire de recherche pour les CUIs
        echo $default->search(
            array(
                'Cui.convention' => array( 'label' => __d( 'cui', 'Cui.convention', true ), 'type' => 'select', 'options' => $options['convention'] ),
                'Cui.datecontrat' => array( 'label' => __d( 'cui', 'Cui.datecontrat', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 ),
                'Cui.secteur' => array( 'label' => __d( 'cui', 'Cui.secteur', true ), 'type' => 'select', 'options' => $options['secteur'] ),
                'Cui.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                'Cui.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                'Cui.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
            ),
            array(
                'options' => $options
            )
        );
    ?>
<?php echo $xform->end(); ?>
<?php $pagination = $xpaginator->paginationBlock( 'Cui', $this->passedArgs ); ?>

    <?php if( isset( $criterescuis ) ):?>
    <br />
    <h2 class="noprint aere">Résultats de la recherche</h2>

    <?php if( is_array( $criterescuis ) && count( $criterescuis ) > 0  ):?>
        <?php echo $pagination;?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>Nom demandeur</th>
                    <th>Secteur</th>
                    <th>Date du contrat</th>
                    <th>Nom de l'employeur</th>
                    <th colspan="4" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $criterescuis as $criterecui ) {
// debug($criterecui);
                        echo $html->tableCells(
                            array(
                                h( Set::enum( Set::classicExtract( $criterecui, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criterecui, 'Personne.nom' ).' '.Set::classicExtract( $criterecui, 'Personne.prenom' ) ),
                                h( Set::classicExtract( $criterecui, 'Cui.secteur' ) ),
                                h( $locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datecontrat' ) ) ),
                                h( Set::classicExtract( $criterecui, 'Cui.nomemployeur' ) ),
                                $html->viewLink(
                                    'Voir',
                                    array( 'controller' => 'cuis', 'action' => 'index', Set::classicExtract( $criterecui, 'Cui.personne_id' ) )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
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
                    array( 'controller' => 'criterescuis', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun contrat unique d'insertion.</p>
    <?php endif?>
<?php endif?>

<!-- *********************************************************************** -->