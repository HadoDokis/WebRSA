<?php $this->pageTitle = 'Paramétrage des types de notification';?>
<?php echo $xform->create( 'Typenotifpdo' );?>
<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'typesnotifspdos', 'action' => 'add' )
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
                <?php foreach( $typesnotifspdos as $typenotifpdo ):?>
                <?php echo $xhtml->tableCells(
                            array(
                                h( $typenotifpdo['Typenotifpdo']['libelle'] ),
                                h( $typenotifpdo['Typenotifpdo']['modelenotifpdo'] ),
                                $xhtml->editLink(
                                    'Éditer le type de PDO ',
                                    array( 'controller' => 'typesnotifspdos', 'action' => 'edit', $typenotifpdo['Typenotifpdo']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer le type de PDO ',
                                    array( 'controller' => 'typesnotifspdos', 'action' => 'delete', $typenotifpdo['Typenotifpdo']['id'] )
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