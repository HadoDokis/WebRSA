<?php $this->pageTitle = 'Comité d\'examen pour l\'APRE';?>
<h1>Détails Comité d'examen</h1>
<?php if( $permissions->check( 'comitesexamenapres', 'add' ) ):?>
    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->editLink(
                'Modifier Comité',
                array( 'controller' => 'comitesexamenapres', 'action' => 'edit', Set::classicExtract( $comiteexamenapre, 'Comiteexamenapre.id' ) )
            ).' </li>';
        ?>
    </ul>
<?php endif;?>


<div id="ficheCI">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'Date du comité');?></th>
                    <td><?php echo date_short( Set::classicExtract( $comiteexamenapre, 'Comiteexamenapre.datecomite' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Heure du comité' );?></th>
                    <td><?php echo $locale->date( 'Time::short', Set::classicExtract( $comiteexamenapre, 'Comiteexamenapre.heurecomite' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Lieu du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteexamenapre, 'Comiteexamenapre.lieucomite' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'Intitulé du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteexamenapre, 'Comiteexamenapre.intitulecomite' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Observations du comité' );?></th>
                    <td><?php echo Set::classicExtract( $comiteexamenapre, 'Comiteexamenapre.observationcomite' );?></td>
                </tr>
            </tbody>
        </table>
</div>
</div>
<div class="clearer"><hr /></div>