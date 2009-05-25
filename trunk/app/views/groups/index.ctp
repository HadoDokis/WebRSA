<?php $this->pageTitle = 'Paramétrage des Groupes d\'utilisateurs';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'groups', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Groupes d'utilisateurs</h2>
        <table>
        <thead>
            <tr>
                 <th>Nom du groupe</th>
		 <th>Parent</th>
                <th colspan="1" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $groups as $group ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $group['Group']['name'] ),
				h( $group['Group']['parent_id'] ),
                                $html->editLink(
                                    'Éditer le contrat d\'insertion ',
                                    array( 'controller' => 'groups', 'action' => 'edit', $group['Group']['id'] )
                                ),
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