<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $this->element( 'dossier_menu', array( 'id' => 203118 ) );?>

<div class="with_treemenu">

<?php $this->pageTitle = 'Liste des offres de l\'allocataire';?>
    <h1>Liste des offres où l'allocataire est inscrit</h1>

    <ul class="actionMenu">
        <?php

            echo '<li>'.$html->addLink(
                'Ajouter une offre',
                array(  'action' => '../pages/display/webrsa/create_offre/' )
            ).' </li>';
        ?>
    </ul>

    <div class="">
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Type d'action</th>
                    <th>Thème / Filière</th>
                    <th>Nom de la structure</th>
                    <th>Commune de l'action</th>
                    <th>Territoire CLI + hors CLI</th>
                    <th>Date début formation</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Action d'insertion / santé</td>
                    <td>Insertion</td>
                    <td>Association DoRéMi</td>
                    <td>Bobigny</td>
                    <td>CLI n°6</td>
                    <td>06/12/2010</td>
                    <td>
                        <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir l'offre</a>
                    </td>
                </tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>Formation qualifiante</td>
                    <td>Bâtiment</td>
                    <td>Bagnolet Formation</td>
                    <td>Bagnolet</td>
                    <td>CLI n°3</td>
                    <td>15/01/2011</td>
                    <td>
                        <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir l'offre</a>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


</body></html>
</div>
    <div class="clearer"><hr></div>