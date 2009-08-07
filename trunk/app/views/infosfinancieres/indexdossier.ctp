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


?>


<?php echo $form->create( 'Infosfinancieres', array( 'type' => 'post', 'action' => '/indexdossier/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
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
   <?php $mois = strftime('%B %Y', strtotime( $this->data['Filtre']['moismoucompta']['year'].'-'.$this->data['Filtre']['moismoucompta']['month'].'-01' ) ); ?>

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
                                $locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ),
                                h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
                                $locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
                                $html->viewLink(
                                    'Voir les informations financières',
                                    array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ),
                                    $permissions->check( 'infosfinancieres', 'view' )
                                ),

                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
       <!-- <ul class="actionMenu">
            <?php
                echo $html->printLink(
                    'Imprimer le tableau',
                    array( 'controller' => 'gedooos', 'action' => 'notifications_cohortes' )
                );
            ?>

            <?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'cohortes', 'action' => 'exportcsv' )
                );
            ?>
        </ul> -->
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>