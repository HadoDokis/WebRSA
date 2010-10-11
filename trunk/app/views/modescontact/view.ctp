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
                    <th ><?php __( 'numposte' );?></th>
                    <td><?php echo ( $modecontact['Modecontact']['numposte'] );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'nattel' );?></th>
                    <td><?php echo ( isset( $nattel[$modecontact['Modecontact']['nattel']] ) ? $nattel[$modecontact['Modecontact']['nattel']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'matetel' );?></th>
                    <td><?php echo ( isset( $matetel[$modecontact['Modecontact']['matetel']] ) ? $matetel[$modecontact['Modecontact']['matetel']] : null );?></td> 
                </tr>
                <tr class="even">
                    <th ><?php __( 'autorutitel' );?></th>
                    <td><?php echo ( isset( $autorutitel[$modecontact['Modecontact']['autorutitel']] ) ? $autorutitel[$modecontact['Modecontact']['autorutitel']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'adrelec' );?></th>
                    <td><?php echo ( $modecontact['Modecontact']['adrelec'] );?></td> 
                </tr>
                <tr class="even">
                    <th ><?php __( 'autorutiadrelec' );?></th>
                    <td><?php echo ( isset( $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] ) ? $autorutiadrelec[$modecontact['Modecontact']['autorutiadrelec']] : null );?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>