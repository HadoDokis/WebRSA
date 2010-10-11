<h3>Historique des exports</h3>

<table>
    <thead>
    <?php
        echo $html->tableHeaders(
            array("Fichier", "Action")
        ); 
    ?></thead>

    <tbody>
    <?php foreach( $data as $row ): ?>
        <tr>
            <td><?php echo $row; ?></td>
            <td>
                <?php echo $html->link( "Télécharger", 'export_download/'.$row ); ?> 
                <?php echo $html->link( "Supprimer", 'export_delete/'.$row, null, "Etes-vous sûr de vouloir supprimer ?" ); ?> 
            </td>
        </tr>
    <?php endforeach; ?> 
    </tbody>
</table>
 
<p><?php echo $html->link("Créer un nouvel export", 'export'); ?></p>