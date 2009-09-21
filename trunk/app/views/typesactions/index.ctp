<?php $this->pageTitle = 'Paramétrage des types d\'actions d\'insertion';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'typesactions', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Type d'actions d'insertion</h2>
        <table>
        <thead>
            <tr>
                <th>Libellé de l'action</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $typesactions as $typeaction ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typeaction['Typeaction']['libelle'] ),
                                $html->editLink(
                                    'Éditer le type d\'action',
                                    array( 'controller' => 'typesactions', 'action' => 'edit', $typeaction['Typeaction']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le type d\'action',
                                    array( 'controller' => 'typesactions', 'action' => 'delete', $typeaction['Typeaction']['id'] )
                                    //$permissions->check( 'typesactions', 'delete' )
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
<div class="clearer"><hr /></div>