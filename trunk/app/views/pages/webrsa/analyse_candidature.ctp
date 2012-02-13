<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Analyse des candidatures';?>
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
    $typesstructs = array(
        '',
        'SIAE',
        'Organisme de Formation - ODF'
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
<?php echo $form->create( 'Candidature', array( 'type' => 'post', 'action' => '../pages/display/webrsa/analyse_candidature/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche de candidatures</legend>

<?php
    echo $form->input( 'Candidature.type_struct', array('disabled'=>false, 'label' => 'Type de structures', 'options' => $typesstructs ) );
    echo $form->input( 'Candidature.commune', array('disabled'=>false, 'label' => 'Nom de la commune' ) );
//     echo $form->input( 'Candidature.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure', 'options' => $nomstruct ) );
//     echo $form->input( 'Candidature.anneeconvention', array('disabled'=>false, 'label' => 'Année de la convention', 'options' => $annee ) );
//     echo $form->input( 'Candidature.numconvention', array('disabled'=>false, 'label' => 'Statut de la convention', 'options' => $statut ) );
//     echo $form->input( 'Candidature.referent', array('disabled'=>false, 'label' => 'N° de la convention', 'options' => $referent ) );
?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>


<?php if( !empty( $this->data ) ):?>
<div class="">
    <h1>Analyse des candidatures</h1>

    <form method="post" action="analyse_candidature">
         <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom de structure</th>
                    <th>Commune structure</th>
                    <th>Catégorie de l'action / activité</th>
                    <th>Public</th>
                    <th>Eligible FSE ?</th>
                    <th>Allocataires réalisés / prévus N-1</th>
                    <th>Agréée DDTEFP ?</th>
                    <th>Décision</th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>SIAE</td>
                    <td>Bobigny</td>
                    <td>Régie quartier</td>
                    <td>??</td>
                    <td>Non</td>
                    <td>30/32</td>
                    <td>Oui</td>
                    <td>
                        <select>
                            <option value=""></option>
                            <option value="02" >En attente</option>
                            <option value="01" selected="Favorable">Favorable</option>
                            <option value="03">Rejeté</option>
                        </select>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>ODF</td>
                    <td>Drancy</td>
                    <td>Alpha</td>
                    <td>??</td>
                    <td>Oui</td>
                    <td>42/40</td>
                    <td>Non</td>
                    <td>
                        <select>
                            <option value=""></option>
                            <option value="02" selected="En attente">En attente</option>
                            <option value="01">Favorable</option>
                            <option value="03">Rejeté</option>
                        </select>
                    </td>
                </tr>
                <tr class="odd dynamic" id="innerTableTrigger0">
                    <td>Assoc insertion sociale</td>
                    <td>Villepinte</td>
                    <td>Redynamisation</td>
                    <td>??</td>
                    <td>Non</td>
                    <td>13/9</td>
                    <td>N/A</td>
                    <td>
                        <select>
                            <option value=""></option>
                            <option value="02" >En attente</option>
                            <option value="01" selected="Favorable">Favorable</option>
                            <option value="03">Rejeté</option>
                        </select>
                    </td>
                </tr>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Assoc création entreprises</td>
                    <td>Clichy/Bois</td>
                    <td>FLE</td>
                    <td>??</td>
                    <td>Non</td>
                    <td>15/17</td>
                    <td>N/A</td>
                    <td>
                        <select>
                            <option value=""></option>
                            <option value="02" >En attente</option>
                            <option value="01">Favorable</option>
                            <option value="03" selected="Rejeté">Rejeté</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div><br>
<div class="submit"><input value="Enregistrer" type="submit"></div>    </form></div>
  <div class="clearer"><hr></div>
            </div>

</body></html>
<?php endif;?>