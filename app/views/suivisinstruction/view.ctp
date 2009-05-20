<?php $this->pageTitle = 'Visualisation des suivis d\'instruction';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <div id="ficheInfoFina">
        <table>
            <tbody>
                <tr class="even">
                    <th ><?php __( 'etatirsa' );?></th>
                    <td><?php echo  h( $etatirsa[$suiviinstruction['Suiviinstruction']['etatirsa']] );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'date_etat_instruction' );?></th>
                    <td><?php echo (date_short( $suiviinstruction['Suiviinstruction']['date_etat_instruction'] ) );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'nomins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['nomins']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'prenomins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['prenomins']);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'numdepins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['numdepins']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'typeserins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['typeserins']);?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'numcomins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['numcomins']);?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __( 'numagrins' );?></th>
                    <td><?php echo ($suiviinstruction['Suiviinstruction']['numagrins']);?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>