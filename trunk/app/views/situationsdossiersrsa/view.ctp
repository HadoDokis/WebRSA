<?php $this->pageTitle = 'Visualisation des situations du dossier';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <div id="ficheInfoFina">
        <table>
            <tbody>
                <tr class="even">
                    <th ><?php __d( 'situationdossierrsa', 'Situationdossierrsa.etatdosrsa' );?></th>
                    <td><?php echo  h( $etatdosrsa[$situationdossierrsa['Situationdossierrsa']['etatdosrsa']] );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'situationdossierrsa', 'Situationdossierrsa.dtrefursa' );?></th>
                    <td><?php echo ( date_short( $situationdossierrsa['Situationdossierrsa']['dtrefursa'] ) );?></td>
                </tr>
                <tr class="even">
                    <th ><?php __( 'moticlorsa' );?></th>
                    <td><?php echo ( isset( $moticlorsa[$situationdossierrsa['Situationdossierrsa']['moticlorsa']] ) ? $moticlorsa[$situationdossierrsa['Situationdossierrsa']['moticlorsa']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th ><?php __d( 'situationdossierrsa', 'Situationdossierrsa.dtclorsa' );?></th>
                    <td><?php echo ( date_short( $situationdossierrsa['Situationdossierrsa']['dtclorsa'] ) );?></td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
<div class="clearer"><hr /></div>