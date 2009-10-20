<?php $this->pageTitle = 'Paramétrage des décisions de PDO';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'decisionspdos', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Décision de PDO</h2>
        <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $decisionspdos as $decisionpdo ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $decisionpdo['Decisionpdo']['libelle'] ),
                                $html->editLink(
                                    'Éditer la décision de PDO ',
                                    array( 'controller' => 'decisionspdos', 'action' => 'edit', $decisionpdo['Decisionpdo']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer la décision de PDO ',
                                    array( 'controller' => 'decisionspdos', 'action' => 'delete', $decisionpdo['Decisionpdo']['id'] )
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