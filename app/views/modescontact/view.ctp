<?php $this->pageTitle = 'Visualisation des modes de contact';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <div id="ficheInfoFina">
        <table>
            <tbody>
                <tr class="even">
                    <th ><?php __( 'numtel' );?></th>
                    <td><?php echo  h( $modecontact['Modecontact']['numtel']] );?></td>
                </tr>
                 <tr class="odd">
                    <th ><?php __( 'numposte' );?></th>
                    <td><?php echo (date_short( $modecontact['Modecontact']['numposte'] ) );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'nattel' );?></th>
                    <td><?php echo ($modecontact['Modecontact']['nattel'] );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'matetel' );?></th>
                    <td><?php echo ( $modecontact['Modecontact']['matetel'] );?></td> 
                </tr>
                <tr class="even">
                    <th ><?php __( 'autorutitel' );?></th>
                    <td><?php echo ($modecontact['Modecontact']['autorutitel'] );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'adrelec' );?></th>
                    <td><?php echo ( $modecontact['Modecontact']['adrelec'] );?></td> 
                </tr>
                <tr class="even">
                    <th ><?php __( 'autorutiadrelec' );?></th>
                    <td><?php echo ($modecontact['Modecontact']['autorutiadrelec'] );?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>