<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Recherche d\'offres';?>
<ul class="actionMenu">
<?php

        echo '<li>'.$xhtml->link(
            $xhtml->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li>';

        echo '<li>'.$xhtml->addLink(
            'Ajouter une offre',
            array(  'action' => '../pages/display/webrsa/create_offre/' )
        ).' </li>';
?>
</ul>
<?php
    $typeaction = array(
        '',
        'Formations',
        'Actions de SIAE',
        'CUIs (ex contrats aidés)',
        'Aides à la création d\'entreprise',
        'Mesures ASI',
        'Actions d\'insertion / santé',
        'Actions d\'insertion / social spécifique',
        'Frais annexes à la formation',
        'APRE',
    );

    $nomstruct = array(
        '',
        'CCAS de Drancy',
        'Bobigny',
        'Communauté d\'agglomération de Paris',
        'Adullact'
    );

?>
<?php echo $form->create( 'Offre', array( 'type' => 'post', 'action' => '../pages/display/webrsa/recherche_offre/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche d'offres</legend>

<?php
    echo $form->input( 'Offre.typeaction', array('disabled'=>false, 'label' => 'Type d\'action', 'options' => $typeaction ) );
    echo $form->input( 'Offre.themes', array('disabled'=>false, 'label' => 'Thèmes / Filière' ) );
    echo $form->input( 'Offre.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure', 'options' => $nomstruct ) );
    echo $form->input( 'Offre.numaction', array('disabled'=>false, 'label' => 'N° de la structure / action' ) );
    echo $form->input( 'Offre.commune', array('disabled'=>false, 'label' => 'Commune de l\'action' ) );
    echo $form->input( 'Offre.territoire', array('disabled'=>false, 'label' => 'Territoire CLI + hors CLI'/*, 'options' => $zone*/ ) );
    echo $form->input( 'Offre.dateformation', array('disabled'=>false, 'label' => 'Formation débutant le', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 2, 'minYear' => date( 'Y' ) - 2 ) );
    echo $form->input( 'Offre.public', array('disabled'=>false, 'label' => 'Public concerné' ) );
    echo $form->input( 'Offre.motcle', array('disabled'=>false, 'label' => 'Mot clé' ) );
    echo $form->input( 'Offre.afficheactiondispo', array('disabled'=>false, 'label' => 'N\'afficher que les actions ayant des places disponibles', 'type' => 'checkbox' ) );
?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>

<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>



    <div class="">
    <h1>Liste des offres</h1>
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
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>Formation qualifiante</td>
                    <td>Bâtiment</td>
                    <td>Bagnolet Formation</td>
                    <td>Bagnolet</td>
                    <td>CLI n°3</td>
                    <td>15/01/2011</td>
                    <td>
                        <li>
                            <a href="../webrsa/recherche_allocataire" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Rechercher allocataire</a>
                        </li>
                        <li>
                            <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Gérer cette offre</a>
                        </li>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Action d'insertion / santé</td>
                    <td>Insertion</td>
                    <td>Association DoRéMi</td>
                    <td>Bobigny</td>
                    <td>CLI n°6</td>
                    <td>06/12/2010</td>
                    <td>
                        <li>
                            <a href="../webrsa/recherche_allocataire" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Rechercher allocataire</a>
                        </li>
                        <li>
                            <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Gérer cette offre</a>
                        </li>
                    </td>
                </tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>CUIs (ex contrats aidés)</td>
                    <td>Insertion </td>
                    <td>PDV Villepinte</td>
                    <td>Villepinte</td>
                    <td>CLI n°4</td>
                    <td>10/01/2011</td>
                    <td>
                        <li>
                            <a href="../webrsa/recherche_allocataire" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Rechercher allocataire</a>
                        </li>
                        <li>
                            <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Gérer cette offre</a>
                        </li>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>APRE</td>
                    <td>Bâtiment</td>
                    <td>Chantier d'insertion</td>
                    <td>Clichy/Bois</td>
                    <td>CLI n°5</td>
                    <td>15/01/2011</td>
                    <td>
                        <li>
                            <a href="../webrsa/recherche_allocataire" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Rechercher allocataire</a>
                        </li>
                        <li>
                            <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Gérer cette offre</a>
                        </li>
                    </td>
                </tr>
            </tbody>
        </table>



    <br>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$xhtml->exportLink(
                    'Télécharger le tableau',
                    array(  'action' => '#' )
                ).' </li>';
                echo '<li>'.$xhtml->printLink(
                    'Imprimer',
                    array(  'action' => '#' )
                ).' </li>';
            ?>
        </ul>

    </div>
    <div class="clearer"><hr></div>

</body></xhtml>
<?php endif;?>
