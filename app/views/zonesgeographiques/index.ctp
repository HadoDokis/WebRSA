<?php $this->pageTitle = 'Paramétrage de zone géographique';?>
<?php echo $xform->create( 'Zonegeographique' );?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
                'Ajouter',
                array( 'controller' => 'zonesgeographiques', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Zone géographique</h2>
        <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Code insee</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $zones as $zone ):?>
                <?php echo $xhtml->tableCells(
                            array(
                                h( $zone['Zonegeographique']['libelle'] ),
                                h( $zone['Zonegeographique']['codeinsee'] ),
                                $xhtml->editLink(
                                    'Éditer la zone géographique ',
                                    array( 'controller' => 'zonesgeographiques', 'action' => 'edit', $zone['Zonegeographique']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer la zone géographique ',
                                    array( 'controller' => 'zonesgeographiques', 'action' => 'delete', $zone['Zonegeographique']['id'] )
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