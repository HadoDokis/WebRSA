<?php  $this->pageTitle = 'Dossier de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un CER';
    }
    else {
        $this->pageTitle = 'CER ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php  echo 'CER  ';?></h1>

        <?php if( empty( $contratsinsertion ) ):?>
            <p class="notice">Cette personne ne possède pas encore de CER.</p>
        <?php endif;?>

        <?php if( $permissions->check( 'contratsinsertion', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter un CER',
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
                    <th>Rang contrat</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Décision</th>
                    <th colspan="6" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $contratsinsertion as $contratinsertion ):?>
                    <?php
                        $isValid = Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' );
                        $block = true;
                        if( $isValid == 'V'  ){
                            $block = false;
                        }
                        else{
                            $block;
                        }

                        echo $html->tableCells(
                            array(
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci ) ),
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ),  $options['num_contrat'] ) ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
                                h( Set::enum( Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' ), $decision_ci ).' '.$locale->date( 'Date::short', Set::extract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) ) ),
                                $html->validateLink(
                                    'Valider le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'valider', $contratinsertion['Contratinsertion']['id'] )
                                ),
                                $html->actionsLink(
                                    'Actions pour le CER',
                                    array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'actionsinsertion', 'index' )
                                ),
                                $html->viewLink(
                                    'Voir le CER',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id']),
                                    $permissions->check( 'contratsinsertion', 'view' )
                                ),
                                $html->editLink(
                                    'Éditer le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] ),
//                                     array(
                                        $block,
                                        $permissions->check( 'contratsinsertion', 'edit' )
//                                     )
                                ),
                                $html->printLink(
                                    'Imprimer le CER',
                                    array( 'controller' => 'gedooos', 'action' => 'contratinsertion', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'gedooos', 'contratinsertion' )
                                ),
                                $html->deleteLink(
                                    'Supprimer le CER ',
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
