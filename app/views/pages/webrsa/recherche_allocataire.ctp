<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Recherche d\'allocataire';?>
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
        'PDV de Bondy',
        'PDV de Drancy',
        'PDV de Bobigny',
        'PDV de Villepinte'
    );

?>
<?php echo $form->create( 'Allocataire', array( 'type' => 'post', 'action' => '../pages/display/webrsa/recherche_allocataire/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche d'allocataires</legend>

<?php
    echo $form->input( 'Allocataire.civilite', array('disabled'=>false, 'label' => 'Civilité de l\'allocataire', 'options' => array( '', 'Mademoiselle', 'Madame', 'Monsieur' ) ) );
    echo $form->input( 'Allocataire.nom', array('disabled'=>false, 'label' => 'Nom de l\'allocataire' ) );
    echo $form->input( 'Allocataire.prenom', array('disabled'=>false, 'label' => 'Prénom de l\'allocataire' ) );
    echo $form->input( 'Allocataire.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure référente liée à l\'allocataire', 'options' => $nomstruct ) );
    echo $form->input( 'Allocataire.typeaction', array('disabled'=>false, 'label' => 'Type d\'action engagée', 'options' => $typeaction ) );
    echo $form->input( 'Allocataire.commune', array('disabled'=>false, 'label' => 'Commune de l\'allocataire' ) );

?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>

<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>



    <div class="">
    <h1>Liste des allocataires</h1>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom / Prénom Allocataire</th>
                    <th>Commune</th>
                    <th>Structure référente</th>
                    <th>Type d'action</th>
                    <th>Contrat validé</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>MR AUZOLAT Arnaud</td>
                    <td>Bobigny</td>
                    <td>PDV de Bobigny</td>
                    <td>Action d'insertion / santé</td>
                    <td>Oui</td>
                    <td>
                        <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Inviter</a>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>MR BUFFIN Christian</td>
                    <td>Drancy</td>
                    <td>PDV de Drancy</td>
                    <td>CUIs</td>
                    <td>Non</td>
                    <td>
                        <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Inviter</a>
                    </td>
                </tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>MLE GAY Marie</td>
                    <td>Villepinte</td>
                    <td>PDV de Villepinte</td>
                    <td>Frais annexes à la formation</td>
                    <td>Oui</td>
                    <td>
                        <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Inviter</a>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>MR HAMZAOUI Michel</td>
                    <td>Bondy</td>
                    <td>PDV de Bondy</td>
                    <td>Actions de SIAE</td>
                    <td>Oui</td>
                    <td>
                        <a href="../webrsa/gestion_offre" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Inviter</a>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
<br />
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

</body></xhtml>
<?php endif;?>
</div>
    <div class="clearer"><hr></div>