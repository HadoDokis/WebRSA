<?php $this->pageTitle = 'Paramétrage des Services instructeurs';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <?php if( $permissions->check( 'servicesinstructeurs', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter',
                    array( 'controller' => 'servicesinstructeurs', 'action' => 'add' )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <div>
        <h2>Table Service instructeur</h2>
        <table>
        <thead>
            <tr>
                <th>Nom du service</th>
                <th>N° de rue</th>
                <th>Type de voie</th>
                <th>Nom de rue</th>
                <!-- <th>Complément d'adresse</th> -->
                <th>Code INSEE</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>N° dépt</th>
                <th>Type service</th>
                <th>N° commune</th>
                <th>N° agréement</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $servicesinstructeurs as $serviceinstructeur ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $serviceinstructeur['Serviceinstructeur']['lib_service'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['num_rue'] ),
                                h( isset( $typevoie[$serviceinstructeur['Serviceinstructeur']['type_voie']] ) ? $typevoie[$serviceinstructeur['Serviceinstructeur']['type_voie']] : null ),
                                h( $serviceinstructeur['Serviceinstructeur']['nom_rue'] ),
                                //h( $serviceinstructeur['Serviceinstructeur']['complement_adr'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['code_insee'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['code_postal'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['ville'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['numdepins'] ),
                                h( isset( $typeserins[$serviceinstructeur['Serviceinstructeur']['typeserins']] ) ? $typeserins[$serviceinstructeur['Serviceinstructeur']['typeserins']] : null ),
                                h( $serviceinstructeur['Serviceinstructeur']['numcomins'] ),
                                h( $serviceinstructeur['Serviceinstructeur']['numagrins'] ),
                                $html->editLink(
                                    'Éditer le service instructeur ',
                                    array( 'controller' => 'servicesinstructeurs', 'action' => 'edit', $serviceinstructeur['Serviceinstructeur']['id'] ),
                                    $permissions->check( 'servicesinstructeurs', 'edit' )
                                ),
                                $html->deleteLink(
                                    'Supprimer le service instructeur ',
                                    array( 'controller' => 'servicesinstructeurs', 'action' => 'delete', $serviceinstructeur['Serviceinstructeur']['id'] ),
                                    $permissions->check( 'servicesinstructeurs', 'delete' ) && ( $serviceinstructeur['Serviceinstructeur']['id'] != $user['User']['serviceinstructeur_id'] )
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