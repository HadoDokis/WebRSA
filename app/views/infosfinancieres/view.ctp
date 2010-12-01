<?php $this->pageTitle = 'Visualisation des Informations financières';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>
<!--
    <ul class="actionMenu">
        <?php
            echo '<li>'.$xhtml->editLink(
                'Éditer des informations',
                array( 'controller' => 'infosfinancieres', 'action' => 'edit', $infofinanciere['Infofinanciere']['id'] )
            ).' </li>';
        ?>
    </ul>-->

    <div id="ficheInfoFina">
        <table>
            <tbody>
                <tr class="even">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.moismoucompta' );?></th>
                    <td><?php echo  h( strftime('%B %Y', strtotime( $infofinanciere['Infofinanciere']['moismoucompta'] ) ) );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.type_allocation' );?></th>
                    <td><?php echo ($type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.natpfcre' );?></th>
                    <td><?php echo ($natpfcre[$infofinanciere['Infofinanciere']['natpfcre']]);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.rgcre' );?></th>
                    <td><?php echo ($infofinanciere['Infofinanciere']['rgcre']);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.numintmoucompta' );?></th>
                    <td><?php echo ($infofinanciere['Infofinanciere']['numintmoucompta']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.typeopecompta' );?></th>
                    <td><?php echo ($typeopecompta[$infofinanciere['Infofinanciere']['typeopecompta']]);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.sensopecompta' );?></th>
                    <td><?php echo ($sensopecompta[$infofinanciere['Infofinanciere']['sensopecompta']]);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.mtmoucompta' );?></th>
                    <td><?php echo ($infofinanciere['Infofinanciere']['mtmoucompta']);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.ddregu' );?></th>
                    <td><?php echo (  date_short( $infofinanciere['Infofinanciere']['ddregu'] ) );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'infofinanciere', 'Infofinanciere.dttraimoucompta' );?></th>
                    <td><?php echo h( date_short( $infofinanciere['Infofinanciere']['dttraimoucompta'] ) );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'heutraimoucompta' );?></th>
                    <td><?php echo (  date_short( $infofinanciere['Infofinanciere']['heutraimoucompta'] ) );?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>
