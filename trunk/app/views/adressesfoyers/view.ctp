<?php
    $title = implode(
        ' ',
        array(
            $adresse['Adresse']['numvoie'],
            $adresse['Adresse']['typevoie'],
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
                echo '<li>'.$html->editLink(
                    'Éditer l\'adresse « '.$title.' »',
                    array( 'controller' => 'adressesfoyers', 'action' => 'edit', $adresse['Adressefoyer']['id'] )
                ).' </li>';
            }

//             echo '<li>'.$html->deleteLink(
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
                    <th><?php __( 'rgadr' );?></th>
                    <td><?php echo isset( $rgadr[$adresse['Adressefoyer']['rgadr']] ) ? $rgadr[$adresse['Adressefoyer']['rgadr']] : null ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'dtemm' );?></th>
                    <td><?php echo date_short( $adresse['Adressefoyer']['dtemm'] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'typeadr' );?></th>
                    <td><?php echo $typeadr[$adresse['Adressefoyer']['typeadr']];?></td>
                </tr>
            </tbody>
        </table>
        <h2>Adresse</h2>
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'numvoie' );?></th>
                    <td><?php echo $adresse['Adresse']['numvoie'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'typevoie' );?></th>
                    <td><?php echo $adresse['Adresse']['typevoie'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'nomvoie' );?></th>
                    <td><?php echo $adresse['Adresse']['nomvoie'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'complideadr' );?></th>
                    <td><?php echo $adresse['Adresse']['complideadr'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'compladr' );?></th>
                    <td><?php echo $adresse['Adresse']['compladr'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'lieudist' );?></th>
                    <td><?php echo $adresse['Adresse']['lieudist'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'numcomrat' );?></th>
                    <td><?php echo $adresse['Adresse']['numcomrat'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'numcomptt' );?></th>
                    <td><?php echo $adresse['Adresse']['numcomptt'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'codepos' );?></th>
                    <td><?php echo $adresse['Adresse']['codepos'];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'locaadr' );?></th>
                    <td><?php echo $adresse['Adresse']['locaadr'];?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'pays' );?></th>
                    <td><?php echo $pays[$adresse['Adresse']['pays']];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'canton' );?></th>
                    <td><?php echo $adresse['Adresse']['canton'];?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="clearer"><hr /></div>