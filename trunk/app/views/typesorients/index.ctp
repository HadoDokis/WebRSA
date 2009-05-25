<?php $this->pageTitle = 'Paramétrage des Types d\'orientation';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'typesorients', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Types d'orientation</h2>
        <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type d'orientation</th>
                <th>Parent</th>
                <th>Modèle de notification</th>
                <th colspan="1" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $typesorients as $typeorient ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $typeorient['Typeorient']['id'] ),
                                h( $typeorient['Typeorient']['lib_type_orient'] ),
                                h( $typeorient['Typeorient']['parentid'] ),
                                h( $typeorient['Typeorient']['modele_notif'] ),
                                $html->editLink(
                                    'Éditer le contrat d\'insertion ',
                                    array( 'controller' => 'typesorients', 'action' => 'edit', $typeorient['Typeorient']['id'] )
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