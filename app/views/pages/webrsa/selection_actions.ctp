<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php $this->pageTitle = 'Sélection des actions';?>
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
        'Organisme de Formation'
    );

?>
<?php echo $form->create( 'Actions', array( 'type' => 'post', 'action' => '../pages/display/webrsa/selection_actions/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<fieldset><legend>Recherche de candidatures</legend>

<?php
    echo $form->input( 'Actions.type_struct', array('disabled'=>false, 'label' => 'Type de structures', 'options' => $typesstructs ) );
//     echo $form->input( 'Actions.numconvention', array('disabled'=>false, 'label' => 'N° de la convention', 'options' => $numconvention ) );
//     echo $form->input( 'Actions.nomstruct', array('disabled'=>false, 'label' => 'Nom de la structure', 'options' => $nomstruct ) );
//     echo $form->input( 'Actions.anneeconvention', array('disabled'=>false, 'label' => 'Année de la convention', 'options' => $annee ) );
//     echo $form->input( 'Actions.numconvention', array('disabled'=>false, 'label' => 'Statut de la convention', 'options' => $statut ) );
//     echo $form->input( 'Actions.referent', array('disabled'=>false, 'label' => 'N° de la convention', 'options' => $referent ) );
?>
</fieldset>
<div class="submit noprint"><?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
</div>
<?php echo $form->end();?>

<?php $innerTable =
        '<table id="innerTable" class="innerTable">
            <tbody>
                <tr>
                    <th>Commune</th>
                    <td>Bobigny</td>
                </tr>
            </tbody>
        </table>';
?>
<?php if( !empty( $this->data ) ):?>
<div class="">
    <h1>Sélection des actions</h1>

    <form method="post" action="selection_actions">
         <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Type de structure</th>
                    <th>Catégorie</th>
                    <th>Filière</th>
                    <th>Nom structure</th>
                    <th>Subvention demandée</th>
                    <th>Subvention selon barême</th>
                    <th>Subvention proposée CG</th>
                    <th>Motif</th>
                    <th>Coche FSE</th>
                    <th>Allocataires réalisés / prévus N-1</th>
                    <th>Objectif résultat N-1</th>
                    <th>Montant retenu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if( $this->data['Actions']['type_struct'] == '0' || $this->data['Actions']['type_struct'] == '1' ):?>
                <tr class="odd dynamic" id="innerTableTrigger0" >
                    <td>SIAE</td>
                    <td>Régie de quartier</td>
                    <td>N/A</td>
                    <td>A</td>
                    <td>2000€</td>
                    <td>1500€</td>
                    <td>1000€</td>
                    <td>
                        <select>
                            <option value=""></option>
                            <option value="02" >En attente</option>
                            <option value="01" selected="Favorable">Favorable</option>
                            <option value="03">Rejeté</option>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" value="1" checked="checked" />
                    </td>
                    <td></td>
                    <td></td>
                    <td>1500€</td>
                    <td><a href="../webrsa/saisie_candidature_indiv" title=""><img src="suivi_stagiaires_fichiers/disk.png"> Supprimer</a></td>
                </tr>
                <?php endif;?>
            <?php if( $this->data['Actions']['type_struct'] == '0' || $this->data['Actions']['type_struct'] == '2' ):?>
                <tr class="even dynamic" id="innerTableTrigger0">
                    <td>Organisme de Formation</td>
                    <td>Drancy</td>
                    <td>Restauration</td>
                    <td>A</td>
                    <td>2000€</td>
                    <td>1500€</td>
                    <td>1000€</td>
                    <td>
                        <select>
                            <option value=""></option>
                            <option value="02" selected="En attente"  >En attente</option>
                            <option value="01" >Favorable</option>
                            <option value="03">Rejeté</option>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" />
                    </td>
                    <td></td>
                    <td></td>
                    <td>1500€</td>
                    <td><a href="../webrsa/saisie_candidature_indiv" title=""><img src="suivi_stagiaires_fichiers/disk.png"> Supprimer</a></td>
                </tr>
            <?php endif;?>
            </tbody>
        </table>
    </div><br>
    <div class="submit">
        <input value="Enregistrer projet" type="submit">
        <input value="Soumettre à commission" type="submit">
        <input value="Valider suite commission" type="submit">
    </div>
</form></div>
  <div class="clearer"><hr></div>
            </div>

</body></html>
<?php endif;?>