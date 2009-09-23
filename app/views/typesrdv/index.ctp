<?php $this->pageTitle = 'Paramétrage des types de rendez-vous';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'typesrdv', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Type de rendez-vous</h2>
        <table>
        <thead>
            <tr>
                <th>Type de rendez-vous</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $typesrdv as $typerdv ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typerdv['Typerdv']['libelle'] ),
                                $html->editLink(
                                    'Éditer le type d\'action',
                                    array( 'controller' => 'typesrdv', 'action' => 'edit', $typerdv['Typerdv']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le type d\'action',
                                    array( 'controller' => 'typesrdv', 'action' => 'delete', $typerdv['Typerdv']['id'] )
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