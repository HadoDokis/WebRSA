<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Liste des conventions';?>
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
            'Ajouter une convention',
            array(  'action' => '../pages/display/webrsa/create_convention/' )
        ).' </li>';
?>
</ul>
<?php
    $typesstructs = array(
        '',
        'Référents',
        'PDV'
    );

    $numconvention = array(
        '',
        '0001',
        '0002',
        '0003'
    );

    $nomstruct = array(
        '',
        'CCAS de Drancy',
        'Bobigny',
        'Communauté d\'agglomération de Paris',
        'Adullact'
    );

    $annee = array(
        '',
        '2009',
        '2010',
        '2011'
    );

    $statut = array(
        '',
        'En projet',
        'Validé'
    );

    $referent = array(
        '',
        'MR AUZOLAT Arnaud',
        'MLE GAY Marie'
    );
?>
<?php echo $form->create( 'Convention', array( 'type' => 'post', 'action' => '../pages/display/webrsa/liste_convention/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche de conventions</legend>

<?php
    echo $form->input( 'Convention.type_struct', array('disabled'=>false, 'label' => 'Liste des projets', 'options' => $typesstructs ) );
    echo $form->input( 'Convention.numconvention', array('disabled'=>false, 'label' => 'N° de la convention', 'options' => $numconvention ) );
    echo $form->input( 'Convention.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure', 'options' => $nomstruct ) );
    echo $form->input( 'Convention.anneeconvention', array('disabled'=>false, 'label' => 'Année de la convention', 'options' => $annee ) );
    echo $form->input( 'Convention.numconvention', array('disabled'=>false, 'label' => 'Statut de la convention', 'options' => $statut ) );
    echo $form->input( 'Convention.referent', array('disabled'=>false, 'label' => 'N° de la convention', 'options' => $referent ) );
?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>


<?php if( !empty( $this->data ) ):?>
<div class="">
    <h1>Liste des conventions</h1>

         <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Type de structure</th>
                    <th>N° de la convention</th>
                    <th>Nom de la structure</th>
                    <th>Année de la convention</th>
                    <th>Durée de la convention</th>
                    <th>Montant du financement</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>CCAS</td>
                    <td>0001</td>
                    <td>CCAS de Drancy</td>
                    <td>2010</td>
                    <td>6 mois</td>
                    <td>1500 €</td>
                    <td>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant association</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant PDV</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Compléter le projet</a>
                        </li>
                        <li>
                            <a href="../webrsa/create_projetactivite" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer projet activité</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer convention fille PDV</a>
                        </li>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Commune</td>
                    <td>0002</td>
                    <td>Bobigny</td>
                    <td>2010</td>
                    <td>12 mois</td>
                    <td>6000 €</td>
                    <td>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant association</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant PDV</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Compléter le projet</a>
                        </li>
                        <li>
                            <a href="../webrsa/create_projetactivite" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer projet activité</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer convention fille PDV</a>
                        </li>
                    </td>
                </tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>Communauté d'agglomération</td>
                    <td>0003</td>
                    <td>Communaté d'agglomération de Paris</td>
                    <td>2010</td>
                    <td>3 mois</td>
                    <td>500 €</td>
                    <td>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant association</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant PDV</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Compléter le projet</a>
                        </li>
                        <li>
                            <a href="../webrsa/create_projetactivite" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer projet activité</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer convention fille PDV</a>
                        </li>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Association</td>
                    <td>0004</td>
                    <td>Adullact</td>
                    <td>2010</td>
                    <td>18 mois</td>
                    <td>8000 €</td>
                    <td>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant association</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer avenant PDV</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Compléter le projet</a>
                        </li>
                        <li>
                            <a href="../webrsa/create_projetactivite" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer projet activité</a>
                        </li>
                        <li>
                            <a href="../webrsa/saisie_candidature" title=""><img src="liste_appels_a_projet_fichiers/disk.png">Créer convention fille PDV</a>
                        </li>
                    </td>
                </tr>
            </tbody>
        </table>
    </div><br>
<input value="Imprimer cette page" onclick="printit();" type="button">    </div>
  <div class="clearer"><hr></div>
            </div>
            <div id="pageFooter">
    webrsa v. 1.0.4.398 (CakePHP v. 1.2.2.8120) - 2009@Adullact.
    Page construite en 0,28 secondes.    $LastChangedDate: 2009-07-27 17:51:26 +0200 (lun., 27 juil. 2009)$
</div>
</body></xhtml>
<?php endif;?>