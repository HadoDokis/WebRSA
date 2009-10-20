<?php $this->pageTitle = 'Paramétrage des types de notification';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'typesnotifs', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Type de Notification</h2>
        <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Modèle de notification de PDO</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach( $typesnotifs as $typenotif ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typenotif['Typenotif']['libelle'] ),
                                h( $typenotif['Typenotif']['modelenotifpdo'] ),
                                $html->editLink(
                                    'Éditer le type de PDO ',
                                    array( 'controller' => 'typesnotifs', 'action' => 'edit', $typenotif['Typenotif']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le type de PDO ',
                                    array( 'controller' => 'typesnotifs', 'action' => 'delete', $typenotif['Typenotif']['id'] )
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