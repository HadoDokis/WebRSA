<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des indus';?>

<h1>Gestion des Indus</h1>


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

<?php echo $form->create( 'Cohorteindu', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( is_array( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche d'Indu</legend>
            <?php echo $form->input( 'Filtre.natpfcre', array( 'label' => 'Type d\'indu', 'type' => 'select', 'options' => $natpfcre, 'empty' => true ) );?>
            <?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Filtre.nom', array( 'label' => 'Nom de l\'allocataire', 'type' => 'text' ) );?>
            <?php echo $form->input( 'Filtre.typeparte', array( 'label' => 'Suivi', 'type' => 'select', 'options' => $typeparte, 'empty' => true ) ); ?>
             <?php echo $form->input( 'Filtre.structurereferente_id', array( 'label' => 'Structure référente', 'type' => 'select', 'options' => $sr , 'empty' => true, 'style' => 10)  ); ?> 
            <?php 
                echo $form->select( 'Filtre.compare', array( 'comparison' => '', '<','>','<=','>=' ) );
                echo $form->input( 'Filtre.mtmoucompta', array( 'label' => 'Montant de l\'indu', 'type' => 'text' ) );
            ?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $cohorteindu ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $cohorteindu ) && count( $cohorteindu ) > 0 ):?>
        <?php /*require( 'index.pagination.ctp' )*/?>
        <?php echo $form->create( 'GestionIndu', array( 'url'=> Router::url( null, true ) ) );?>
            <table id="searchResults" class="tooltips_oupas">
                <thead>
                    <tr>
                        <th>N° Dossier</th>
                        <th>Nom de l'allocataire</th>
                        <th>Suivi</th>
                        <th>Situation des droits</th>
                        <th>Date indus</th>
                        <th>Montant initial de l'indu</th>
                        <th>Montant transféré CG</th>
                        <th>Remise CG</th>
                        <th>Montant remboursé</th>
                        <th class="action">Action</th>
                        <th class="innerTableHeader">Informations complémentaires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $cohorteindu as $index => $indu ):?>
                        <?php
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $indu['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $indu['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $indu['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $indu['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                            $title = $indu['Dossier']['numdemrsa'];

                            echo $html->tableCells(
                                array(
                                    h( $indu['Dossier']['numdemrsa'] ),
                                    h( $indu['Personne']['nom'].' '.$indu['Personne']['prenom'] ),
                                    h( $indu['Dossier']['typeparte'] ), //h( $typeparte[$indu['Dossier']['typeparte']] ),
                                    h( $etatdosrsa[$indu['Situationdossierrsa']['etatdosrsa']] ),
                                    h( date_short( $indu['Infofinanciere']['dttraimoucompta'] ) ),
                                    h( $indu['Infofinanciere']['mtmoucompta'] ),
                                    h( $indu['Infofinanciere']['mtmoucompta'] ),
                                    h( $indu['Infofinanciere']['mtmoucompta'] ),
                                    h( $indu['Infofinanciere']['mtmoucompta'] ),
                                    $html->viewLink(
                                        'Voir le contrat « '.$title.' »',
                                        array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier', $indu['Infofinanciere']['id'] )
                                    ),
                                    array( $innerTable, array( 'class' => 'innerTableCell' ) )
                                ),
                            array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                            array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                            );
                        ?>
                    <?php endforeach;?>
                </tbody>
            </table>

    <?php /*require( 'index.pagination.ctp' )*/ ?>

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>