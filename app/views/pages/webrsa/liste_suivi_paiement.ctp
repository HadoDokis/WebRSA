<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>


<?php $this->pageTitle = 'Suivi des paiements';?>
<ul class="actionMenu">
<?php

        echo '<li>'.$html->link(
            $html->image(
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
        'CCAS de Drancy',
        'Bobigny',
        'Communauté d\'agglomération de Paris',
        'Adullact'
    );
    $sect_acti_emp = array(
        '',
        'Agriculture, sylviculture et pêche',
        'Industries extractives',
        'Industrie manufacturière',
        'Production et distribution d\'électricité, de gaz, de vapeur et d\'air conditionné',
        'Production et distribution d\'eau ; assainissement, gestion des déchets et dépollution',
        'Construction',
        'Commerce ; réparation d\'automobiles et de motocycles',
        'Transports et entreposage',
        'Hébergement et restauration',
        'Information et communication',
        'Activités financières et d\'assurance',
        'Activités immobilières',
        'Activités spécialisées, scientifiques et techniques',
        'Activités de services administratifs et de soutien',
        'Administration publique',
        'Enseignement',
        'Santé humaine et action sociale',
        'Arts, spectacles et activités récréatives',
        'Autres activités de services',
        'Activités des ménages en tant qu\'employeurs; activités indifférenciées des ménages en tant que producteurs de biens et services pour usage propre',
        'Activités extra-territoriales'
    );

    $typestruct = array(
        '',
        'PDV',
        'Association',
        '...'
    );

    $annee = array(
        '',
        '2011',
        '2010',
        '2009',
    );

?>
<?php echo $form->create( 'Suivi', array( 'type' => 'post', 'action' => '../pages/display/webrsa/liste_suivi_paiement/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche d'offres</legend>

<?php
    echo $form->input( 'Suivi.annee', array('disabled'=>false, 'label' => 'Année', 'options' => $annee ) );
    echo $form->input( 'Suivi.typestruct', array('disabled'=>false, 'label' => 'Type de structures', 'options' => $typestruct ) );
    echo $form->input( 'Suivi.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure', 'options' => $nomstruct ) );
    echo $form->input( 'Suivi.typeaction', array('disabled'=>false, 'label' => 'Type d\'actions', 'options' => $typeaction ) );
    echo $form->input( 'Suivi.theme', array('disabled'=>false, 'label' => 'Thèmes / Filières' ) );
    echo $form->input( 'Suivi.territoire', array('disabled'=>false, 'label' => 'Action / Activité'/*, 'options' => $zone*/ ) );
?>
</fieldset>

<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>

<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php if( !empty( $this->data ) ):?>



    <div class="">
    <h1>Suivi des paiements</h1>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Organisme</th>
                    <th>Type d'action / Type de SIAE</th>
                    <th>Filière</th>
                    <th>Action / Activité</th>
                    <th>Montant versé</th>
                    <th>Solde</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>Association</td>
                    <td>Alpha</td>
                    <td>Restauration</td>
                    <td></td>
                    <td>2400 €</td>
                    <td>800 €</td>
                    <td>
                        <a href="../webrsa/etapes_convention" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir la convention</a>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Association</td>
                    <td>FLE</td>
                    <td>Bâtiment</td>
                    <td></td>
                    <td>1200 €</td>
                    <td>400 €</td>
                    <td>
                        <a href="../webrsa/etapes_convention" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Voir la convention</a>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


</body></html>
<?php endif;?>
</div>
    <div class="clearer"><hr></div>