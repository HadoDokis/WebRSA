<?php $this->pageTitle = 'Visualisation des modes de contact';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <div id="ficheInfoFina">
        <table>
            <tbody>
                <tr class="even">
                    <th ><?php __( 'numtel' );?></th>
                    <td><?php echo  h( $modecontact['Modecontact']['numtel'] );?></td>
                </tr>
                 <tr class="odd">
                    <th ><?php __d( 'modecontact', 'Modecontact.numposte' );?></th>
                    <td><?php echo ( $modecontact['Modecontact']['numposte'] );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __d( 'modecontact', 'Modecontact.nattel' );?></th>
                    <td><?php echo ( isset( $nattel[$modecontact['Modecontact']['nattel']] ) ? $nattel[$modecontact['Modecontact']['nattel']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'modecontact', 'Modecontact.matetel' );?></th>
                    <td><?php echo ( isset( $matetel[$modecontact['Modecontact']['matetel']] ) ? $matetel[$modecontact['Modecontact']['matetel']] : null );?></td> 
                </tr>
                <tr class="even">
                    <th ><?php __d( 'modecontact', 'Modecontact.autorutitel' );?></th>
                    <td><?php echo ( isset( $autorutitel[$modecontact['Modecontact']['autorutitel']] ) ? $autorutitel[$modecontact['Modecontact']['autorutitel']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'modecontact', 'Modecontact.adrelec' );?></th>
                    <td><?php echo ( $modecontact['Modecontact']['adrelec'] );?></td> 
                </tr>
                <tr class="even">
                    <th ><?php __d( 'modecontact', 'Modecontact.autorutiadrelec' );?></th>
                    <td><?php echo ( isset( $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] ) ? $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] : null );?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>