<?php
    $domain = 'propopdo';
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'propopdo', "Criterespdos::{$this->action}", true )
    )
?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'DossierDatedecisionpdo', $( 'DossierDatedecisionpdoFromDay' ).up( 'fieldset' ), false );
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

    echo $xform->create( 'Criterespdos', array( 'type' => 'post', 'action' => '/nouvelles/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>

<fieldset>
    <legend>Recherche par date de demande RSA</legend>
        <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande RSA', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de demande RSA</legend>
            <?php
                $dtdemrsa_from = Set::check( $this->data, 'Dossier.dtdemrsa_from' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
                $dtdemrsa_to = Set::check( $this->data, 'Dossier.dtdemrsa_to' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_to' ) : strtotime( 'now' );
            ?>
            <?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_to ) );?>
        </fieldset>
</fieldset>
    <?php
        ///Formulaire de recherche pour les CUIs
        echo $default->search(
            array(
                'Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                'Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                'Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
                'Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
                'Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 )
            ),
            array(
                'options' => $options
            )
        );
    ?>
<?php echo $xform->end(); ?>
<?php $pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs ); ?>

    <?php if( isset( $criterespdos ) ):?>
    <br />
    <h2 class="noprint aere">Résultats de la recherche</h2>

    <?php if( is_array( $criterespdos ) && count( $criterespdos ) > 0  ):?>
        <?php echo $pagination;?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Etat du droit', 'Situationdossierrsa.etatdosrsa' );?></th>
                    <th colspan="4" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $criterespdos as $index => $criterepdo ) {
// debug($criterepdo);
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Commune de naissance</th>
                                    <td>'. $criterepdo['Personne']['nomcomnai'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $criterepdo['Personne']['dtnai']).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$criterepdo['Adresse']['numcomptt'].'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.$criterepdo['Personne']['nir'].'</td>
                                </tr>
                                <tr>
                                    <th>N° CAF</th>
                                    <td>'.$criterepdo['Dossier']['matricule'].'</td>
                                </tr>

                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( Set::classicExtract( $criterepdo, 'Dossier.numdemrsa' ) ),
                                h( Set::enum( Set::classicExtract( $criterepdo, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criterepdo, 'Personne.nom' ).' '.Set::classicExtract( $criterepdo, 'Personne.prenom' ) ),
                                h( Set::classicExtract( $etatdosrsa, Set::classicExtract( $criterepdo, 'Situationdossierrsa.etatdosrsa' ) ) ),
                                $html->viewLink(
                                    'Voir',
                                    array( 'controller' => 'propospdos', 'action' => 'index', Set::classicExtract( $criterepdo, 'Personne.id' ) )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
                            ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
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
                    array( 'controller' => 'criterespdos', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php else:?>
        <p>Vos critères n'ont retourné aucune PDO.</p>
    <?php endif?>
<?php endif?>

<!-- *********************************************************************** -->