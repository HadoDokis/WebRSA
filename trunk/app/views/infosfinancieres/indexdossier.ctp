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
        <?php echo $form->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php echo $form->input( 'Filtre.moismoucompta', array( 'label' => 'Recherche des paiements pour le mois de ', 'type' => 'date', 'dateFormat' => 'MY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) ) );?>
        <?php echo $form->input( 'Filtre.type_allocation', array( 'label' => 'Type d\'allocation', 'type' => 'select', 'options' => $type_allocation, 'empty' => true ) ); ?>
        <?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire', 'type' => 'text' ) ); ?>
        <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Code INSEE', 'type' => 'text', 'maxlength' => 5 ) ); ?>
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
    <?php /*echo $pagination;*/?>
    <?php require( 'index.pagination.ctp' )?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° Dossier', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'N° CAF', 'Dossier.matricule' );?></th>
                    <th><?php echo $paginator->sort( 'Nom/prénom du bénéficiaire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Date de naissance du bénéficiaire', 'Personne.dtnai' );?></th>
                    <th>Type d'allocation</th>
                    <th>Montant de l'allocation</th>
                    <!-- <th><?php /*echo $paginator->sort( 'Type d\'allocation', 'Infofinanciere.type_allocation' );*/?></th>
                    <th><?php /*echo $paginator->sort( 'Montant de l\'allocation', 'Infofinanciere.mtmoucompta' );*/?></th>
                    <th>N° Dossier</th>
                    <th>N° CAF</th>
                    <th>Nom/Prénom allocataire</th>
                    <th>Date de naissance du bénéficiaire</th>
                    <th>Type d'allocation</th>
                    <th>Montant de l'allocation</th> -->
                    <th class="action noprint">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $even = true;?>
                <?php foreach( $infosfinancieres as $index => $infofinanciere ):?>
                    <?php
                        // Nouvelle entrée
                        if( Set::extract( $infosfinancieres, ( $index - 1 ).'.Dossier.numdemrsa' ) != Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) ) {
                            $rowspan = 1;
                            for( $i = ( $index + 1 ) ; $i < count( $infosfinancieres ) ; $i++ ) {
                                if( Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) == Set::extract( $infosfinancieres, $i.'.Dossier.numdemrsa' ) )
                                    $rowspan++;
                            }
                            if( $rowspan == 1 ) {
                                echo $html->tableCells(
                                    array(
                                        h( $infofinanciere['Dossier']['numdemrsa'] ),
                                        h( $infofinanciere['Dossier']['matricule'] ),
                                        h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
                                        $locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ),
                                        h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
                                        $locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
                                        array(
                                            $html->viewLink(
                                                'Voir les informations financières',
                                                array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ),
                                                $permissions->check( 'infosfinancieres', 'view' )
                                            ),
                                            array( 'class' => 'noprint' )
                                        )
                                    ),
                                    array( 'class' => ( $even ? 'even' : 'odd' ) ),
                                    array( 'class' => ( !$even ? 'even' : 'odd' ) )
                                );
                            }
                            // Nouvelle entrée avec rowspan
                            else {
                                echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
                                        <td rowspan="'.$rowspan.'">'.h( $infofinanciere['Dossier']['numdemrsa'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.h( $infofinanciere['Dossier']['matricule'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ).'</td>
                                        <td rowspan="'.$rowspan.'">'.$locale->date( 'Date::short', $infofinanciere['Personne']['dtnai'] ).'</td>

                                        <td>'.h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]).'</td>
                                        <td>'.$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ).'</td>
                                        <td rowspan="'.$rowspan.'" class="noprint">'. $html->viewLink(
                                            'Voir les informations financières',
                                            array( 'controller' => 'infosfinancieres', 'action' => 'index', $infofinanciere['Infofinanciere']['dossier_rsa_id'] ),
                                            $permissions->check( 'infosfinancieres', 'view' )
                                        ).'</td>
                                    </tr>';
                            }
                        }
                        // Suite avec rowspan
                        else {
                            echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
                                    <td>'.h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]).'</td>
                                    <td>'.$locale->money( $infofinanciere['Infofinanciere']['mtmoucompta'] ).'</td>
                                </tr>';
                        }


                       /* echo $html->tableCells(
                            array(
                                h( $infofinanciere['Dossier']['numdemrsa'] ),
                                h( $infofinanciere['Dossier']['matricule'] ),
//                                 h( $infofinanciere['Personne']['qual'].' '.$infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
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
                        );*/
// debug( 'i => '.Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) );
// debug( 'i + 1 => '.Set::extract( $infosfinancieres, ( $index + 1 ).'.Dossier.numdemrsa' ) );
                        if( Set::extract( $infosfinancieres, ( $index + 1 ).'.Dossier.numdemrsa' ) != Set::extract( $infofinanciere, 'Dossier.numdemrsa' ) ) {
                            $even = !$even;
                        }
// var_dump( $even );
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if( Set::extract( $paginator, 'params.paging.Infofinanciere.count' ) > 65000 ):?>
            <p style="border: 1px solid #556; background: #ffe;padding: 0.5em;"><?php echo $html->image( 'icons/error.png' );?> <strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>
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
                    array( 'controller' => 'infosfinancieres', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php require( 'index.pagination.ctp' )?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>

<?php endif?>