<?php  $this->pageTitle = 'Dossier de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un contrat d\'engagement réciproque';
    }
    else {
        $this->pageTitle = 'Contrat d\'engagement réciproque ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php  echo 'Contrat d\'engagement réciproque  ';?></h1>
    <?php if( Configure::read( 'nom_form_ci_cg' )  == 'cg66' ):?>
        <?php if( empty( $persreferent ) ) :?>
            <p class="error">Aucun référent n'est lié au parcours de cette personne.</p>
        <?php endif;?>
    <?php endif;?>
     <!-- <?php /*if( empty( $orientstruct )  ):?>
        <p class="error">Impossible d'ajouter un contrat d'engagement réciproque lorsqu'il n'existe pas d'orientation.</p>

    <?php elseif( !empty( $orientstruct ) && empty( $referents ) ):?>
        <p class="error">Impossible d'ajouter un contrat d'engagement réciproque lorsqu'il n'existe pas de référent lié à la structure d'orientation <?php echo '(' .$sr. ')';?>.</p>
    <?php else:*/?> -->
        <?php if( empty( $contratsinsertion ) ):?>
            <p class="notice">Cette personne ne possède pas encore de contrat d'engagement réciproque.</p>
        <?php endif;?>

        <?php if( $permissions->check( 'contratsinsertion', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter un contrat d\'engagement réciproque',
                        array( 'controller' => 'contratsinsertion', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <!-- <?php /*if( !empty( $contratsinsertion ) ): */?> -->
    <?php if( !empty( $contratsinsertion ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <!-- <th>N° Contrat</th> -->
                    <th>Type contrat</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Décision</th>
                    <th colspan="6" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $contratsinsertion as $contratinsertion ):?>
                    <?php
                        echo $html->tableCells(
                            array(
                                //h( $contratinsertion['Contratinsertion']['id'] ),
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ),  $options['num_contrat'] ) ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
                                h( Set::enum( Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' ), $decision_ci ).' '.$locale->date( 'Date::short', Set::extract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) ) ),
                                $html->validateLink(
                                    'Valider le contrat d\'engagement réciproque ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'valider', $contratinsertion['Contratinsertion']['id'] )
                                ),
                                $html->actionsLink(
                                    'Actions pour le contrat d\'engagement réciproque',
                                    array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'actionsinsertion', 'index' )
                                ),
                                $html->viewLink(
                                    'Voir le contrat d\'engagement réciproque',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id']),
                                    $permissions->check( 'contratsinsertion', 'view' )
                                ),
                                $html->editLink(
                                    'Éditer le contrat d\'engagement réciproque ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'contratsinsertion', 'edit' )
                                ),
                                $html->printLink(
                                    'Imprimer le contrat d\'engagement réciproque',
                                    array( 'controller' => 'gedooos', 'action' => 'contratinsertion', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'gedooos', 'contratinsertion' )
                                ),
                                $html->deleteLink(
                                    'Supprimer le contrat d\'engagement réciproque ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'delete', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'contratsinsertion', 'delete' )
                                )

                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php  endif;?>
    <?php /*endif;*/?>
</div>
<div class="clearer"><hr /></div>
