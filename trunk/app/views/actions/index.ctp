<?php $this->pageTitle = 'Paramétrage des actions d\'insertion';?>
<?php echo $xform->create( 'Action' );?>
<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'actions', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Actions d'insertion</h2>
        <table>
        <thead>
            <tr>
                <th>Code de l'action</th>
                <th>Libellé de l'action</th>
                <th>Type d'action</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $actions as $action ):?>
                <?php echo $xhtml->tableCells(
                            array(
                                h( $action['Action']['code'] ),
                                h( $action['Action']['libelle'] ),
                                h( $action['Typeaction']['libelle'] ),
                                $xhtml->editLink(
                                    'Éditer l\'action',
                                    array( 'controller' => 'actions', 'action' => 'edit', $action['Action']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer l\'action',
                                    array( 'controller' => 'actions', 'action' => 'delete', $action['Action']['id'] ), $permissions->check( 'actions', 'delete' )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                ?>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
    <div class="submit">
        <?php
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>

<div class="clearer"><hr /></div>
<?php echo $form->end();?>