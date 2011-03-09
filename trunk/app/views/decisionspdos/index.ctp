<?php $this->pageTitle = 'Paramétrage des décisions de PDO';?>
<?php echo $xform->create( 'Decisionpdo' );?>
<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->addLink(
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
                <th>Ce type clotûre-t-il le dossier ?</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $decisionspdos as $decisionpdo ):
                echo $xhtml->tableCells(
                            array(
                                h( $decisionpdo['Decisionpdo']['libelle'] ),
								( $decisionpdo['Decisionpdo']['clos'] == 'N' ) ? 'Non' : 'Oui',
                                $xhtml->editLink(
                                    'Éditer la décision de PDO ',
                                    array( 'controller' => 'decisionspdos', 'action' => 'edit', $decisionpdo['Decisionpdo']['id'] )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer la décision de PDO ',
                                    array( 'controller' => 'decisionspdos', 'action' => 'delete', $decisionpdo['Decisionpdo']['id'] )
                                )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
            endforeach;?>
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