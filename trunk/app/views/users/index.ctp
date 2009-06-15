<?php $this->pageTitle = 'Paramétrage des utilisateurs';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'users', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Utilisateur</h2>
        <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Login</th>
                <th>Date de naissance</th>
                <th>N° téléphone</th>
                <th>Date début habilitation</th>
                <th>Date fin habilitation</th>
                <th>Groupe d'utilisateur</th>
                <th>Service instructeur</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $users as $user ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $user['User']['nom'] ),
                                h( $user['User']['prenom'] ),
                                h( $user['User']['username'] ),
                                h( date_short( $user['User']['date_naissance'] ) ),
                                h( $user['User']['numtel'] ),
                                h( date_short( $user['User']['date_deb_hab'] ) ),
                                h( date_short( $user['User']['date_fin_hab'] ) ),
                                h( $user['Group']['name'] ) ,
                                h( $user['Serviceinstructeur']['lib_service'] ),
                                $html->editLink(
                                    'Éditer l\'utilisateur',
                                    array( 'controller' => 'users', 'action' => 'edit', $user['User']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer l\'utilisateur',
                                    array( 'controller' => 'users', 'action' => 'delete', $user['User']['id'] )
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