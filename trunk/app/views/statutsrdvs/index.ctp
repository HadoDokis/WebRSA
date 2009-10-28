<?php $this->pageTitle = 'Paramétrage des statuts de rendez-vous';?>

<div>
    <h1><?php echo 'Visualisation de la table  ';?></h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'statutsrdvs', 'action' => 'add' )
            ).' </li>';
        ?>
    </ul>
    <div>
        <h2>Table Statut de rendez-vous</h2>
        <table>
        <thead>
            <tr>
                <th>Statut de rendez-vous</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $statutsrdvs as $statutrdv ):?>
                <?php echo $html->tableCells(
                            array(
                                h( $statutrdv['Statutrdv']['libelle'] ),
                                $html->editLink(
                                    'Éditer le type d\'action',
                                    array( 'controller' => 'statutsrdvs', 'action' => 'edit', $statutrdv['Statutrdv']['id'] )
                                ),
                                $html->deleteLink(
                                    'Supprimer le type d\'action',
                                    array( 'controller' => 'statutsrdvs', 'action' => 'delete', $statutrdv['Statutrdv']['id'] )
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