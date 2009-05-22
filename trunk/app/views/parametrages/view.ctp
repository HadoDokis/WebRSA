<?php $this->pageTitle = 'Paramétrage de table';?>

<div class="with_treemenu">
    <h1><?php echo 'Visualisation de la table  ';?></h1>


    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter',
                array( 'controller' => 'parametrages', 'action' => 'add', $param )
            ).' </li>';
        ?>
    </ul>

<div id="ficheDspp">
            <h2>Table Zone géographique</h2>

<table>
        <tbody>

            <tr class="odd">
                <th ><?php __( 'libelle' );?></th>
                <td><?php echo ( $zone['Zonegeographique']['libelle'] );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'codeinsee' );?></th>
                <td><?php echo ( $zone['Zonegeographique']['codeinsee'] );?></td>
            </tr>

        </tbody>
</table>
</div>
</div>
<div class="clearer"><hr /></div>
