<?php  $this->pageTitle = 'Dossier de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un contrat d\'insertion';
    }
    else {
        $this->pageTitle = 'Contrat d\'insertion ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php  echo 'Contrat d\'insertion  ';?></h1>
    <?php if( empty( $contratsinsertion ) ):?>
        <p class="notice">Cette personne ne possède pas encore de contrat d'insertion.</p>
    <?php  endif;?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter un contrat d\'insertion',
                array( 'controller' => 'contratsinsertion', 'action' => 'add', $personne_id )
            ).' </li>';
        ?>
    </ul>
<?php if( !empty( $contratsinsertion ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>N° Contrat</th>
                <th>Type contrat</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Décision</th>
                <th colspan="5" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $contratsinsertion as $contratinsertion ):?>
                <?php
                    echo $html->tableCells(
                        array(
                            h( $contratinsertion['Contratinsertion']['id'] ),
                            h( $contratinsertion['Typocontrat']['lib_typo'] ),
                            h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
                            h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
                            //h( $decision_ci[$contratinsertion['Contratinsertion']['decision_ci']].date_short( $contratinsertion['Contratinsertion']['datevalidation_ci'] ) ),
                            $html->actionsLink(
                                'Actions pour le contrat d\'insertion',
                                array( 'controller' => 'actionsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['id'])
                            ),
                            $html->viewLink(
                                'Voir le contrat d\'insertion',
                                array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id'])
                            ),
                            $html->editLink(
                                'Éditer le contrat d\'insertion ',
                                array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] )
                            ),
                            $html->printLink(
                                'Imprimer le contrat d\'insertion',
                                array( 'controller' => 'gedooos', 'action' => 'contratinsertion', $contratinsertion['Contratinsertion']['id'] )
                            )/*,
                            $html->deleteLink(
                                'Éditer le contrat d\'insertion ',
                                array( 'controller' => 'contratsinsertion', 'action' => 'delete', $contratinsertion['Contratinsertion']['id'] )
                            )*/

                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php  endif;?>


</div>
<div class="clearer"><hr /></div>
