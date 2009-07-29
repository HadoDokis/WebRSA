<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Paiement des allocations';?>

<h1><?php echo $this->pageTitle;?></h1>

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

   $mois = strftime('%B %Y', strtotime( $infosfinancieres[0]['Infofinanciere']['moismoucompta'] ) ); ///FIXME: enlever ce saleté de 0
?>

<?php echo $form->create( 'Infosfinancieres', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
            <?php echo $form->input( 'Filtre.moismoucompta', array( 'label' => 'Recherche des paiements pour le mois de ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) ) );?>
    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $infosfinancieres ) ):?>
   <div class="submit noprint">
        <!-- <?php echo $form->button( 'Imprimer cette page', array( 'onclick' => 'printit();' ) );?> -->
    </div>
    <h2 class="noprint">Liste des allocations pour le mois de <?php echo isset( $mois ) ? $mois : null ; ?></h2>

    <?php if( is_array( $infosfinancieres ) && count( $infosfinancieres ) > 0  ):?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>N° Dossier</th>
                    <th>N° CAF </th>
                    <th>Nom/Prénom allocataire</th>
                    <th>Nom/prénom bénéficiaire</th>
                    <th>Date de naissance de l'allocataire</th>
                    <th>Type d'allocation</th>
                    <th>Montant de l'allocation</th>
                    <th colspan="2" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $infosfinancieres as $infofinanciere ):?>
                    <?php
                        echo $html->tableCells(
                            array(
                                h( $infofinanciere['Dossier']['numdemrsa'] ),
                                h( $infofinanciere['Dossier']['matricule'] ),
                                h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
                                h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
                                h( date_short( $infofinanciere['Personne']['dtnai'] ) ),
                                h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
                                h( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
//                                 h( strftime('%B %Y', strtotime( $infofinanciere['Infofinanciere']['moismoucompta'] ) ) ) ,
                                $html->viewLink(
                                    'Voir les informations financières',
                                    array( 'controller' => 'infosfinancieres', 'action' => 'view', $infofinanciere['Infofinanciere']['id']),
                                    $permissions->check( 'infosfinancieres', 'view' )
                                ),
//                                 $html->editLink(
//                                     'Éditer les informations financières ',
//                                     array( 'controller' => 'infosfinancieres', 'action' => 'edit', $infofinanciere['Infofinanciere']['id'] ),
//                                     $permissions->check( 'infosfinancieres', 'edit' )
//                                 )

                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
       <!-- <ul class="actionMenu">
            <?php/*
                echo $html->printLink(
                    'Imprimer le tableau',
                    Set::merge(
                        array(
                            'controller' => 'gedooos',
                            'action'     => 'notifications_cohortes'
                        ),
                        array_unisize( $this->data )
                    )
                );
            ?>

            <?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    Set::merge(
                        array(
                            'controller' => 'cohortes',
                            'action' => 'exportcsv'
                        ),
                        array_unisize( $this->data )
                    )
                );
            */?>
        </ul> -->
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>