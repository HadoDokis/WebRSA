<?php $this->pageTitle = 'Paramétrage des objets du rendez-vous';?>
<?php echo $xform->create( 'Rendezvous' );?>
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
        <h2>Table Objet du rendez-vous</h2>
        <table>
        <thead>
            <tr>
                <th>Objet du rendez-vous</th>
                <th>Modèle de notification de RDV</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $typesrdv as $typerdv ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typerdv['Typerdv']['libelle'] ),
                                h( $typerdv['Typerdv']['modelenotifrdv'] ),
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
    <div class="submit">
        <?php
            echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>