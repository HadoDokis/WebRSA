<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Recherche des structures';?>
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
        'SIAE',
        'Organisme de formation',
        '...'
    );

    $statuts = array(
        '',
        'Candidature non instruite',
        'Projet enregistré',
        'Soumis à validation',
        'Validée',
        'Rejetée'
    );

    $referent = array(
        '',
        'MR AUZOLAT Arnaud',
        'MLE GAY Marie'
    );
?>
<?php echo $form->create( 'Structure', array( 'type' => 'post', 'action' => '../pages/display/webrsa/recherche_admin_structure/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche de structures</legend>

<?php
    echo $form->input( 'Structure.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure', 'options' => $nomstruct ) );
    echo $form->input( 'Structure.typeaction', array('disabled'=>false, 'label' => 'Activité de la structure / Intitulé de l\'action' ) );
    echo $form->input( 'Structure.typeaction', array('disabled'=>false, 'label' => 'Type d\'action', 'options' => $typeaction ) );
    echo $form->input( 'Structure.themes', array('disabled'=>false, 'label' => 'Thèmes / Filière', 'options' => array( '', 'Restauration', 'Bâtiment', '...' ) ) );
    echo $form->input( 'Structure.dossiersuivi', array('disabled'=>false, 'label' => 'Dossier suivi par', 'options' => $referent ) );
    echo $form->input( 'Structure.numaction', array('disabled'=>false, 'label' => 'N° d\'enregistrement de la structure' ) );
    echo $form->input( 'Structure.commune', array('disabled'=>false, 'label' => 'Commune de la structure' ) );
    echo $form->input( 'Structure.territoire', array('disabled'=>false, 'label' => 'Commune de l\'action'/*, 'options' => $zone*/ ) );
    echo $form->input( 'Structure.dateformation', array('disabled'=>false, 'label' => 'Statut de la candidature', 'options' =>  $statuts ) );

?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>

<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>



    <div class="">
    <h1>Liste des structures</h1>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom structure</th>
                    <th>Activité</th>
                    <th>Type d'action</th>
                    <th>Thème / Filière</th>
                    <th>Dossier suivi par</th>
                    <th>N° enregistrement</th>
                    <th>Commune structure</th>
                    <th>Statut candidature</th>

                    <th>Taux encadrement</th>
                    <th>Taux occupation N-1</th>
                    <th>Montant demandé</th>
                    <th>Montant accordé</th>
                    <th colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>Bagnolet Formation</td>
                    <td>Formations</td>
                    <td>Formation qualifiante</td>
                    <td>Bâtiment</td>
                    <td>A. AUZOLAT</td>
                    <td>0001</td>
                    <td>Bobigny</td>
                    <td>Soumis à validation</td>

                    <td>100%</td>
                    <td>60%</td>
                    <td>2000€</td>
                    <td>2000€</td>
                    <td>
                        <a href="../webrsa/saisie_candidature_indiv" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir dossier structure</a>
                    </td>
                    <td>
                        <a href="#" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Notifier décision</a>
                    </td>
                    <td>
                        <a href="#" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Editer convention</a>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Association DoRéMi</td>
                    <td>Insertion</td>
                    <td>Action d'insertion / santé</td>
                    <td>Restauration</td>
                    <td>A. AUZOLAT</td>
                    <td>0002</td>
                    <td>Drancy</td>
                    <td>Projet enregistré</td>

                    <td>60%</td>
                    <td>80%</td>
                    <td>1500€</td>
                    <td>1000€</td>
                    <td>
                        <a href="../webrsa/saisie_candidature_indiv" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir dossier structure</a>
                    </td>
                    <td>
                        <a href="#" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Notifier décision</a>
                    </td>
                    <td>
                        <a href="#" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Editer convention</a>
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
