<?php $this->pageTitle = 'Dossier RSA '.$dossier['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>

<div class="with_treemenu">
    <h1>Dossier RSA <?php echo h( $dossier['Dossier']['numdemrsa'] );?></h1>

<div id="ficheDossier">
    <table>
        <tbody>
            <tr class="odd">
                <th>Numéro de dossier</th>
                <td><?php echo h( $dossier['Dossier']['numdemrsa'] );?></td>
            </tr>
            <tr class="even">
                <th>Date de demande</th>
                <td><?php echo h( date_short( $dossier['Dossier']['dtdemrsa'] ) );?></td>
            </tr>
            <tr class="odd">
                <th>État du dossier</th>
                <td><?php echo h( $dossier['Situationdossierrsa']['etatdosrsa'] );?></td>
            </tr>
        </tbody>
    </table>
</div>
</div>
<div class="clearer"><hr /></div>