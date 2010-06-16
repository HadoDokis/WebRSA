<?php
    $domain = 'propopdo';
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'propopdo', "Criterespdos::{$this->action}", true )
    )
?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'PropopdoDatereceptionpdo', $( 'PropopdoDatereceptionpdoFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'PropopdoDatedecisionpdo', $( 'PropopdoDatedecisionpdoFromDay' ).up( 'fieldset' ), false );
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

    echo $xform->create( 'Criterespdos', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>
<fieldset>
    <legend>Recherche par date de décision</legend>
        <?php echo $form->input( 'Propopdo.datereceptionpdo', array( 'label' => 'Filtrer par date de réception de la PDO', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de proposition de la décision PDO</legend>
            <?php
                $datereceptionpdo_from = Set::check( $this->data, 'Propopdo.datereceptionpdo_from' ) ? Set::extract( $this->data, 'Propopdo.datereceptionpdo_from' ) : strtotime( '-1 week' );
                $datereceptionpdo_to = Set::check( $this->data, 'Propopdo.datereceptionpdo_to' ) ? Set::extract( $this->data, 'Propopdo.datereceptionpdo_to' ) : strtotime( 'now' );
            ?>
            <?php echo $form->input( 'Propopdo.datereceptionpdo_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datereceptionpdo_from ) );?>
            <?php echo $form->input( 'Propopdo.datereceptionpdo_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datereceptionpdo_to ) );?>
        </fieldset>
</fieldset>
<fieldset>
    <legend>Recherche par date de réception</legend>
        <?php echo $form->input( 'Propopdo.datedecisionpdo', array( 'label' => 'Filtrer par date de décision de la PDO', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de proposition de la décision PDO</legend>
            <?php
                $datedecisionpdo_from = Set::check( $this->data, 'Propopdo.datedecisionpdo_from' ) ? Set::extract( $this->data, 'Propopdo.datedecisionpdo_from' ) : strtotime( '-1 week' );
                $datedecisionpdo_to = Set::check( $this->data, 'Propopdo.datedecisionpdo_to' ) ? Set::extract( $this->data, 'Propopdo.datedecisionpdo_to' ) : strtotime( 'now' );
            ?>
            <?php echo $form->input( 'Propopdo.datedecisionpdo_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_from ) );?>
            <?php echo $form->input( 'Propopdo.datedecisionpdo_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datereceptionpdo_to ) );?>
        </fieldset>
</fieldset>
    <?php
        ///Formulaire de recherche pour les CUIs
        echo $default->search(
            array(
                'Propopdo.decisionpdo_id' => array( 'label' => __d( 'propopdo', 'Propopdo.decisionpdo_id', true ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
                'Propopdo.datereceptionpdo' => array( 'label' => __d( 'propopdo', 'Propopdo.datereceptionpdo', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 ),
                'Propopdo.originepdo_id' => array( 'label' => __d( 'propopdo', 'Propopdo.originepdo_id', true ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
                'Propopdo.motifpdo' => array( 'label' => __d( 'propopdo', 'Propopdo.motifpdo', true ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true  ),
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
<?php $pagination = $xpaginator->paginationBlock( 'Propopdo', $this->passedArgs ); ?>

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
                    <th><?php echo $paginator->sort( 'Proposition de décision', 'Propopdo.decisionpdo_id' );?></th>
                    <th><?php echo $paginator->sort( 'Origine de la PDO', 'Propopdo.originepdo_id' );?></th>
                    <th><?php echo $paginator->sort( 'Motif de la PDO', 'Propopdo.motifpdo' );?></th>
                    <th><?php echo $paginator->sort( 'Date du contrat', 'Propopdo.datereceptionpdo' );?></th>
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
                                    <th>Etat du droit</th>
                                    <td>'.Set::enum( Set::classicExtract( $criterepdo, 'Situationdossierrsa.etatdosrsa' ),$criterepdo ).'</td>
                                </tr>
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
                                h( Set::enum( Set::classicExtract( $criterepdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ) ),
                                h( Set::enum( Set::classicExtract( $criterepdo, 'Propopdo.originepdo_id' ), $originepdo ) ),
                                h( Set::enum( Set::classicExtract( $criterepdo, 'Propopdo.motifpdo' ), $motifpdo ) ),
                                h( $locale->date( 'Locale->date',  Set::classicExtract( $criterepdo, 'Propopdo.datereceptionpdo' ) ) ),
                                $html->viewLink(
                                    'Voir',
                                    array( 'controller' => 'propospdos', 'action' => 'index', Set::classicExtract( $criterepdo, 'Propopdo.personne_id' ) )
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