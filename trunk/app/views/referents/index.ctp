<?php $this->pageTitle = 'Paramétrage des référents';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'referents', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Référents</h2>
        <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>N° téléphone</th>
                <th>Email</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $referents as $referent ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $referent['Referent']['nom'] ),
                                h( $referent['Referent']['prenom'] ),
                                h( $referent['Referent']['numero_poste'] ),
                                h( $referent['Referent']['email'] ),
                                $html->editLink(
                                    'Éditer le référent',
                                    array( 'controller' => 'referents', 'action' => 'edit', $referent['Referent']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le référent',
                                    array( 'controller' => 'referents', 'action' => 'delete', $referent['Referent']['id'] )
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