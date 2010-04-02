<?php $this->pageTitle = 'Paramétrage des types de PDO';?>
<?php echo $xform->create( 'Typepdo' );?>
<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'typespdos', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Type de PDO</h2>
        <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach( $typespdos as $typepdo ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typepdo['Typepdo']['libelle'] ),
                                $html->editLink(
                                    'Éditer le type de PDO ',
                                    array( 'controller' => 'typespdos', 'action' => 'edit', $typepdo['Typepdo']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le type de PDO ',
                                    array( 'controller' => 'typespdos', 'action' => 'delete', $typepdo['Typepdo']['id'] )
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
            echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>