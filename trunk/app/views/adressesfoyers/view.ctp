<?php
    $title = implode(
        ' ',
        array(
            $adresse['Adresse']['numvoie'],
            $typevoie[$adresse['Adresse']['typevoie']],
            $adresse['Adresse']['nomvoie']
        )
    );

    $this->pageTitle = 'Visualisation de l\'adresse « '.$title.' »';
?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $adresse['Adressefoyer']['foyer_id'] ) );?>

<div class="with_treemenu">
    <h1><?php echo 'Visualisation de l\'adresse « '.$title.' »';?></h1>

    <ul class="actionMenu">
        <?php
            if( $permissions->check( 'adressesfoyers', 'edit' ) ) {
                echo '<li>'.$xhtml->editLink(
                    'Éditer l\'adresse « '.$title.' »',
                    array( 'controller' => 'adressesfoyers', 'action' => 'edit', $adresse['Adressefoyer']['id'] )
                ).' </li>';
            }

//             echo '<li>'.$xhtml->deleteLink(
//                 'Supprimer l\'adresse « '.$title.' »',
//                 array( 'controller' => 'adressesfoyers', 'action' => 'delete', $adresse['Adressefoyer']['id'] )
//             ).' </li>';
        ?>
    </ul>

    <div id="ficheAdresse">
        <h2>Informations adresse</h2>
        <table>
            <tbody>
                <tr class="odd">
                    <th><?php __d( 'adressefoyer', 'Adressefoyer.rgadr' );?></th>
                    <td><?php echo isset( $rgadr[$adresse['Adressefoyer']['rgadr']] ) ? $rgadr[$adresse['Adressefoyer']['rgadr']] : null ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'adressefoyer', 'Adressefoyer.dtemm' );?></th>
                    <td><?php echo date_short( $adresse['Adressefoyer']['dtemm'] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adressefoyer', 'Adressefoyer.typeadr' );?></th>
                    <td><?php echo $typeadr[$adresse['Adressefoyer']['typeadr']];?></td>
                </tr>
            </tbody>
        </table>
        <h2>Adresse</h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __d( 'adresse', 'Adresse.numvoie' );?></th>
                    <td><?php echo $adresse['Adresse']['numvoie'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adresse', 'Adresse.typevoie' );?></th>
                    <td><?php echo isset( $typevoie[$adresse['Adresse']['typevoie']] ) ? $typevoie[$adresse['Adresse']['typevoie']] : null;?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'adresse', 'Adresse.nomvoie' );?></th>
                    <td><?php echo $adresse['Adresse']['nomvoie'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adresse', 'Adresse.complideadr' );?></th>
                    <td><?php echo $adresse['Adresse']['complideadr'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'adresse', 'Adresse.compladr' );?></th>
                    <td><?php echo $adresse['Adresse']['compladr'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adresse', 'Adresse.lieudist' );?></th>
                    <td><?php echo $adresse['Adresse']['lieudist'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'adresse', 'Adresse.numcomrat' );?></th>
                    <td><?php echo $adresse['Adresse']['numcomrat'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adresse', 'Adresse.numcomptt' );?></th>
                    <td><?php echo $adresse['Adresse']['numcomptt'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'adresse', 'Adresse.codepos' );?></th>
                    <td><?php echo $adresse['Adresse']['codepos'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adresse', 'Adresse.locaadr' );?></th>
                    <td><?php echo $adresse['Adresse']['locaadr'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __d( 'adresse', 'Adresse.pays' );?></th>
                    <td><?php echo $pays[$adresse['Adresse']['pays']];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __d( 'adresse', 'Adresse.canton' );?></th>
                    <td><?php echo $adresse['Adresse']['canton'];?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="clearer"><hr /></div>